package com.budgetpersonale.demo.controller;

import jakarta.servlet.http.HttpSession;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;

import com.budgetpersonale.demo.security.SessionUser;

@Controller
public class HomeController {

    @GetMapping("/")
    public String home(HttpSession session) {
        if (session.getAttribute(SessionUser.USER_ID_KEY) != null) {
            return "redirect:/dashboard";
        }
        return "redirect:/login";
    }
}
