package com.budgetpersonale.demo.controller;

import com.budgetpersonale.demo.dto.LoginForm;
import com.budgetpersonale.demo.dto.RegisterForm;
import com.budgetpersonale.demo.entity.User;
import com.budgetpersonale.demo.exception.DuplicateEmailException;
import com.budgetpersonale.demo.exception.InvalidCredentialsException;
import com.budgetpersonale.demo.exception.ValidationException;
import com.budgetpersonale.demo.security.SessionUser;
import com.budgetpersonale.demo.service.UserService;
import jakarta.servlet.http.HttpSession;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PostMapping;

@Controller
public class AuthController {

    private final UserService userService;

    public AuthController(UserService userService) {
        this.userService = userService;
    }

    @GetMapping("/login")
    public String loginForm(Model model) {
        if (!model.containsAttribute("loginForm")) {
            model.addAttribute("loginForm", new LoginForm());
        }
        return "login";
    }

    @PostMapping("/login")
    public String login(@ModelAttribute("loginForm") LoginForm form, HttpSession session, Model model) {
        try {
            User user = userService.login(form);
            session.setAttribute(SessionUser.USER_ID_KEY, user.getId());
            session.setAttribute(SessionUser.USER_NAME_KEY, user.getName());
            return "redirect:/dashboard";
        } catch (InvalidCredentialsException e) {
            model.addAttribute("errorMessage", e.getMessage());
            return "login";
        }
    }

    @GetMapping("/logout")
    public String logout(HttpSession session) {
        session.invalidate();
        return "redirect:/login";
    }

    @GetMapping("/register")
    public String registerForm(Model model) {
        if (!model.containsAttribute("registerForm")) {
            model.addAttribute("registerForm", new RegisterForm());
        }
        return "register";
    }

    @PostMapping("/register")
    public String register(@ModelAttribute("registerForm") RegisterForm form, Model model) {
        try {
            userService.register(form);
            model.addAttribute("successMessage", "Registrazione completata! Ora puoi effettuare il login.");
            model.addAttribute("loginForm", new LoginForm());
            return "login";
        } catch (ValidationException | DuplicateEmailException e) {
            model.addAttribute("errorMessage", e.getMessage());
            return "register";
        }
    }
}
