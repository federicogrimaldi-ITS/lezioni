package com.budgetpersonale.demo.exception;

import jakarta.servlet.http.HttpServletRequest;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.ControllerAdvice;
import org.springframework.web.bind.annotation.ExceptionHandler;
import org.springframework.web.multipart.MaxUploadSizeExceededException;

import java.time.LocalDateTime;
import java.util.LinkedHashMap;
import java.util.Map;

@ControllerAdvice
public class GlobalExceptionHandler {

    private boolean isApiRequest(HttpServletRequest request) {
        return request.getRequestURI().startsWith("/api/");
    }

    private ResponseEntity<Map<String, Object>> buildApiError(HttpStatus status, String message) {
        Map<String, Object> body = new LinkedHashMap<>();
        body.put("timestamp", LocalDateTime.now());
        body.put("status", status.value());
        body.put("error", status.getReasonPhrase());
        body.put("message", message);
        return ResponseEntity.status(status).body(body);
    }

    @ExceptionHandler(ResourceNotFoundException.class)
    public Object handleNotFound(ResourceNotFoundException ex, HttpServletRequest request, Model model) {
        if (isApiRequest(request)) {
            return buildApiError(HttpStatus.NOT_FOUND, ex.getMessage());
        }
        model.addAttribute("errorMessage", ex.getMessage());
        model.addAttribute("status", 404);
        return "error";
    }

    @ExceptionHandler(DuplicateEmailException.class)
    public Object handleDuplicateEmail(DuplicateEmailException ex, HttpServletRequest request, Model model) {
        if (isApiRequest(request)) {
            return buildApiError(HttpStatus.CONFLICT, ex.getMessage());
        }
        model.addAttribute("errorMessage", ex.getMessage());
        model.addAttribute("status", 409);
        return "error";
    }

    @ExceptionHandler(InvalidCredentialsException.class)
    public Object handleInvalidCredentials(InvalidCredentialsException ex, HttpServletRequest request, Model model) {
        if (isApiRequest(request)) {
            return buildApiError(HttpStatus.UNAUTHORIZED, ex.getMessage());
        }
        model.addAttribute("errorMessage", ex.getMessage());
        model.addAttribute("status", 401);
        return "error";
    }

    @ExceptionHandler(ValidationException.class)
    public Object handleValidation(ValidationException ex, HttpServletRequest request, Model model) {
        if (isApiRequest(request)) {
            return buildApiError(HttpStatus.BAD_REQUEST, ex.getMessage());
        }
        model.addAttribute("errorMessage", ex.getMessage());
        model.addAttribute("status", 400);
        return "error";
    }

    @ExceptionHandler(FileStorageException.class)
    public Object handleFileStorage(FileStorageException ex, HttpServletRequest request, Model model) {
        if (isApiRequest(request)) {
            return buildApiError(HttpStatus.INTERNAL_SERVER_ERROR, ex.getMessage());
        }
        model.addAttribute("errorMessage", ex.getMessage());
        model.addAttribute("status", 500);
        return "error";
    }

    @ExceptionHandler(MaxUploadSizeExceededException.class)
    public Object handleMaxUpload(MaxUploadSizeExceededException ex, HttpServletRequest request, Model model) {
        String message = "Il file caricato supera la dimensione massima consentita (10MB).";
        if (isApiRequest(request)) {
            return buildApiError(HttpStatus.PAYLOAD_TOO_LARGE, message);
        }
        model.addAttribute("errorMessage", message);
        model.addAttribute("status", 413);
        return "error";
    }

    @ExceptionHandler(Exception.class)
    public Object handleGeneric(Exception ex, HttpServletRequest request, Model model) {
        if (isApiRequest(request)) {
            return buildApiError(HttpStatus.INTERNAL_SERVER_ERROR, "Si e' verificato un errore interno: " + ex.getMessage());
        }
        model.addAttribute("errorMessage", "Si e' verificato un errore imprevisto: " + ex.getMessage());
        model.addAttribute("status", 500);
        return "error";
    }
}
