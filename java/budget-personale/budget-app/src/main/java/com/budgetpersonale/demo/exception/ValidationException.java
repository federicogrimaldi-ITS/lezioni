package com.budgetpersonale.demo.exception;

/**
 * Eccezione usata per la validazione manuale dei form/DTO, dato che il
 * progetto non include lo starter di Bean Validation (hibernate-validator).
 */
public class ValidationException extends RuntimeException {
    public ValidationException(String message) {
        super(message);
    }
}
