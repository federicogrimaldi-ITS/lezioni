package com.budgetpersonale.demo.service;

import com.budgetpersonale.demo.exception.FileStorageException;
import com.budgetpersonale.demo.exception.ValidationException;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.stereotype.Service;
import org.springframework.util.StringUtils;
import org.springframework.web.multipart.MultipartFile;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.nio.file.StandardCopyOption;
import java.util.List;
import java.util.UUID;

@Service
public class FileStorageService {

    private static final List<String> ALLOWED_CONTENT_TYPES = List.of(
            "image/jpeg", "image/png", "application/pdf"
    );

    private final Path uploadPath;

    public FileStorageService(@Value("${app.upload.dir}") String uploadDir) {
        this.uploadPath = Paths.get(uploadDir).toAbsolutePath().normalize();
        try {
            Files.createDirectories(this.uploadPath);
        } catch (IOException e) {
            throw new FileStorageException("Impossibile creare la cartella per l'upload delle ricevute", e);
        }
    }

    /**
     * Salva il file ricevuto e restituisce il percorso relativo (es. uploads/xxxx.jpg)
     * da memorizzare nel campo "receipt" della transazione.
     */
    public String store(MultipartFile file) {
        if (file == null || file.isEmpty()) {
            return null;
        }
        if (!ALLOWED_CONTENT_TYPES.contains(file.getContentType())) {
            throw new ValidationException("Formato ricevuta non supportato. Sono ammessi solo JPG, PNG e PDF.");
        }

        String originalFilename = StringUtils.cleanPath(file.getOriginalFilename() != null ? file.getOriginalFilename() : "ricevuta");
        String extension = "";
        int dotIndex = originalFilename.lastIndexOf('.');
        if (dotIndex >= 0) {
            extension = originalFilename.substring(dotIndex);
        }
        String storedFilename = UUID.randomUUID() + extension;

        try {
            Path target = this.uploadPath.resolve(storedFilename);
            Files.copy(file.getInputStream(), target, StandardCopyOption.REPLACE_EXISTING);
        } catch (IOException e) {
            throw new FileStorageException("Impossibile salvare il file: " + originalFilename, e);
        }

        return "uploads/" + storedFilename;
    }
}
