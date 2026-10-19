# Watermelon Lab — Digital Menu System

A production-ready bilingual (English / Arabic) digital menu for **Watermelon Lab**, built with pure PHP 8+, MySQL, HTML/CSS/JS. Mobile-first, Instagram-worthy UI with a cozy watermelon café aesthetic — no item photos required.

---

## Features

- Beautiful welcome language screen (EN / AR)
- Session + localStorage language preference
- Full RTL support for Arabic
- Category pills, instant AJAX search & filtering
- Premium typography-led menu cards (no images)
- Soft animations & micro-interactions
- Admin dashboard with secure login
- Category & menu item CRUD
- Cafe settings (name, logo, colors, WhatsApp, socials, welcome text)
- Prepared statements, CSRF protection, XSS escaping

---

## Folder Structure

```
menu_cu/
├── admin/                 # Admin panel pages
│   ├── includes/          # Admin layout partials
│   ├── login.php
│   ├── logout.php
│   ├── index.php          # Dashboard
│   ├── categories.php
│   ├── items.php
│   └── settings.php
├── api/                   # AJAX endpoints
│   ├── items.php
│   └── language.php
├── assets/
│   ├── css/               # Public + admin styles
│   ├── js/                # Public + admin scripts
│   └── img/               # Default logo SVG
├── config/                # App & DB configuration
├── core/                  # Bootstrap, Database, helpers
├── database/
│   └── watermelon_lab.sql # phpMyAdmin import file
├── includes/              # Reserved for shared views
├── lang/                  # UI translations (en.php, ar.php)
├── models/                # OOP data models
├── uploads/               # Admin-uploaded logos
├── index.php              # Language welcome screen
├── menu.php               # Public digital menu
└── README.md
```

---

## Requirements

- XAMPP (or equivalent) with:
  - PHP 8.0+
  - MySQL / MariaDB
  - Apache with `mod_rewrite` (optional)
- Extensions: `pdo_mysql`, `mbstring`, `fileinfo`

---

## Installation (XAMPP + phpMyAdmin)

### 1. Place the project

This project lives at:

```
/Applications/XAMPP/xamppfiles/htdocs/menu_cu
```

Start **Apache** and **MySQL** from the XAMPP control panel.

### 2. Import the database

1. Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Click **Import**
3. Choose `database/watermelon_lab.sql`
4. Click **Go**

This creates the `watermelon_lab` database, tables, sample menu, and default admin user.

### 3. Configure database credentials (if needed)

Edit `config/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'watermelon_lab');
define('DB_USER', 'root');
define('DB_PASS', '');          // default XAMPP password is empty
define('BASE_URL', '/menu_cu'); // change if folder name differs
```

### 4. Open the menu

- Public menu: [http://localhost/menu_cu/](http://localhost/menu_cu/)
- Admin panel: [http://localhost/menu_cu/admin/login.php](http://localhost/menu_cu/admin/login.php)

### Default admin login

| Field    | Value     |
|----------|-----------|
| Username | `admin`   |
| Password | `admin123`|

**Change this password after first login** (update via phpMyAdmin or a future password page).

---

## Brand Colors

| Role            | Hex       |
|-----------------|-----------|
| Primary Green   | `#226F54` |
| Watermelon Red  | `#D33C3C` |
| Soft Background | `#E2E7D8` |
| Dark Brown      | `#43291F` |
| White           | `#FFFFFF` |

Colors can also be adjusted from **Admin → Settings**.

---

## Usage Notes

### Public menu
1. Choose language on first visit
2. Browse categories or search instantly
3. Switch language anytime from the header chip

### Admin
1. Manage categories (EN/AR names, order, status)
2. Manage items (descriptions, price, order, status)
3. Update cafe identity, social links, and welcome messages

Prices are displayed in **IQD**.

---

## Security Practices

- PDO prepared statements for all queries
- `password_hash` / `password_verify` for admin auth
- CSRF tokens on admin forms
- `htmlspecialchars` output escaping
- Session regeneration on login
- Upload MIME + size validation
- Sensitive directories blocked via `.htaccess`
- No PHP execution in `/uploads`

---

## Troubleshooting

**Blank page / DB error**  
Confirm MySQL is running and `watermelon_lab` was imported. Check credentials in `config/config.php`.

**Wrong asset paths**  
Update `BASE_URL` to match your folder name under `htdocs`.

**Logo upload fails**  
Ensure `uploads/` is writable by Apache (`chmod 755` or `775`).

**Arabic looks wrong**  
Database and tables must use `utf8mb4`. The SQL file already sets this.

---

## Tech Stack

- PHP 8+ (OOP models, MVC-like structure)
- MySQL
- HTML5 / CSS3 / Vanilla JavaScript
- AJAX (`fetch`) for search & filtering

Built for Watermelon Lab — cute, cozy, premium.
