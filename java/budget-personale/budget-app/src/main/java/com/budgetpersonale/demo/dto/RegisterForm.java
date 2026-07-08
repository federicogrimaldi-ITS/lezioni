package com.budgetpersonale.demo.dto;

import lombok.Data;

@Data
public class RegisterForm {
    private String name;
    private String email;
    private String password;
    private String confirmPassword;
}
