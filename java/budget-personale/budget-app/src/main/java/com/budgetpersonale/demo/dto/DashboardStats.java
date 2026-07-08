package com.budgetpersonale.demo.dto;

import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.math.BigDecimal;
import java.util.List;
import java.util.Map;

@Data
@Builder
@NoArgsConstructor
@AllArgsConstructor
public class DashboardStats {
    private BigDecimal totalEntrate;
    private BigDecimal totalUscite;
    private BigDecimal saldoTotale;
    private Map<String, BigDecimal> speseCategorie;
    private List<MonthlyBalance> bilancioMensile;

    @Data
    @Builder
    @NoArgsConstructor
    @AllArgsConstructor
    public static class MonthlyBalance {
        private String mese;
        private BigDecimal entrate;
        private BigDecimal uscite;
    }
}
