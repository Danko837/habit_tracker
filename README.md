# Habit Tracker

Jednoduchý PHP OOP projekt bez frameworku.

## Funkcie
- registrácia používateľa
- prihlásenie / odhlásenie
- sessions
- hashovanie hesiel cez `password_hash()`
- CRUD pre návyky
- označenie návyku ako splnený dnes
- štatistika pokroku
- MySQL databáza s cudzími kľúčmi
- jednoduchá MVC/OOP štruktúra

## Štruktúra
```text
config/Database.php      pripojenie na databázu
config/schema.sql        databázová schéma
controllers/             controllery
models/                  modely pre databázu
views/                   HTML/PHP šablóny
public/index.php         hlavný vstup do aplikácie
public/assets/style.css  dizajn
```

## Tabuľky
- `users`
- `habits`
- `habit_logs`

