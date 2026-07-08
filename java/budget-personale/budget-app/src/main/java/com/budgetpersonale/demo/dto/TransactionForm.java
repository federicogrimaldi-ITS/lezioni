package com.budgetpersonale.demo.dto;

import lombok.Data;
import org.springframework.web.multipart.MultipartFile;

import java.math.BigDecimal;
import java.time.LocalDate;

@Data
public class TransactionForm {
    private Long id;
    private String description;
    private LocalDate date = LocalDate.now();
    private BigDecimal amount;
    private String type; // ENTRATA / USCITA
    private String category;
    private MultipartFile receiptFile;
}
