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

| Role        | Email                   | Password   | URL          |
|-------------|-------------------------|------------|--------------|
| Admin       | admin@example.com       | `password` | `/admin`     |
| Employee    | employee@example.com    | `password` | `/employee`  |
| Customer    | customer@example.com    | `password` | `/dashboard` |

All seeded users use password `password` (set in `UserFactory`).

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
