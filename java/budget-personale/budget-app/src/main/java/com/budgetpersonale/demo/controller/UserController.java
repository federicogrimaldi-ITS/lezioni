package com.budgetpersonale.demo.controller;

import com.budgetpersonale.demo.dto.ProfileForm;
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
public class UserController {

    private final UserService userService;

    public UserController(UserService userService) {
        this.userService = userService;
    }

    private Long currentUserId(HttpSession session) {
        return (Long) session.getAttribute(SessionUser.USER_ID_KEY);
    }

    @GetMapping("/profile")
    public String profile(HttpSession session, Model model) {
        User user = userService.getById(currentUserId(session));
        ProfileForm form = new ProfileForm();
        form.setName(user.getName());
        form.setEmail(user.getEmail());
        form.setFinancialGoal(user.getFinancialGoal());
        model.addAttribute("profileForm", form);
        model.addAttribute("user", user);
        return "profile";
    }

    @PostMapping("/profile")
    public String updateProfile(@ModelAttribute("profileForm") ProfileForm form, HttpSession session, Model model) {
        try {
            User updated = userService.updateProfile(currentUserId(session), form);
            session.setAttribute(SessionUser.USER_NAME_KEY, updated.getName());
            model.addAttribute("successMessage", "Profilo aggiornato con successo.");
            model.addAttribute("user", updated);
            return "profile";
        } catch (ValidationException | DuplicateEmailException | InvalidCredentialsException e) {
            model.addAttribute("errorMessage", e.getMessage());
            model.addAttribute("user", userService.getById(currentUserId(session)));
            return "profile";
        }
    }
}
