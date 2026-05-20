# Car Rental Management

A multi-role car rental management system built with Laravel 13, Filament 5, Livewire 4, and Tailwind CSS. Handles the full rental lifecycle — from public vehicle browsing and booking to employee-managed rentals and admin financial oversight.

---

## Features

### Public Frontend (`/`)

- [x] Browse available vehicles with date range filtering
- [x] Car listing cards (image, transmission badge, year, license plate, daily rate)
- [x] Multi-step booking wizard (rental details → customer details → payment)
- [x] Guest checkout — auto-creates account on booking
- [x] Pricing: 12-hour block system, half-day / full-day / multi-day
- [x] Self-drive or with-driver options with driver fee calculation
- [x] Delivery method (pickup/delivery)
- [x] Language switcher (EN | ID)
- [ ] Dedicated customer registration page
- [ ] Search/filter cars by name, transmission, or price

### Customer Panel (`/dashboard`)

- [x] Dashboard stats (active rentals, pending payments, unpaid rentals count)
- [x] My Rentals — Active tab (Pending/Confirmed/Active) and Past tab (Completed/Cancelled)
- [x] Rental detail view with payment history
- [x] Cancel booking (when status = Pending)
- [x] Upload payment proof (cash or bank transfer)
- [x] My Profile page
- [ ] Printable invoice/receipt PDF
- [ ] Email notifications for booking & payment status changes
- [ ] Password reset flow

### Employee Panel (`/employee`)

- [x] Dashboard
- [x] Vehicle CRUD (list, create, view, edit, delete)
- [x] Rental CRUD with lifecycle actions (Confirm → Activate → Complete → Cancel)
- [x] Rental items relation manager (assign vehicle, dates, driver per rental)
- [x] People CRUD (customers, employees, drivers)
- [x] Payment CRUD
- [x] My Profile
- [ ] Vehicle return inspection (condition notes, damages, fuel level)
- [ ] Late return penalty calculation

### Admin Panel (`/admin`)

- [x] All Employee features
- [x] Dashboard stats: Revenue / Expense / Net Profit (month-over-month)
- [x] Active rentals table widget
- [x] Expense management (CRUD, 15 categories, grouped, proof upload) — super_admin only
- [ ] Revenue/expense charts and trend graphs
- [ ] Export reports (CSV/Excel)
- [ ] Vehicle maintenance schedule tracking

### Auth & Access Control

- [x] Role-based panel routing (5 roles: SuperAdmin, Admin, Employee, Customer, Driver)
- [x] Smart login redirect (role → correct panel)
- [x] Logout redirect to homepage
- [ ] Email verification for new accounts
- [ ] Password reset for all panels

### Data & Seeding

- [x] Rich seeder: 20 vehicles, 11 customers, 5 drivers, 2 employees, 1 admin, 1 superadmin
- [x] Seeded rentals in all statuses with payments and expenses
- [ ] Vehicle images (currently all seeded with `null` image)
- [ ] Vehicle status field (availability inferred via date overlap query — possible race condition)

### Testing (31 tests)

- [x] Booking wizard flow (8 tests)
- [x] Car listing & date filtering (3 tests)
- [x] Customer resources & navigation (7 tests)
- [x] Payment upload workflow (5 tests)
- [x] Rental management (4 tests)
- [x] Panel access & role checks (5 tests)

---

## Local Setup

