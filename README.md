# Habit Tracker

Jednoduchý školský PHP OOP projekt bez frameworku.

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

## Inštalácia v XAMPP
1. Skopíruj projekt do `htdocs/habit_tracker`.
2. Spusti Apache a MySQL.
3. V phpMyAdmin importuj súbor `config/schema.sql`.
4. Skontroluj údaje v `config/Database.php`.
5. Najistejší spôsob spustenia:

```bash
cd C:\xampp\htdocs\habit_tracker
php -S localhost:8000 -t public
```

Potom otvor:

```text
http://localhost:8000
```

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

## Poznámka ku skúške
Projekt používa PDO prepared statements, sessions, OOP triedy, CRUD operácie a relácie v databáze cez foreign keys.
