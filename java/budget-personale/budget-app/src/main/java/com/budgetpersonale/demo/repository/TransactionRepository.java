package com.budgetpersonale.demo.repository;

import com.budgetpersonale.demo.entity.Transaction;
import com.budgetpersonale.demo.entity.TransactionType;
import com.budgetpersonale.demo.entity.User;
import org.springframework.data.jpa.repository.JpaRepository;

import java.time.LocalDate;
import java.util.List;
import java.util.Optional;

public interface TransactionRepository extends JpaRepository<Transaction, Long> {

    List<Transaction> findByUserOrderByDateDesc(User user);

    Optional<Transaction> findByIdAndUser(Long id, User user);

    List<Transaction> findByUserAndCategoryOrderByDateDesc(User user, String category);

    List<Transaction> findByUserAndDateBetweenOrderByDateDesc(User user, LocalDate start, LocalDate end);

    List<Transaction> findByUserAndType(User user, TransactionType type);
}
