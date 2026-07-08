package com.budgetpersonale.demo.security;

import org.springframework.stereotype.Component;

import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.security.SecureRandom;
import java.util.Base64;

/**
 * NOTA: il pom.xml fornito per questa esercitazione non include
 * spring-boot-starter-security (e quindi nemmeno BCryptPasswordEncoder).
 * Per rispettare l'obiettivo "password memorizzate in modo sicuro" senza
 * aggiungere dipendenze non richieste, viene implementato un encoder
 * basato su SHA-256 con salt casuale per ogni utente (formato: salt:hash,
 * entrambi in Base64). Se in futuro si aggiunge spring-boot-starter-security,
 * questa classe puo' essere sostituita da BCryptPasswordEncoder.
 */
@Component
public class PasswordEncoder {

    private static final int SALT_LENGTH = 16;
    private static final String ALGORITHM = "SHA-256";
    private final SecureRandom secureRandom = new SecureRandom();

    public String encode(String rawPassword) {
        byte[] salt = new byte[SALT_LENGTH];
        secureRandom.nextBytes(salt);
        byte[] hash = hash(rawPassword, salt);
        return Base64.getEncoder().encodeToString(salt) + ":" + Base64.getEncoder().encodeToString(hash);
    }

    public boolean matches(String rawPassword, String encodedPassword) {
        if (encodedPassword == null || !encodedPassword.contains(":")) {
            return false;
        }
        String[] parts = encodedPassword.split(":", 2);
        byte[] salt = Base64.getDecoder().decode(parts[0]);
        byte[] expectedHash = Base64.getDecoder().decode(parts[1]);
        byte[] actualHash = hash(rawPassword, salt);
        return MessageDigest.isEqual(expectedHash, actualHash);
    }

    private byte[] hash(String password, byte[] salt) {
        try {
            MessageDigest digest = MessageDigest.getInstance(ALGORITHM);
            digest.update(salt);
            return digest.digest(password.getBytes());
        } catch (NoSuchAlgorithmException e) {
            throw new IllegalStateException("Algoritmo di hashing non disponibile: " + ALGORITHM, e);
        }
    }
}
