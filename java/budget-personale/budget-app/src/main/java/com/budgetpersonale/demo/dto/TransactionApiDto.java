package com.budgetpersonale.demo.dto;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.math.BigDecimal;
import java.time.LocalDate;

/**
 * DTO usato dalla REST API: non espone l'entita' User per intero,
 * solo l'id del proprietario.
 */
@Data
@NoArgsConstructor
@AllArgsConstructor
public class TransactionApiDto {
    private Long id;
    private String description;
    private LocalDate date;
    private BigDecimal amount;
    private String type;
    private String category;
    private String receipt;
}
