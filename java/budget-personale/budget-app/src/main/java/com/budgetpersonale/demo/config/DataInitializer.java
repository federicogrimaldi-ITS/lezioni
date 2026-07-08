package com.budgetpersonale.demo.config;

import com.budgetpersonale.demo.entity.Transaction;
import com.budgetpersonale.demo.entity.TransactionType;
import com.budgetpersonale.demo.entity.User;
import com.budgetpersonale.demo.repository.TransactionRepository;
import com.budgetpersonale.demo.repository.UserRepository;
import com.budgetpersonale.demo.security.PasswordEncoder;
import org.springframework.boot.CommandLineRunner;
import org.springframework.stereotype.Component;

import java.math.BigDecimal;
import java.time.LocalDate;

/**
 * Popola il database H2 con utenti e transazioni di esempio (equivalenti
 * agli INSERT proposti nella traccia) al primo avvio dell'applicazione.
 * Le password vengono cifrate con il PasswordEncoder dell'applicazione
 * (vedi nota nella classe PasswordEncoder sul perche' non si usa BCrypt).
 */
@Component
public class DataInitializer implements CommandLineRunner {

    private final UserRepository userRepository;
    private final TransactionRepository transactionRepository;
    private final PasswordEncoder passwordEncoder;

    public DataInitializer(UserRepository userRepository, TransactionRepository transactionRepository,
                            PasswordEncoder passwordEncoder) {
        this.userRepository = userRepository;
        this.transactionRepository = transactionRepository;
        this.passwordEncoder = passwordEncoder;
    }

    @Override
    public void run(String... args) {
        if (userRepository.count() > 0) {
            return;
        }

        User giovanni = userRepository.save(User.builder()
                .name("Giovanni Rossi")
                .email("giovanni@example.com")
                .password(passwordEncoder.encode("password123"))
                .financialGoal("Risparmiare per le vacanze")
                .build());

        User maria = userRepository.save(User.builder()
                .name("Maria Bianchi")
                .email("maria@example.com")
                .password(passwordEncoder.encode("password123"))
                .financialGoal("Fondo di emergenza")
                .build());

        User luca = userRepository.save(User.builder()
                .name("Luca Verdi")
                .email("luca@example.com")
                .password(passwordEncoder.encode("password123"))
                .financialGoal("Comprare una nuova auto")
                .build());

        transactionRepository.save(Transaction.builder()
                .user(giovanni)
                .description("Affitto Settembre")
                .date(LocalDate.of(2025, 9, 1))
                .amount(new BigDecimal("850.00"))
                .type(TransactionType.USCITA)
                .category("Affitto")
                .receipt(null)
                .build());

        transactionRepository.save(Transaction.builder()
                .user(maria)
                .description("Stipendio")
                .date(LocalDate.of(2025, 9, 5))
                .amount(new BigDecimal("1500.00"))
                .type(TransactionType.ENTRATA)
                .category("Stipendio")
                .receipt(null)
                .build());

        transactionRepository.save(Transaction.builder()
                .user(luca)
                .description("Supermercato")
                .date(LocalDate.of(2025, 9, 10))
                .amount(new BigDecimal("80.50"))
                .type(TransactionType.USCITA)
                .category("Spese Generali")
                .receipt(null)
                .build());

        transactionRepository.save(Transaction.builder()
                .user(giovanni)
                .description("Stipendio Settembre")
                .date(LocalDate.of(2025, 9, 27))
                .amount(new BigDecimal("1800.00"))
                .type(TransactionType.ENTRATA)
                .category("Stipendio")
                .build());

        transactionRepository.save(Transaction.builder()
                .user(giovanni)
                .description("Cinema con amici")
                .date(LocalDate.of(2025, 9, 15))
                .amount(new BigDecimal("25.00"))
                .type(TransactionType.USCITA)
                .category("Tempo Libero")
                .build());

        transactionRepository.save(Transaction.builder()
                .user(giovanni)
                .description("Abbonamento bus")
                .date(LocalDate.of(2025, 9, 3))
                .amount(new BigDecimal("40.00"))
                .type(TransactionType.USCITA)
                .category("Trasporti")
                .build());

        System.out.println("Dati di esempio caricati. Utenti di test (password: password123):");
        System.out.println(" - giovanni@example.com");
        System.out.println(" - maria@example.com");
        System.out.println(" - luca@example.com");
    }
}
