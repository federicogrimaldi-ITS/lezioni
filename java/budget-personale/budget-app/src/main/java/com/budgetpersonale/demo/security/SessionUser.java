package com.budgetpersonale.demo.security;

/**
 * Chiavi usate per memorizzare i dati dell'utente autenticato in HttpSession.
 */
public final class SessionUser {

    public static final String USER_ID_KEY = "AUTH_USER_ID";
    public static final String USER_NAME_KEY = "AUTH_USER_NAME";

    private SessionUser() {
    }
}