```bash
git clone <repo-url> car-rental
cd car-rental

cp .env.example .env
composer install
npm install && npm run build
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

---

## Test Users

| Role        | Email                        | Password   | URL          |
|-------------|------------------------------|------------|--------------|
| Super Admin | superadmin@example.com       | `password` | `/admin`     |
| Admin       | admin@example.com            | `password` | `/admin`     |
| Employee    | employee@example.com         | `password` | `/employee`  |
| Customer    | customer@example.com         | `password` | `/dashboard` |

All seeded users use password `password`.

---

## User Guide

### Public Frontend (`/`)

Anyone can browse vehicles and book a car without an account:

1. **Browse Vehicles** — Click "Telusuri Mobil" (Browse Cars) on the homepage. Filter by pickup/return dates, choose self-drive or with-driver.
2. **Book a Car** — Click "Sewa" (Rent) on any car card. The booking wizard guides you through:
   - **Step 1 — Rental Details:** Pickup/return dates, delivery method (pickup or delivery with address).
   - **Step 2 — Customer Details:** Name, email, phone, address, ID type. If you enter a new email, an account is created automatically with your password.
   - **Step 3 — Payment:** Choose cash or bank transfer. For transfers, you will upload proof later.
3. **Track Your Booking** — After booking, log in at `/dashboard` with the email and password you provided.

### Customer Panel (`/dashboard`)

Once logged in as a customer:

| Menu | What You Can Do |
|---|---|
| **Dashboard** | See active rentals, pending payments, unpaid rental count |
| **Sewa Saya (My Rentals)** | Active tab: view Pending/Confirmed/Active rentals. Past tab: view Completed/Cancelled rentals. Click any rental for detail, payment history, and cancel option (if Pending) |
| **Upload Payment** | From a rental detail page, upload payment proof (photo/PDF) for bank transfer bookings |
| **My Profile** | View your account info (name, email, phone, address, ID) |

### Employee Panel (`/employee`)

For staff managing day-to-day operations:

| Menu | What You Can Do |
|---|---|
| **Dashboard** | Overview of rentals in progress |
| **Rentals** | View all rentals. Use action buttons to move rentals through the lifecycle: **Confirm** → **Activate** → **Complete** → **Cancel**. Click a rental to manage its items (assigned vehicles, dates, drivers) |
| **Vehicles** | Add/edit/delete vehicles. Set name, year, transmission, license plate, rental rate, and upload images |
| **People** | Manage customers, employees, and drivers. View contact info, ID documents, and driver fees |
| **Payments** | View and verify payments uploaded by customers |
| **My Profile** | View your account |

**Rental Lifecycle:**
```
Pending → Confirmed → Active → Completed
                  ↘ Cancelled
```

- **Pending:** Customer submitted booking, awaiting staff confirmation.
- **Confirmed:** Staff approved the booking. Vehicle allocated.
- **Active:** Customer has the car. Rental in progress.
- **Completed:** Car returned. Rental finished.
- **Cancelled:** Booking cancelled by customer or staff.

### Admin Panel (`/admin`)

For owners/managers — same as employee panel plus financial oversight:

| Menu | What You Can Do |
|---|---|
| **Dashboard** | Revenue / Expense / Net Profit stats with month-over-month comparison, active rentals table |
| **Expenses** (Super Admin only) | Track all operational costs: employee salaries, driver wages, THR, bonuses, maintenance, vehicle tax, insurance, fuel, cleaning, spare parts, office rent, utilities, marketing, and other. Upload proof files, categorize, and filter by date |
| **Everything Employee Has** | All rental, vehicle, people, payment management |

The **Super Admin** user has full access to all panels and all features including expense management. The **Admin** user has everything except expense management.

### Quick Rental Flow (Example)

1. **Customer** browses cars at `/` → books a Toyota Avanza for 3 days, bank transfer → rental status: **Pending**
2. **Employee/Admin** logs in at `/employee` or `/admin` → sees pending rental → clicks **Confirm** → rental status: **Confirmed**
3. **Employee/Admin** clicks **Activate** when customer picks up the car → rental status: **Active**
4. **Customer** uploads payment proof from `/dashboard` → **Employee/Admin** verifies payment
5. **Employee/Admin** clicks **Complete** when car is returned → rental status: **Completed**

---

## Tech Stack

| Layer       | Technology              |
|-------------|-------------------------|
| Backend     | PHP 8.3, Laravel 13     |
| Admin Panel | Filament 5              |
| Frontend    | Livewire 4, Alpine.js   |
| Styling     | Tailwind CSS            |
| Database    | SQLite (dev) / MySQL    |
| Testing     | Pest 4                  |
| i18n        | English + Indonesian    |
