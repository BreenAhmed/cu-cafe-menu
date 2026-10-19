# CU Cafe — Hostinger Deployment Guide

Domain: **https://cumenu.com**

---

## 1. Upload files

1. Log in to Hostinger → **File Manager**
2. Open `public_html`
3. Delete default Hostinger placeholder files if present (`index.html`, etc.)
4. Upload `cumenu-hostinger.zip`
5. Extract it **inside** `public_html` so you see:
   - `index.php`
   - `menu.php`
   - `admin/`
   - `config/`
   - `assets/`
   - etc.

If extraction creates a subfolder, move all files up into `public_html`.

---

## 2. Import the database

1. Open Hostinger → **Databases** → **phpMyAdmin**
2. Select database: `u100517965_cumenu`
3. Click **Import**
4. Choose file: `database/hostinger_import.sql`
5. Click **Go**

---

## 3. Set folder permissions

In File Manager, set `uploads/` to **755** (or 775 if logo upload fails).

---

## 4. Test the website

| Page | URL |
|------|-----|
| Menu | https://cumenu.com/ |
| Admin | https://cumenu.com/admin/login.php |

**Admin login**
- Username: `admin`
- Password: `admin123`

Change this password after first login.

---

## Notes

- Config auto-detects Hostinger vs local XAMPP
- SSL: enable **Force HTTPS** in Hostinger → Domains → SSL
- Keep `database/` folder private if possible (already blocked by `.htaccess`)
