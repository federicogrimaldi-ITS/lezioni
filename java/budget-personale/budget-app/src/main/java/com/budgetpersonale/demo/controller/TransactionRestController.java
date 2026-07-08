package com.budgetpersonale.demo.controller;

import com.budgetpersonale.demo.dto.TransactionApiDto;
import com.budgetpersonale.demo.dto.TransactionForm;
import com.budgetpersonale.demo.entity.Transaction;
import com.budgetpersonale.demo.entity.User;
import com.budgetpersonale.demo.security.SessionUser;
import com.budgetpersonale.demo.service.TransactionService;
import com.budgetpersonale.demo.service.UserService;
import jakarta.servlet.http.HttpSession;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;

/**
 * REST API per la gestione delle transazioni (JSON).
 * Nota: gli endpoint qui sotto ricevono la transazione come JSON e quindi
 * non supportano l'upload contestuale della ricevuta: per caricare una
 * ricevuta si usano i form Thymeleaf (multipart) esposti da TransactionController.
 */
@RestController
@RequestMapping("/api/transactions")
public class TransactionRestController {

    private final TransactionService transactionService;
    private final UserService userService;

    public TransactionRestController(TransactionService transactionService, UserService userService) {
        this.transactionService = transactionService;
        this.userService = userService;
    }

    private User currentUser(HttpSession session) {
        Long userId = (Long) session.getAttribute(SessionUser.USER_ID_KEY);
        return userService.getById(userId);
    }

    private TransactionApiDto toDto(Transaction t) {
        return new TransactionApiDto(
                t.getId(),
                t.getDescription(),
                t.getDate(),
                t.getAmount(),
                t.getType().name(),
                t.getCategory(),
                t.getReceipt()
        );
    }

    @GetMapping
    public List<TransactionApiDto> list(HttpSession session) {
        return transactionService.findAllByUser(currentUser(session)).stream()
                .map(this::toDto)
                .toList();
    }

    @GetMapping("/{id}")
    public TransactionApiDto get(@PathVariable Long id, HttpSession session) {
        return toDto(transactionService.findByIdAndUser(id, currentUser(session)));
    }

    @PostMapping
    public ResponseEntity<TransactionApiDto> create(@RequestBody TransactionApiDto dto, HttpSession session) {
        TransactionForm form = toForm(dto);
        Transaction created = transactionService.create(currentUser(session), form);
        return ResponseEntity.status(HttpStatus.CREATED).body(toDto(created));
    }

    @PutMapping("/{id}")
    public TransactionApiDto update(@PathVariable Long id, @RequestBody TransactionApiDto dto, HttpSession session) {
        TransactionForm form = toForm(dto);
        Transaction updated = transactionService.update(currentUser(session), id, form);
        return toDto(updated);
    }

    @DeleteMapping("/{id}")
    public ResponseEntity<Void> delete(@PathVariable Long id, HttpSession session) {
        transactionService.delete(currentUser(session), id);
        return ResponseEntity.noContent().build();
    }

    private TransactionForm toForm(TransactionApiDto dto) {
        TransactionForm form = new TransactionForm();
        form.setDescription(dto.getDescription());
        form.setDate(dto.getDate());
        form.setAmount(dto.getAmount());
        form.setType(dto.getType());
        form.setCategory(dto.getCategory());
        return form;
    }
}
