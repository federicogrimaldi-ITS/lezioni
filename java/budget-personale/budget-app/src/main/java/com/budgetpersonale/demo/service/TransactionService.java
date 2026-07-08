package com.budgetpersonale.demo.service;

import com.budgetpersonale.demo.dto.DashboardStats;
import com.budgetpersonale.demo.dto.TransactionForm;
import com.budgetpersonale.demo.entity.Transaction;
import com.budgetpersonale.demo.entity.TransactionType;
import com.budgetpersonale.demo.entity.User;
import com.budgetpersonale.demo.exception.ResourceNotFoundException;
import com.budgetpersonale.demo.exception.ValidationException;
import com.budgetpersonale.demo.repository.TransactionRepository;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import org.springframework.util.StringUtils;

import java.math.BigDecimal;
import java.time.LocalDate;
import java.time.format.TextStyle;
import java.util.Comparator;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;
import java.util.stream.Collectors;

@Service
public class TransactionService {

    private final TransactionRepository transactionRepository;
    private final FileStorageService fileStorageService;

    public TransactionService(TransactionRepository transactionRepository, FileStorageService fileStorageService) {
        this.transactionRepository = transactionRepository;
        this.fileStorageService = fileStorageService;
    }

    public List<Transaction> findAllByUser(User user) {
        return transactionRepository.findByUserOrderByDateDesc(user);
    }

    public Transaction findByIdAndUser(Long id, User user) {
        return transactionRepository.findByIdAndUser(id, user)
                .orElseThrow(() -> new ResourceNotFoundException("Transazione non trovata."));
    }

    public List<Transaction> findByCategory(User user, String category) {
        return transactionRepository.findByUserAndCategoryOrderByDateDesc(user, category);
    }

    public List<Transaction> findByDateRange(User user, LocalDate start, LocalDate end) {
        return transactionRepository.findByUserAndDateBetweenOrderByDateDesc(user, start, end);
    }

    private void validate(TransactionForm form) {
        if (!StringUtils.hasText(form.getType())) {
            throw new ValidationException("Il tipo di transazione (ENTRATA/USCITA) e' obbligatorio.");
        }
        try {
            TransactionType.valueOf(form.getType());
        } catch (IllegalArgumentException e) {
            throw new ValidationException("Tipo di transazione non valido. Valori ammessi: ENTRATA, USCITA.");
        }
        if (form.getAmount() == null || form.getAmount().compareTo(BigDecimal.ZERO) <= 0) {
            throw new ValidationException("L'importo deve essere maggiore di zero.");
        }
        if (form.getDate() == null) {
            throw new ValidationException("La data e' obbligatoria.");
        }
        if (!StringUtils.hasText(form.getCategory())) {
            throw new ValidationException("La categoria e' obbligatoria.");
        }
    }

    @Transactional
    public Transaction create(User user, TransactionForm form) {
        validate(form);
        String receiptPath = fileStorageService.store(form.getReceiptFile());

        Transaction transaction = Transaction.builder()
                .user(user)
                .description(form.getDescription())
                .date(form.getDate())
                .amount(form.getAmount())
                .type(TransactionType.valueOf(form.getType()))
                .category(form.getCategory())
                .receipt(receiptPath)
                .build();

        return transactionRepository.save(transaction);
    }

    @Transactional
    public Transaction update(User user, Long id, TransactionForm form) {
        validate(form);
        Transaction transaction = findByIdAndUser(id, user);

        transaction.setDescription(form.getDescription());
        transaction.setDate(form.getDate());
        transaction.setAmount(form.getAmount());
        transaction.setType(TransactionType.valueOf(form.getType()));
        transaction.setCategory(form.getCategory());

        if (form.getReceiptFile() != null && !form.getReceiptFile().isEmpty()) {
            String receiptPath = fileStorageService.store(form.getReceiptFile());
            transaction.setReceipt(receiptPath);
        }

        return transactionRepository.save(transaction);
    }

    @Transactional
    public void delete(User user, Long id) {
        Transaction transaction = findByIdAndUser(id, user);
        transactionRepository.delete(transaction);
    }

    public DashboardStats computeStats(User user) {
        List<Transaction> transactions = findAllByUser(user);

        BigDecimal totalEntrate = transactions.stream()
                .filter(t -> t.getType() == TransactionType.ENTRATA)
                .map(Transaction::getAmount)
                .reduce(BigDecimal.ZERO, BigDecimal::add);

        BigDecimal totalUscite = transactions.stream()
                .filter(t -> t.getType() == TransactionType.USCITA)
                .map(Transaction::getAmount)
                .reduce(BigDecimal.ZERO, BigDecimal::add);

        BigDecimal saldo = totalEntrate.subtract(totalUscite);

        Map<String, BigDecimal> speseCategorie = transactions.stream()
                .filter(t -> t.getType() == TransactionType.USCITA)
                .collect(Collectors.groupingBy(
                        Transaction::getCategory,
                        LinkedHashMap::new,
                        Collectors.reducing(BigDecimal.ZERO, Transaction::getAmount, BigDecimal::add)
                ));

        Map<String, DashboardStats.MonthlyBalance> monthlyMap = new LinkedHashMap<>();
        transactions.stream()
                .sorted(Comparator.comparing(Transaction::getDate))
                .forEach(t -> {
                    String key = t.getDate().getYear() + "-" + String.format("%02d", t.getDate().getMonthValue());
                    String label = t.getDate().getMonth().getDisplayName(TextStyle.FULL, Locale.ITALIAN) + " " + t.getDate().getYear();
                    DashboardStats.MonthlyBalance mb = monthlyMap.computeIfAbsent(key, k ->
                            DashboardStats.MonthlyBalance.builder()
                                    .mese(label)
                                    .entrate(BigDecimal.ZERO)
                                    .uscite(BigDecimal.ZERO)
                                    .build());
                    if (t.getType() == TransactionType.ENTRATA) {
                        mb.setEntrate(mb.getEntrate().add(t.getAmount()));
                    } else {
                        mb.setUscite(mb.getUscite().add(t.getAmount()));
                    }
                });

        return DashboardStats.builder()
                .totalEntrate(totalEntrate)
                .totalUscite(totalUscite)
                .saldoTotale(saldo)
                .speseCategorie(speseCategorie)
                .bilancioMensile(List.copyOf(monthlyMap.values()))
                .build();
    }
}
