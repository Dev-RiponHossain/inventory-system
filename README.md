# 📦 StockLedger — Inventory Management System

A simplified Inventory Management System built with **Laravel 12**, **Blade Templates**, **Tailwind CSS**, and **MySQL** — featuring proper double-entry accounting journal entries and date-wise financial reports.

---

## 🎯 Features

- ✅ Product Management (Add, Edit, Delete)
- ✅ Sale Entry with automatic stock deduction
- ✅ Auto-generated Double-Entry Accounting Journal Entries
- ✅ Discount & VAT calculation
- ✅ Payment tracking (Paid / Partial / Due)
- ✅ Date-wise Financial Report with filter
- ✅ Dashboard with KPIs
- ✅ Demo data via Database Seeder

---

## 📊 Assignment Business Scenario

| Field | Value |
|---|---|
| Purchase Price | 100 TK |
| Sell Price | 200 TK |
| Opening Stock | 50 units |
| Units Sold | 10 |
| Discount | 50 TK |
| VAT | 5% |
| Customer Paid | 1000 TK |

**Calculation:**
```
Gross Sale     = 10 × 200        = 2000.00 TK
Discount       =                 =   50.00 TK
Net Amount     = 2000 - 50       = 1950.00 TK
VAT (5%)       = 1950 × 5%       =   97.50 TK
Total Payable  = 1950 + 97.50    = 2047.50 TK
Amount Paid    =                 = 1000.00 TK
Due Amount     = 2047.50 - 1000  = 1047.50 TK
COGS           = 10 × 100        = 1000.00 TK
Gross Profit   = 1950 - 1000     =  950.00 TK
```

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12 (PHP) |
| Frontend | Blade Templates |
| Styling | Tailwind CSS (CDN) |
| Database | MySQL |
| ORM | Eloquent |

---

## ⚙️ Requirements

- PHP >= 8.2
- Composer
- MySQL 5.7+
- Git

---

## 🚀 Installation & Setup (Step by Step)

### Step 1 — Clone the repository
```bash
git clone https://github.com/YOUR_USERNAME/inventory-system.git
cd inventory-system
```

### Step 2 — Install PHP dependencies
```bash
composer install
```

### Step 3 — Environment setup
```bash
cp .env.example .env
php artisan key:generate
```

### Step 4 — Configure database in `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_db
DB_USERNAME=root
DB_PASSWORD=
```

> 💡 If using XAMPP, leave DB_PASSWORD empty

### Step 5 — Create database
Open phpMyAdmin or MySQL and run:
```sql
CREATE DATABASE inventory_db;
```

### Step 6 — Run migrations with demo data
```bash
php artisan migrate --seed
```

This will automatically load:
- ✅ Sample Product (100 TK purchase / 200 TK sell / 50 units)
- ✅ Demo Sale (10 units / 50 TK discount / 5% VAT / 1000 TK paid)
- ✅ 3 Journal Entries auto-created

### Step 7 — Start the server
```bash
php artisan serve
```

### Step 8 — Open in browser
```
http://localhost:8000
```

---

## 📁 Project Structure

```
inventory-system/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php
│   │   ├── ProductController.php
│   │   ├── SaleController.php
│   │   ├── JournalController.php
│   │   └── ReportController.php
│   └── Models/
│       ├── Product.php
│       ├── Sale.php
│       ├── JournalEntry.php
│       └── JournalEntryLine.php
├── database/
│   ├── migrations/
│   └── seeders/DatabaseSeeder.php
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── dashboard.blade.php
│   ├── products/
│   ├── sales/
│   ├── journal/
│   └── reports/
├── routes/web.php
└── README.md
```

---

## 📒 Journal Entries (Auto-Generated on Sale)

### Entry 1 — Sale Recording
| Account | DR | CR |
|---|---|---|
| Accounts Receivable | 2047.50 | |
| Sales Revenue | | 1950.00 |
| VAT Payable | | 97.50 |
| Discount Allowed | 50.00 | |
| Sales (Gross) | | 50.00 |

### Entry 2 — Cost of Goods Sold
| Account | DR | CR |
|---|---|---|
| Cost of Goods Sold | 1000.00 | |
| Inventory | | 1000.00 |

### Entry 3 — Cash Received
| Account | DR | CR |
|---|---|---|
| Cash / Bank | 1000.00 | |
| Accounts Receivable | | 1000.00 |

---

## 📅 Pages

| Page | URL | Description |
|---|---|---|
| Dashboard | `/` | KPI summary, recent sales, stock |
| Products | `/products` | Product list, add, edit, delete |
| New Sale | `/sales/create` | Sale form with live calculation |
| Sales List | `/sales` | All transactions |
| Sale Detail | `/sales/{id}` | Invoice + journal entries |
| Journal | `/journal` | All journal entries with filter |
| Reports | `/reports` | Date-wise financial report |

---

## 🌐 Deploy to Railway.app (Free)

1. Push code to GitHub
2. Go to [railway.app](https://railway.app) → Login with GitHub
3. Click **New Project** → **Deploy from GitHub repo**
4. Add **MySQL** plugin
5. Add environment variables from `.env`
6. Run: `php artisan migrate --seed`
7. Done! 🚀

---

**Laravel 12 | Blade | Tailwind CSS | MySQL**
