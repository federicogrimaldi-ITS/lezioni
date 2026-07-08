package com.budgetpersonale.demo.service;

import com.budgetpersonale.demo.dto.LoginForm;
import com.budgetpersonale.demo.dto.ProfileForm;
import com.budgetpersonale.demo.dto.RegisterForm;
import com.budgetpersonale.demo.entity.User;
import com.budgetpersonale.demo.exception.DuplicateEmailException;
import com.budgetpersonale.demo.exception.InvalidCredentialsException;
import com.budgetpersonale.demo.exception.ResourceNotFoundException;
import com.budgetpersonale.demo.exception.ValidationException;
import com.budgetpersonale.demo.repository.UserRepository;
import com.budgetpersonale.demo.security.PasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import org.springframework.util.StringUtils;

@Service
public class UserService {

    private final UserRepository userRepository;
    private final PasswordEncoder passwordEncoder;

    public UserService(UserRepository userRepository, PasswordEncoder passwordEncoder) {
        this.userRepository = userRepository;
        this.passwordEncoder = passwordEncoder;
    }

    @Transactional
    public User register(RegisterForm form) {
        if (!StringUtils.hasText(form.getName()) || !StringUtils.hasText(form.getEmail())
                || !StringUtils.hasText(form.getPassword())) {
            throw new ValidationException("Nome, email e password sono obbligatori.");
        }
        if (!form.getPassword().equals(form.getConfirmPassword())) {
            throw new ValidationException("Le password non coincidono.");
        }
        if (form.getPassword().length() < 6) {
            throw new ValidationException("La password deve contenere almeno 6 caratteri.");
        }
        if (userRepository.existsByEmail(form.getEmail())) {
            throw new DuplicateEmailException("Esiste gia' un account con questa email.");
        }

        User user = User.builder()
                .name(form.getName())
                .email(form.getEmail())
                .password(passwordEncoder.encode(form.getPassword()))
                .build();

        return userRepository.save(user);
    }

    public User login(LoginForm form) {
        User user = userRepository.findByEmail(form.getEmail())
                .orElseThrow(() -> new InvalidCredentialsException("Email o password non corretti."));
        if (!passwordEncoder.matches(form.getPassword(), user.getPassword())) {
            throw new InvalidCredentialsException("Email o password non corretti.");
        }
        return user;
    }

    public User getById(Long id) {
        return userRepository.findById(id)
                .orElseThrow(() -> new ResourceNotFoundException("Utente non trovato."));
    }

    @Transactional
    public User updateProfile(Long userId, ProfileForm form) {
        User user = getById(userId);

        if (!StringUtils.hasText(form.getName()) || !StringUtils.hasText(form.getEmail())) {
            throw new ValidationException("Nome ed email sono obbligatori.");
        }

        if (!user.getEmail().equalsIgnoreCase(form.getEmail()) && userRepository.existsByEmail(form.getEmail())) {
            throw new DuplicateEmailException("Esiste gia' un account con questa email.");
        }

        user.setName(form.getName());
        user.setEmail(form.getEmail());
        user.setFinancialGoal(form.getFinancialGoal());

        if (StringUtils.hasText(form.getNewPassword())) {
            if (!passwordEncoder.matches(form.getCurrentPassword(), user.getPassword())) {
                throw new InvalidCredentialsException("La password attuale non e' corretta.");
            }
            if (!form.getNewPassword().equals(form.getConfirmNewPassword())) {
                throw new ValidationException("Le nuove password non coincidono.");
            }
            if (form.getNewPassword().length() < 6) {
                throw new ValidationException("La nuova password deve contenere almeno 6 caratteri.");
            }
            user.setPassword(passwordEncoder.encode(form.getNewPassword()));
        }

        return userRepository.save(user);
    }
}
