package com.budgetpersonale.demo.dto;

import lombok.Data;

@Data
public class ProfileForm {
    private String name;
    private String email;
    private String financialGoal;
    private String currentPassword;
    private String newPassword;
    private String confirmNewPassword;
}
