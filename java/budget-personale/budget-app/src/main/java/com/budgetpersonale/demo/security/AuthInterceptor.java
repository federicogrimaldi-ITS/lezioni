package com.budgetpersonale.demo.security;

import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.servlet.http.HttpSession;
import org.springframework.web.servlet.HandlerInterceptor;

/**
 * Interceptor che sostituisce (in forma semplificata) la protezione delle
 * pagine normalmente offerta da Spring Security, non presente tra le
 * dipendenze richieste per questo progetto. Solo login, registrazione,
 * risorse statiche e le API pubbliche restano accessibili senza sessione.
 */
public class AuthInterceptor implements HandlerInterceptor {

    @Override
    public boolean preHandle(HttpServletRequest request, HttpServletResponse response, Object handler) throws Exception {
        HttpSession session = request.getSession(false);
        boolean authenticated = session != null && session.getAttribute(SessionUser.USER_ID_KEY) != null;

        if (!authenticated) {
            String accept = request.getHeader("Accept");
            boolean wantsJson = accept != null && accept.contains("application/json");
            if (wantsJson || request.getRequestURI().startsWith("/api/")) {
                response.setStatus(HttpServletResponse.SC_UNAUTHORIZED);
                response.setContentType("application/json");
                response.getWriter().write("{\"error\":\"Non autenticato\"}");
            } else {
                response.sendRedirect(request.getContextPath() + "/login");
            }
            return false;
        }
        return true;
    }
}
