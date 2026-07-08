package com.budgetpersonale.demo.controller;

import com.budgetpersonale.demo.dto.TransactionForm;
import com.budgetpersonale.demo.entity.Transaction;
import com.budgetpersonale.demo.entity.TransactionType;
import com.budgetpersonale.demo.entity.User;
import com.budgetpersonale.demo.exception.ValidationException;
import com.budgetpersonale.demo.security.SessionUser;
import com.budgetpersonale.demo.service.TransactionService;
import com.budgetpersonale.demo.service.UserService;
import jakarta.servlet.http.HttpSession;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@Controller
public class TransactionController {

    private final TransactionService transactionService;
    private final UserService userService;

    public TransactionController(TransactionService transactionService, UserService userService) {
        this.transactionService = transactionService;
        this.userService = userService;
    }

    private User currentUser(HttpSession session) {
        Long userId = (Long) session.getAttribute(SessionUser.USER_ID_KEY);
        return userService.getById(userId);
    }

    @GetMapping("/dashboard")
    public String dashboard(HttpSession session, Model model) {
        User user = currentUser(session);
        model.addAttribute("user", user);
        model.addAttribute("stats", transactionService.computeStats(user));
        return "dashboard";
    }

    @GetMapping("/transactions")
    public String list(HttpSession session, Model model,
                        @RequestParam(value = "category", required = false) String category) {
        User user = currentUser(session);
        List<Transaction> transactions;
        if (category != null && !category.isBlank()) {
            transactions = transactionService.findByCategory(user, category);
        } else {
            transactions = transactionService.findAllByUser(user);
        }
        model.addAttribute("transactions", transactions);
        model.addAttribute("selectedCategory", category);
        model.addAttribute("categories", categories());
        return "transactions";
    }

    @GetMapping("/transactions/new")
    public String newForm(Model model) {
        model.addAttribute("transactionForm", new TransactionForm());
        model.addAttribute("categories", categories());
        model.addAttribute("types", TransactionType.values());
        return "transaction-form";
    }

    @PostMapping("/transactions")
    public String create(@ModelAttribute("transactionForm") TransactionForm form, HttpSession session, Model model) {
        User user = currentUser(session);
        try {
            transactionService.create(user, form);
            return "redirect:/transactions";
        } catch (ValidationException e) {
            model.addAttribute("errorMessage", e.getMessage());
            model.addAttribute("categories", categories());
            model.addAttribute("types", TransactionType.values());
            return "transaction-form";
        }
    }

    @GetMapping("/transactions/{id}/edit")
    public String editForm(@PathVariable Long id, HttpSession session, Model model) {
        User user = currentUser(session);
        Transaction transaction = transactionService.findByIdAndUser(id, user);

        TransactionForm form = new TransactionForm();
        form.setId(transaction.getId());
        form.setDescription(transaction.getDescription());
        form.setDate(transaction.getDate());
        form.setAmount(transaction.getAmount());
        form.setType(transaction.getType().name());
        form.setCategory(transaction.getCategory());

        model.addAttribute("transactionForm", form);
        model.addAttribute("existingReceipt", transaction.getReceipt());
        model.addAttribute("categories", categories());
        model.addAttribute("types", TransactionType.values());
        return "transaction-form";
    }

    @PostMapping("/transactions/{id}")
    public String update(@PathVariable Long id, @ModelAttribute("transactionForm") TransactionForm form,
                          HttpSession session, Model model) {
        User user = currentUser(session);
        try {
            transactionService.update(user, id, form);
            return "redirect:/transactions";
        } catch (ValidationException e) {
            model.addAttribute("errorMessage", e.getMessage());
            model.addAttribute("categories", categories());
            model.addAttribute("types", TransactionType.values());
            return "transaction-form";
        }
    }

    @PostMapping("/transactions/{id}/delete")
    public String delete(@PathVariable Long id, HttpSession session) {
        User user = currentUser(session);
        transactionService.delete(user, id);
        return "redirect:/transactions";
    }

    private List<String> categories() {
        return List.of("Affitto", "Stipendio", "Spese Generali", "Trasporti", "Tempo Libero", "Altro");
    }
}
