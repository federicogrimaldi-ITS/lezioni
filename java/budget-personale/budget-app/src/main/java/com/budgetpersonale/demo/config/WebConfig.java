package com.budgetpersonale.demo.config;

import com.budgetpersonale.demo.security.AuthInterceptor;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.context.annotation.Configuration;
import org.springframework.web.servlet.config.annotation.InterceptorRegistry;
import org.springframework.web.servlet.config.annotation.ResourceHandlerRegistry;
import org.springframework.web.servlet.config.annotation.WebMvcConfigurer;

@Configuration
public class WebConfig implements WebMvcConfigurer {

    @Value("${app.upload.dir}")
    private String uploadDir;

    @Override
    public void addInterceptors(InterceptorRegistry registry) {
        registry.addInterceptor(new AuthInterceptor())
                .addPathPatterns("/dashboard/**", "/transactions/**", "/profile/**", "/api/**")
                .excludePathPatterns("/login", "/register", "/logout", "/css/**", "/js/**", "/h2-console/**");
    }

    @Override
    public void addResourceHandlers(ResourceHandlerRegistry registry) {
        // Le ricevute caricate sono servite come risorse statiche protette dall'interceptor
        registry.addResourceHandler("/uploads/**")
                .addResourceLocations("file:" + uploadDir + "/");
    }
}
