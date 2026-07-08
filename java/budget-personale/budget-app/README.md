# Budget Personale

Applicazione Spring Boot (Java 21) per il monitoraggio del budget personale, sviluppata seguendo
la traccia dell'esercitazione, con architettura MVC + REST API.

## Come avviarla

```bash
mvn spring-boot:run
```

L'app parte su http://localhost:8080 e reindirizza al login.

Utenti demo gia' presenti nel database (creati automaticamente al primo avvio):

| Email | Password |
|---|---|
| giovanni@example.com | password123 |
| maria@example.com | password123 |
| luca@example.com | password123 |

Puoi anche registrare un nuovo account dalla pagina "Registrati".

Console H2 disponibile su http://localhost:8080/h2-console
(JDBC URL: `jdbc:h2:file:./data/budgetdb`, utente `sa`, password vuota).

## Differenze rispetto alla traccia originale (dovute alle dipendenze richieste)

Il `pom.xml` fornito **non include** `spring-boot-starter-security` ne' lo starter di Bean
Validation (hibernate-validator), e usa **H2** al posto di MySQL. Per rispettare esattamente le
dipendenze indicate, ho quindi adattato l'esercitazione così:

- **Sicurezza/autenticazione**: al posto di Spring Security, l'app usa un `AuthInterceptor`
  personalizzato (Spring MVC `HandlerInterceptor`) che protegge `/dashboard`, `/transactions`,
  `/profile` e `/api/**` controllando la presenza di un utente in sessione HTTP (`HttpSession`).
  Login/logout/registrazione restano le uniche pagine pubbliche, come richiesto.
- **Password**: non essendoci `BCryptPasswordEncoder` (fa parte di spring-security-crypto), ho
  implementato un `PasswordEncoder` custom basato su SHA-256 + salt casuale per utente
  (classe `security/PasswordEncoder.java`, ben documentata nel codice). Se in futuro aggiungi
  `spring-boot-starter-security` al progetto, puoi sostituirla con `BCryptPasswordEncoder` senza
  cambiare il resto del codice (stessa firma `encode`/`matches`).
- **Validazione**: senza hibernate-validator non sono disponibili le annotazioni `@NotNull`,
  `@Valid`, ecc. La validazione dei form (registrazione, transazioni, profilo) e' quindi fatta
  manualmente nei service (`UserService`, `TransactionService`), che lanciano una
  `ValidationException` gestita centralmente dal `@ControllerAdvice`.
- **Database**: MySQL sostituito con H2 (file-based, i dati persistono in `./data/budgetdb.mv.db`).
  Lo schema viene creato automaticamente da Hibernate (`ddl-auto=update`), equivalente alle
  `CREATE TABLE` della traccia.

Tutto il resto (entity, repository, service, controller MVC, REST API, upload ricevute,
dashboard con grafici Chart.js, gestione eccezioni con `@ControllerAdvice`) segue la traccia.

## Struttura del progetto

```
src/main/java/com/budgetpersonale/demo
├── DemoApplication.java
├── config        (WebConfig, DataInitializer)
├── security      (AuthInterceptor, PasswordEncoder, SessionUser)
├── entity        (User, Transaction, TransactionType)
├── repository    (UserRepository, TransactionRepository)
├── dto           (form e DTO REST)
├── service       (UserService, TransactionService, FileStorageService)
├── controller    (AuthController, UserController, TransactionController, TransactionRestController)
└── exception     (eccezioni custom + GlobalExceptionHandler)
```

## REST API

| Metodo | Endpoint | Descrizione |
|---|---|---|
| GET | /api/transactions | Elenco transazioni dell'utente loggato |
| GET | /api/transactions/{id} | Dettaglio transazione |
| POST | /api/transactions | Inserimento (JSON) |
| PUT | /api/transactions/{id} | Modifica (JSON) |
| DELETE | /api/transactions/{id} | Eliminazione |

Le API richiedono una sessione autenticata (fai prima login da `/login`, il cookie di sessione
verra' riutilizzato dalle chiamate successive, es. da Postman con "cookie jar" abilitato).

## Nota

Il progetto non e' stato compilato in questo ambiente (l'accesso a Maven Central non e'
disponibile qui), quindi ti consiglio di lanciare `mvn clean install` in locale come primo passo
per verificare che tutto scarichi e compili correttamente.
