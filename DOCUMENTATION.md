# Vamika - Complete End-to-End Project Documentation

> **Document Version**: 2.0.0  
> **Last Updated**: March 2026  
> **Framework**: Laravel 12.x (PHP 8.2+)  
> **Primary Purpose**: Comprehensive technical reference, architecture guide, and operational manual for the Vamika B2B Sales Management Platform before undertaking any system modifications.

---

## Table of Contents

1. [Executive Summary & Project Purpose](#1-executive-summary--project-purpose)
2. [Technology Stack & Runtime Architecture](#2-technology-stack--runtime-architecture)
3. [System Architecture & Directory Structure](#3-system-architecture--directory-structure)
4. [Authentication & User Lifecycle Flows](#4-authentication--user-lifecycle-flows)
5. [Core Middleware & Request Pipeline](#5-core-middleware--request-pipeline)
6. [Role-Based Access Control (RBAC) Matrix](#6-role-based-access-control-rbac-matrix)
7. [Comprehensive Routes & Controllers Directory](#7-comprehensive-routes--controllers-directory)
8. [End-to-End Business Workflows](#8-end-to-end-business-workflows)
   - [8.1 Territory & Bit Management](#81-territory--bit-management)
   - [8.2 Working Hours Enforcement Engine](#82-working-hours-enforcement-engine)
   - [8.3 Salesperson Field Visits & No-Order Tracking](#83-salesperson-field-visits--no-order-tracking)
   - [8.4 Order Lifecycle & Real-Time Stock Engine](#84-order-lifecycle--real-time-stock-engine)
   - [8.5 Order Consolidation & Warehouse Dispatch](#85-order-consolidation--warehouse-dispatch)
   - [8.6 Wallet, Ledger & Signup Bonus System](#86-wallet-ledger--signup-bonus-system)
   - [8.7 Birthday & CRM Engagement Engine](#87-birthday--crm-engagement-engine)
   - [8.8 System Settings & Company Profile](#88-system-settings--company-profile)
9. [Database Schema & Migration Audit (All 32 Migrations)](#9-database-schema--migration-audit-all-32-migrations)
10. [Eloquent Models & Relationship Mapping](#10-eloquent-models--relationship-mapping)
11. [Seeders & Default Test Accounts](#11-seeders--default-test-accounts)
12. [Frontend Architecture & UI/UX Design System](#12-frontend-architecture--uiux-design-system)
13. [Critical Architectural Nuances, Gotchas & Edge Cases](#13-critical-architectural-nuances-gotchas--edge-cases)
14. [Developer Guidelines & Safe Change Protocol](#14-developer-guidelines--safe-change-protocol)

---

## 1. Executive Summary & Project Purpose

**Vamika** is an enterprise-grade multi-role B2B (Business-to-Business) FMCG / distribution and sales management platform built on Laravel 12. The application automates supply-chain distribution between a central manufacturer/distributor and retail pharmacies/grocery outlets.

The platform coordinates three interconnected user roles:
1. **Admin**: Executive management overseeing company-wide sales, territory bits, stock inventories, catalog pricing, order fulfillment, promotional offers, operational reports, and system-wide configurations.
2. **Salesperson**: Field sales representatives on daily market beats ("bits") visiting retail outlets, taking orders in real-time, logging visit reasons when no purchase is made, tracking daily incentives, and onboarding new shops.
3. **Shop Owner**: Retail merchants who can either place direct self-service B2B replenishment orders, track consignment deliveries, manage their digital cash-back wallet, download tax invoices, and refer peer merchants.

---

## 2. Technology Stack & Runtime Architecture

### Backend Infrastructure
- **Framework**: Laravel 12.x (`laravel/framework: ^12.0`)
- **PHP Version**: PHP 8.2+
- **Database**: SQLite (default local development configuration via `database.sqlite`) / MySQL / PostgreSQL compliant.
- **ORM**: Laravel Eloquent with strict transactional guarantees (`DB::transaction`).
- **Authentication**: Laravel Session-based Auth with custom hashed credentials (`Illuminate\Support\Facades\Hash`).
- **Asset Storage**: Laravel Public Storage Disk (`storage/app/public` symlinked to `public/storage`).

### Frontend Architecture
- **Templating**: Laravel Blade with nested layouts and reusable partials.
- **Styling**: Tailwind CSS (CDN-delivered with custom color variables), Animate.css, and custom CSS utility classes.
- **Typography**: Google Fonts (`Inter` for body UI, `Outfit` for headings and display numbers).
- **Icons**: Iconify Web Components (`iconify-icon`) & Font Awesome 6.4.0.
- **UI Notifications**: Toastify.js (`toast.js` wrapper) & SweetAlert2 (`sweetalert2@11`).
- **Data Visualization**: Chart.js for executive analytics and territory reporting.
- **Form Interactivity**: Native JavaScript with Vanilla DOM manipulation and Fetch API.

---

## 3. System Architecture & Directory Structure

```
c:\Users\Admin\Desktop\projects\vamika\
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                    # Administrative controllers
│   │   │   │   ├── BirthdayController.php
│   │   │   │   ├── BitController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── OfferController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── ReportController.php
│   │   │   │   ├── SettingsController.php
│   │   │   │   └── UserController.php
│   │   │   ├── Auth/                     # Core authentication
│   │   │   │   ├── ForgotPasswordController.php
│   │   │   │   ├── LoginController.php
│   │   │   │   └── RegisterController.php
│   │   │   ├── Salesperson/              # Field sales operations
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── ProfileController.php
│   │   │   │   ├── ShopController.php
│   │   │   │   └── VisitController.php
│   │   │   └── ShopOwner/                # Retail merchant portal
│   │   │       ├── CartController.php
│   │   │       ├── CheckoutController.php
│   │   │       ├── DashboardController.php
│   │   │       ├── OrderController.php
│   │   │       ├── ProductController.php
│   │   │       ├── ProfileController.php
│   │   │       └── WalletController.php
│   │   └── Middleware/                   # Guard and business rule filters
│   │       ├── AdminMiddleware.php       # Enforces role === 'admin'
│   │       ├── CheckWorkingHours.php     # Blocks salesperson actions outside operating hours
│   │       ├── SalespersonMiddleware.php # Enforces role === 'salesperson'
│   │       └── ShopOwnerMiddleware.php   # Enforces role === 'shop-owner'
│   └── Models/                           # Eloquent Entity Models
│       ├── Bit.php
│       ├── Category.php
│       ├── Offer.php
│       ├── Order.php
│       ├── OrderItem.php
│       ├── Product.php
│       ├── ProductImage.php
│       ├── Setting.php
│       ├── Shop.php
│       ├── User.php
│       ├── Visit.php
│       ├── Wallet.php
│       └── WalletTransaction.php
├── bootstrap/
│   └── app.php                           # Application bootstrap & middleware aliases
├── config/                               # Standard Laravel configuration files
├── database/
│   ├── factories/                        # User and Model test factories
│   ├── migrations/                       # 32 sequential schema migration files
│   └── seeders/                          # Database seeders (Static data & Ahmedabad data)
│       ├── AhmedabadDataSeeder.php
│       ├── DatabaseSeeder.php
│       ├── InitialUserSeeder.php
│       └── SettingSeeder.php
├── public/
│   ├── assets/                           # Custom CSS stylesheets, JS helpers, and images
│   └── storage/                          # Symlink to public uploaded media
├── resources/
│   └── views/                            # Blade UI templates
│       ├── admin/                        # Admin views (dashboard, bits, orders, reports...)
│       ├── auth/                         # Login, Register, Forgot Password
│       ├── layouts/                      # Base layouts (admin, salesperson, shop-owner, guest)
│       │   └── partials/                 # Headers, bottom navigation bars, notifications
│       ├── salesperson/                  # Salesperson views (dashboard, visits, cart, review)
│       └── shop-owner/                   # Shop Owner views (catalog, cart, checkout, orders)
└── routes/
    ├── console.php                       # Artisan console commands
    └── web.php                           # Complete web route declarations
```

---

## 4. Authentication & User Lifecycle Flows

### 4.1 Multi-Role Login Workflow (`LoginController`)
- **Route**: `GET /login` -> renders `auth.login`
- **Action**: `POST /login` -> `LoginController@login`
- **Validation**: `email` (required, email), `password` (required, string).
- **Authentication Flow**:
  1. `Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $remember)`
  2. If credentials fail -> redirects back with error: *"Invalid credentials provided."*
  3. Checks account status: If `$user->status === 'inactive'`, session is invalidated, user is logged out, and redirected with error: *"Your account has been deactivated. Please contact administrator."*
  4. Role-based routing:
     - Role `admin` -> Redirects to `route('admin.dashboard')` (`/admin/dashboard`)
     - Role `salesperson` -> Redirects to `route('salesperson.dashboard')` (`/salesperson/dashboard`)
     - Role `shop-owner` -> Redirects to `route('shop-owner.dashboard')` (`/shop-owner/dashboard`)
     - Any other role -> Aborts with `403 Unauthorized`.

### 4.2 Merchant Self-Registration Workflow (`RegisterController`)
- **Route**: `GET /register` -> renders `auth.register`
- **Action**: `POST /register` -> `RegisterController@register`
- **Validation Rules**:
  - `name`: `required|string|max:255`
  - `email`: `required|string|email|max:255|unique:users`
  - `password`: `required|string|min:8|confirmed`
  - `phone`: `required|string|max:20|unique:users`
  - `shop_name`: `required|string|max:255`
  - `address`: `required|string`
  - `bit_id`: `nullable|exists:bits,id`
  - `referral_code`: `nullable|string`
- **Atomic Database Transaction Execution**:
  1. **User Creation**: Creates `User` with `role = 'shop-owner'`, `status = 'active'`, `creator_type = 'self'`.
  2. **Shop Creation**: Creates `Shop` linked to user ID, setting initial `credit_limit = 0.00` and `current_balance = 0.00`.
  3. **Wallet Creation**: Creates `Wallet` linked to the new shop with `balance = 0.00`.
  4. **Signup Bonus Credit**:
     - System checks `Setting::where('key', 'referral_bonus')->value('value')` or `Setting::where('key', 'signup_bonus')->value('value')`, falling back to ₹100.
     - Adds bonus to wallet balance (`$wallet->increment('balance', $bonusAmount)`).
     - Records entry in `wallet_transactions` with `type = 'credit'`, `amount = $bonusAmount`, `description = 'Welcome signup bonus'`.
  5. **Auto-Login**: Calls `Auth::login($user)` and redirects immediately to `route('shop-owner.dashboard')` with a celebratory welcome message.

### 4.3 Salesperson-Assisted Shop Onboarding
- A salesperson in the field can onboard a new shop on the spot via `Salesperson\ShopController@store`:
  - Automatically associates the new shop with the salesperson's active `bit_id`.
  - Sets `creator_type = 'salesperson'` and `created_by = Auth::id()`.
  - Creates the merchant user, shop, and funded wallet in one seamless step.

---

## 5. Core Middleware & Request Pipeline

Middleware aliases are configured in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'salesperson' => \App\Http\Middleware\SalespersonMiddleware::class,
        'shop-owner' => \App\Http\Middleware\ShopOwnerMiddleware::class,
        'working_hours' => \App\Http\Middleware\CheckWorkingHours::class,
    ]);
})
```

### 1. `AdminMiddleware`
- Verifies `Auth::check()` and ensures `Auth::user()->role === 'admin'`.
- If unauthorized, aborts with HTTP 403.

### 2. `SalespersonMiddleware`
- Verifies `Auth::check()` and ensures `Auth::user()->role === 'salesperson'`.
- If unauthorized, aborts with HTTP 403.

### 3. `ShopOwnerMiddleware`
- Verifies `Auth::check()` and ensures `Auth::user()->role === 'shop-owner'`.
- If unauthorized, aborts with HTTP 403.

### 4. `CheckWorkingHours` (Salesperson Operational Guard)
- **Applies to**: Critical salesperson actions:
  - Placing orders (`POST /salesperson/orders`)
  - Logging visits (`POST /salesperson/visits`)
  - Marking no-order visits (`POST /salesperson/visits/{id}/no-order`)
  - Reviewing orders (`GET /salesperson/orders/{id}/review`)
- **Evaluation Logic**:
  1. If authenticated user is NOT a salesperson, request passes through immediately.
  2. Retrieves start and end work times in priority order:
     - **User Specific**: `user->work_start_time` and `user->work_end_time` (formatted as `H:i`).
     - **Global Fallback**: Database `settings` keys `salesperson_work_start` and `salesperson_work_end` (defaults to `09:00` and `18:00`).
  3. Evaluates current time `now()` against start and end boundaries.
  4. If current time falls outside operating hours:
     - If request expects JSON -> Returns HTTP 403 with error message: *"Orders and visits can only be processed during working hours ($start to $end)."*
     - If standard web request -> Redirects back with error toast notification blocking the action.

---

## 6. Role-Based Access Control (RBAC) Matrix

| Feature / Action | Admin | Salesperson | Shop Owner | Guest |
| :--- | :---: | :---: | :---: | :---: |
| View Landing / Login / Register | No (redirects) | No (redirects) | No (redirects) | Yes |
| Manage System Users (CRUD) | Full | None | None | None |
| Configure Working Hours & Company Settings | Full | None | None | None |
| Create / Edit / Delete Bits (Territories) | Full | None | None | None |
| Switch Active Bit Territory | Full | Yes (Active Bit) | None | None |
| Product Catalog Management (CRUD + Images) | Full | None | None | None |
| Manage Product Categories (CRUD) | Full | None | None | None |
| Browse Product Catalog | Full | View Only | View Only | None |
| View Outlets in Assigned Bit | Full | Yes (Assigned) | Self Only | None |
| Quick Onboard Retail Outlet | Yes | Yes (in active bit)| Self Register | None |
| Log Field Visit (Order / No-Order) | View All | Log (in bit) | None | None |
| Place B2B Replenishment Order | View All | Create (for shop) | Self-Service | None |
| Update Order Status (Pending -> Delivered)| Full | None | None | None |
| Cancel Order (Stock Restoration) | Full | Pending orders | Pending orders| None |
| Warehouse Order Consolidation Report | Full | None | None | None |
| View Digital Cash-Back Wallet | View All | None | Personal Shop | None |
| Birthday & Milestone Tracking | Full | None | None | None |
| Download / View Tax Invoices | Full | Yes | Yes | None |

---

## 7. Comprehensive Routes & Controllers Directory

### 7.1 Public & Authentication Routes (Guest / Unprotected)

| HTTP Method | URI | Controller Action | Route Name | Notes |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/` | `Auth\LoginController@showLoginForm` | - | Root redirects to login |
| `GET` | `/login` | `Auth\LoginController@showLoginForm` | `login` | Displays login interface |
| `POST` | `/login` | `Auth\LoginController@login` | - | Authenticates & redirects by role |
| `GET` | `/register` | `Auth\RegisterController@showRegistrationForm` | `register` | Merchant registration form |
| `POST` | `/register` | `Auth\RegisterController@register` | - | Atomic user, shop & wallet creation |
| `GET` | `/forgot-password` | `Auth\ForgotPasswordController@showLinkRequestForm`| `password.request` | Password reset request page |
| `POST` | `/forgot-password` | `Auth\ForgotPasswordController@sendResetLinkEmail` | `password.email` | Generates reset token |
| `POST` | `/logout` | `Auth\LoginController@logout` | `logout` | Invalidates session (auth required) |

---

### 7.2 Admin Routes (`prefix: admin`, `middleware: [auth, admin]`)

| HTTP Method | URI | Controller Action | Route Name | Purpose |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/admin/dashboard` | `Admin\DashboardController@index` | `admin.dashboard` | Key KPIs, revenue, birthdays count |
| `GET` | `/admin/users` | `Admin\UserController@index` | `admin.users.index` | Filtered list of users (SP & Shops) |
| `GET` | `/admin/users/create` | `Admin\UserController@create` | `admin.users.create` | User creation form |
| `POST` | `/admin/users` | `Admin\UserController@store` | `admin.users.store` | Stores user & creates shop if owner |
| `GET` | `/admin/users/{id}/edit` | `Admin\UserController@edit` | `admin.users.edit` | User editing form |
| `PUT` | `/admin/users/{id}` | `Admin\UserController@update` | `admin.users.update` | Updates user details, role & shop |
| `DELETE` | `/admin/users/{id}` | `Admin\UserController@destroy` | `admin.users.destroy` | Deletes user and associated shop |
| `GET` | `/admin/salespersons` | `Admin\UserController@salespersons` | `admin.salespersons.index` | Filter view for salespersons |
| `GET` | `/admin/salespersons/{id}/details` | `Admin\UserController@salespersonDetails`| `admin.salespersons.details`| SP performance, MTD revenue, visits |
| `GET` | `/admin/salespersons/top` | `Admin\UserController@topSalespersons` | `admin.salespersons.top` | Top-performing salespersons view |
| `GET` | `/admin/bits` | `Admin\BitController@index` | `admin.bits.index` | Territory list with shop counts |
| `GET` | `/admin/bits/create` | `Admin\BitController@create` | `admin.bits.create` | Form to define new territory bit |
| `POST` | `/admin/bits` | `Admin\BitController@store` | `admin.bits.store` | Saves bit with JSON pincodes |
| `GET` | `/admin/bits/{id}/edit` | `Admin\BitController@edit` | `admin.bits.edit` | Edit bit definition |
| `PUT` | `/admin/bits/{id}` | `Admin\BitController@update` | `admin.bits.update` | Updates bit pincodes & status |
| `DELETE` | `/admin/bits/{id}` | `Admin\BitController@destroy` | `admin.bits.destroy` | Deletes bit (protected if shops exist)|
| `GET` | `/admin/bits/{id}/performance` | `Admin\BitController@performance` | `admin.bits.performance` | Territory revenue and shop breakdown |
| `GET` | `/admin/bits/{id}/shops` | `Admin\BitController@shops` | `admin.bits.shops` | JSON endpoint of shops in bit |
| `GET` | `/admin/products` | `Admin\ProductController@index` | `admin.products.index` | Product catalog listing |
| `GET` | `/admin/products/create` | `Admin\ProductController@create` | `admin.products.create` | Product creation form |
| `POST` | `/admin/products` | `Admin\ProductController@store` | `admin.products.store` | Stores product & uploads primary image |
| `GET` | `/admin/products/{id}/edit` | `Admin\ProductController@edit` | `admin.products.edit` | Edit product form |
| `PUT` | `/admin/products/{id}` | `Admin\ProductController@update` | `admin.products.update` | Updates product & handles images |
| `DELETE` | `/admin/products/{id}` | `Admin\ProductController@destroy` | `admin.products.destroy` | Deletes product & image disk files |
| `POST` | `/admin/products/bulk-destroy` | `Admin\ProductController@bulkDestroy` | `admin.products.bulk-destroy` | Bulk deletion of selected products |
| `GET` | `/admin/products/stock` | `Admin\ProductController@stock` | `admin.products.stock` | Quick stock inventory adjust view |
| `GET` | `/admin/products/top` | `Admin\ProductController@top` | `admin.products.top` | Top-selling products ranking |
| `GET` | `/admin/categories` | `Admin\CategoryController@index` | `admin.categories.index` | Category management list & stats |
| `POST` | `/admin/categories` | `Admin\CategoryController@store` | `admin.categories.store` | Stores new category & generates slug |
| `PUT` | `/admin/categories/{id}` | `Admin\CategoryController@update` | `admin.categories.update` | Updates category & cascade-updates products |
| `DELETE` | `/admin/categories/{id}` | `Admin\CategoryController@destroy` | `admin.categories.destroy` | Deletes category (guarded if products exist) |
| `POST` | `/admin/categories/{id}/toggle-status` | `Admin\CategoryController@toggleStatus` | `admin.categories.toggle-status` | Toggles category active/inactive status |
| `GET` | `/admin/orders` | `Admin\OrderController@index` | `admin.orders.index` | Orders listing with date/status filter |
| `GET` | `/admin/orders/consolidation` | `Admin\OrderController@consolidation` | `admin.orders.consolidation` | Consolidated batch warehouse report |
| `GET` | `/admin/orders/{id}` | `Admin\OrderController@show` | `admin.orders.show` | Order overview & items |
| `GET` | `/admin/orders/{id}/details` | `Admin\OrderController@details` | `admin.orders.details` | Detailed order timeline & data |
| `GET` | `/admin/orders/{id}/update-status`| `Admin\OrderController@updateStatusForm` | `admin.orders.update-status` | Order status modal/form |
| `POST` | `/admin/orders/{id}/status` | `Admin\OrderController@updateStatus` | `admin.orders.status.update` | Updates status & manages stock deltas |
| `DELETE` | `/admin/orders/{id}` | `Admin\OrderController@destroy` | `admin.orders.destroy` | Deletes order record |
| `GET` | `/admin/offers` | `Admin\OfferController@index` | `admin.offers.index` | List promotional discounts |
| `GET` | `/admin/offers/create` | `Admin\OfferController@create` | `admin.offers.create` | New offer creation form |
| `POST` | `/admin/offers` | `Admin\OfferController@store` | `admin.offers.store` | Stores percentage/fixed offer |
| `GET` | `/admin/offers/{id}` | `Admin\OfferController@show` | `admin.offers.show` | View offer specifics |
| `GET` | `/admin/offers/{id}/edit` | `Admin\OfferController@edit` | `admin.offers.edit` | Edit offer form |
| `PUT` | `/admin/offers/{id}` | `Admin\OfferController@update` | `admin.offers.update` | Updates offer validity & discount |
| `DELETE` | `/admin/offers/{id}` | `Admin\OfferController@destroy` | `admin.offers.destroy` | Removes offer |
| `GET` | `/admin/reports` | `Admin\ReportController@index` | `admin.reports.index` | Executive analytics dashboard |
| `GET` | `/admin/reports/visit` | `Admin\ReportController@visitReports` | `admin.reports.visit` | Daily field visits & no-order logs |
| `GET` | `/admin/shops/analysis` | `Admin\ReportController@shopAnalysis` | `admin.shops.analysis` | Deep analytics for individual shops |
| `GET` | `/admin/shops/top` | `Admin\ReportController@topShops` | `admin.shops.top` | Top shops by revenue / volume |
| `GET` | `/admin/birthdays` | `Admin\BirthdayController@index` | `admin.birthdays.index` | Today's & next 7 days' birthdays |
| `GET` | `/admin/settings` | `Admin\SettingsController@index` | `admin.settings.index` | Global system settings & work hours |
| `POST` | `/admin/settings` | `Admin\SettingsController@update` | `admin.settings.update` | Saves company data, GSTIN, hours |

---

### 7.3 Salesperson Routes (`prefix: salesperson`, `middleware: [auth, salesperson]`)

| HTTP Method | URI | Controller Action | Route Name | Working Hours Enforced? |
| :--- | :--- | :--- | :--- | :---: |
| `GET` | `/salesperson/dashboard` | `Salesperson\DashboardController@index` | `salesperson.dashboard` | No |
| `GET` | `/salesperson/sales` | `Salesperson\DashboardController@sales` | `salesperson.sales` | No |
| `GET` | `/salesperson/bits/select` | `Salesperson\ProfileController@selectBit` | `salesperson.bits.select` | No |
| `POST` | `/salesperson/bits/select` | `Salesperson\ProfileController@updateBit` | `salesperson.bits.update` | No |
| `GET` | `/salesperson/shops` | `Salesperson\ShopController@index` | `salesperson.shops.index` | No |
| `POST` | `/salesperson/shops` | `Salesperson\ShopController@store` | `salesperson.shops.store` | No |
| `GET` | `/salesperson/shops/select` | `Salesperson\ShopController@select` | `salesperson.shops.select` | No |
| `GET` | `/salesperson/shops/{id}` | `Salesperson\ShopController@show` | `salesperson.shops.show` | No |
| `GET` | `/salesperson/products` | `Salesperson\ProductController@index` | `salesperson.products.index` | No |
| `GET` | `/salesperson/orders/create` | `Salesperson\OrderController@create` | `salesperson.orders.create` | No |
| `POST` | `/salesperson/orders` | `Salesperson\OrderController@store` | `salesperson.orders.store` | **YES** |
| `GET` | `/salesperson/orders/{id}/review` | `Salesperson\OrderController@review` | `salesperson.orders.review` | **YES** |
| `GET` | `/salesperson/orders/{id}/invoice` | `Salesperson\OrderController@invoice` | `salesperson.orders.invoice` | No |
| `POST` | `/salesperson/orders/{id}/cancel` | `Salesperson\OrderController@cancel` | `salesperson.orders.cancel` | No |
| `GET` | `/salesperson/visits` | `Salesperson\VisitController@index` | `salesperson.visits.index` | No |
| `POST` | `/salesperson/visits` | `Salesperson\VisitController@store` | `salesperson.visits.store` | **YES** |
| `POST` | `/salesperson/visits/{id}/no-order`| `Salesperson\VisitController@markAsNoOrder`| `salesperson.visits.no-order`| **YES** |
| `GET` | `/salesperson/profile` | `Salesperson\ProfileController@index` | `salesperson.profile.index` | No |

---

### 7.4 Shop Owner Routes (`prefix: shop-owner`, `middleware: [auth, shop-owner]`)

| HTTP Method | URI | Controller Action | Route Name | Purpose |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/shop-owner/dashboard` | `ShopOwner\DashboardController@index` | `shop-owner.dashboard` | Shop stats, recent activities |
| `GET` | `/shop-owner/products` | `ShopOwner\ProductController@index` | `shop-owner.products.index` | Product catalog for shopping |
| `GET` | `/shop-owner/cart` | `ShopOwner\CartController@index` | `shop-owner.cart.index` | Interactive shopping cart |
| `GET` | `/shop-owner/checkout` | `ShopOwner\CheckoutController@index` | `shop-owner.checkout.index`| Checkout review & order confirmation |
| `POST` | `/shop-owner/checkout` | `ShopOwner\CheckoutController@store` | `shop-owner.checkout.store`| Places order, decrements stock |
| `GET` | `/shop-owner/orders` | `ShopOwner\OrderController@index` | `shop-owner.orders.index` | Complete history of shop orders |
| `GET` | `/shop-owner/orders/{id}` | `ShopOwner\OrderController@show` | `shop-owner.orders.show` | Order tracking & item details |
| `GET` | `/shop-owner/orders/{id}/details` | `ShopOwner\OrderController@details` | `shop-owner.orders.details`| Granular fulfillment timeline |
| `POST` | `/shop-owner/orders/{id}/cancel` | `ShopOwner\OrderController@cancel` | `shop-owner.orders.cancel` | Restores stock & cancels order |
| `GET` | `/shop-owner/invoices` | `ShopOwner\OrderController@invoices` | `shop-owner.invoices.index`| Delivered orders invoice archive |
| `GET` | `/shop-owner/invoices/{id}` | `ShopOwner\OrderController@invoice` | `shop-owner.invoices.show` | Printable tax invoice format |
| `GET` | `/shop-owner/wallet` | `ShopOwner\WalletController@index` | `shop-owner.wallet.index` | Wallet balance & credit/debit logs |
| `GET` | `/shop-owner/profile` | `ShopOwner\ProfileController@index` | `shop-owner.profile.index`| Merchant and business profile |
| `GET` | `/shop-owner/profile/edit`| `ShopOwner\ProfileController@edit` | `shop-owner.profile.edit` | Profile modification form |
| `PUT` | `/shop-owner/profile` | `ShopOwner\ProfileController@update` | `shop-owner.profile.update`| Updates owner & shop details |
| `GET` | `/shop-owner/referral` | `ShopOwner\ProfileController@referral` | `shop-owner.referral.index`| Referral link & bonus rewards |

---

## 8. End-to-End Business Workflows

### 8.1 Territory & Bit Management
- Geographic market beats are defined in the `bits` table by `name`, unique `code`, and an array of 6-digit postal `pincodes` (stored as JSON).
- Each retail `Shop` is assigned to exactly one `Bit` (`shops.bit_id`).
- Each `Salesperson` selects their active operational territory (`users.bit_id`).
- **Dynamic Bit Progress**: The `Bit` model calculates market coverage in real time:
  ```php
  $totalShops = $bit->shops()->count();
  $visitedShops = Visit::whereIn('shop_id', $shopIds)->whereDate('visit_date', today())->distinct('shop_id')->count();
  $percentage = ($totalShops > 0) ? round(($visitedShops / $totalShops) * 100) : 0;
  ```
- **Deletion Safety**: A bit cannot be deleted while shops remain assigned to it (`BitController@destroy` throws an error prompting the admin to reposition shops first).

### 8.2 Working Hours Enforcement Engine
- The system enforces working hours for field sales personnel via `CheckWorkingHours`.
- Working hours can be customized per salesperson (`users.work_start_time`, `users.work_end_time`) or globally via `Setting` (`salesperson_work_start`, `salesperson_work_end`).
- Outside these hours, sales reps are in **"View-Only Mode"**: they can browse products and view customer history, but cannot submit new orders or record visits.

### 8.3 Salesperson Field Visits & No-Order Tracking
- When a salesperson logs into `/salesperson/visits`:
  - If no active bit is selected, they are redirected to `/salesperson/bits/select`.
  - The system loads all shops in the active bit and attaches today's visit status (`pending`, `ordered`, or `no_order`).
- **Marking No-Order**:
  - If a shop does not purchase, the salesperson records a visit with a specific `no_order_reason`:
    - `shop_closed`
    - `owner_not_available`
    - `stock_sufficient`
    - `payment_issue`
    - `other`
  - Notes are optionally captured.
  - This updates or creates a row in `visits` with `status = 'no_order'`.
- **Visit with Order**:
  - When an order is placed through `Salesperson\OrderController@store`, the system automatically updates today's visit to `status = 'ordered'` and sets `order_id`.

### 8.4 Order Lifecycle & Real-Time Stock Engine
The order lifecycle strictly maintains transactional inventory integrity:

```
[Customer or Salesperson Submits Order]
  │
  ├─> DB::beginTransaction()
  ├─> For each item:
  │     ├─> Verify Product is active
  │     ├─> Check $product->stock >= $quantity (Throw Exception if insufficient)
  │     ├─> $product->decrement('stock', $quantity)
  │     └─> Create OrderItem (price, quantity, subtotal)
  ├─> Create Order (total_amount, status = 'pending', payment_status = 'pending')
  ├─> If Salesperson: Update/Create Visit (status = 'ordered', order_id)
  └─> DB::commit()
```

#### Order Status Pipeline:
1. `pending` -> Order received, inventory reserved.
2. `confirmed` / `processing` -> Warehouse verifying and packing goods.
3. `shipped` / `dispatched` -> Order handed over to logistics.
4. `delivered` / `completed` -> Goods handed to merchant. Invoices become available.
5. `cancelled` -> Order cancelled by Admin, Salesperson, or Shop Owner:
   - **Automated Stock Restitution**: Iterates through each `OrderItem` and executes `$product->increment('stock', $item->quantity)`.
   - Payment status is flagged as `failed` or `cancelled`.
   - Associated visit is marked `cancelled`.

### 8.5 Order Consolidation & Warehouse Dispatch
- Accessible at `/admin/orders/consolidation`.
- Aggregates all pending and in-transit orders (`whereNotIn('status', ['cancelled', 'delivered', 'completed'])`).
- Groups order items by `product_id`.
- Generates a consolidated pick-list showing:
  - Product SKU, Name, Category, Unit, Unit Price.
  - Total quantity required across all pending orders.
  - Granular breakdown of which shop and salesperson requested what quantity, along with order IDs.

### 8.6 Wallet, Ledger & Signup Bonus System
- Every shop has a 1-to-1 relationship with a `Wallet`.
- On registration, the merchant is granted a welcome credit (configurable via `Setting`, defaults to ₹100.00).
- Every ledger modification creates an immutable `WalletTransaction`:
  - `type`: `credit` or `debit`
  - `amount`: Transaction value
  - `order_id`: Optional reference to the order
  - `description`: Human-readable description (e.g., *"Welcome signup bonus"*)
- The Shop Owner can inspect their real-time ledger at `/shop-owner/wallet`.

### 8.7 Birthday & CRM Engagement Engine
- Accessible at `/admin/birthdays`.
- Queries `users.dob` (Date of Birth) where `role = 'shop-owner'`:
  - **Today's Birthdays**: Matches `whereMonth('dob', today()->month)->whereDay('dob', today()->day)`.
  - **Upcoming Birthdays**: Scans the next 7 consecutive calendar days to forecast milestone greetings.
- Displayed prominently on the Admin Dashboard as a key CRM metric.

### 8.8 System Settings & Company Profile
- Managed dynamically via key-value records in the `settings` table.
- **Configurable Attributes**:
  - `salesperson_work_start`: Daily start time (e.g., `09:00`)
  - `salesperson_work_end`: Daily end time (e.g., `18:00`)
  - `company_name`: Legal business entity (e.g., `Vamika Enterprise`)
  - `contact_email`: Support correspondence email
  - `phone_number`: Official support phone number
  - `gstin`: 15-character GST Tax Identification Number (injected into all generated invoices)
  - `signup_bonus` / `referral_bonus`: Promotional incentive amounts

---

## 9. Database Schema & Migration Audit (All 32 Migrations)

The database consists of 32 sequential migrations:

| # | Migration Filename | Primary Table Affected | Key Columns & Structural Changes |
| :---: | :--- | :--- | :--- |
| 1 | `0001_01_01_000000_create_users_table.php` | `users`, `password_reset_tokens`, `sessions` | `id`, `name`, `email`, `password`, `remember_token`, timestamps |
| 2 | `0001_01_01_000001_create_cache_table.php` | `cache`, `cache_locks` | `key`, `value`, `expiration` |
| 3 | `0001_01_01_000002_create_jobs_table.php` | `jobs`, `job_batches`, `failed_jobs` | Queue worker runtime tables |
| 4 | `2026_01_13_184605_add_role_to_users_table.php` | `users` | Adds `role` enum: `'admin'`, `'salesperson'`, `'shop-owner'` |
| 5 | `2026_01_21_055940_create_bits_table.php` | `bits` | `id`, `name`, `code`, `pincodes` (json), `status` |
| 6 | `2026_01_21_055950_create_shops_table.php` | `shops` | `id`, `user_id` (fk), `bit_id` (fk), `name`, `address`, `phone`, `status`, `credit_limit`, `current_balance` |
| 7 | `2026_01_21_060000_create_products_table.php` | `products` | `id`, `name`, `sku`, `brand`, `division`, `sub_brand`, `description`, `price`, `status` |
| 8 | `2026_01_21_060014_create_orders_table.php` | `orders` | `id`, `shop_id` (fk), `salesperson_id` (fk, nullable), `order_number`, `total_amount`, `status`, `payment_status`, `payment_method`, `notes` |
| 9 | `2026_01_21_060024_create_order_items_table.php` | `order_items` | `id`, `order_id` (fk), `product_id` (fk), `quantity`, `price`, `subtotal` |
| 10 | `2026_01_21_060032_create_visits_table.php` | `visits` | `id`, `salesperson_id` (fk), `shop_id` (fk), `visit_date`, `notes` |
| 11 | `2026_01_21_060041_create_offers_table.php` | `offers` | `id`, `title`, `description`, `discount_type` ('percentage','fixed'), `discount_value`, `start_date`, `end_date`, `status` |
| 12 | `2026_01_21_060050_create_wallets_table.php` | `wallets` | `id`, `shop_id` (fk, unique), `balance` (decimal 10,2) |
| 13 | `2026_01_21_060058_create_wallet_transactions_table.php` | `wallet_transactions` | `id`, `wallet_id` (fk), `order_id` (fk, nullable), `type` ('credit','debit'), `amount`, `description` |
| 14 | `2026_01_21_060107_create_activity_logs_table.php` | `activity_logs` | `id`, `user_id` (fk), `action`, `description`, `ip_address`, `user_agent` |
| 15 | `2026_01_21_060114_create_settings_table.php` | `settings` | `id`, `key` (unique), `value` (text nullable) |
| 16 | `2026_01_21_143431_create_product_images_table.php` | `product_images` | `id`, `product_id` (fk), `image_path`, `is_primary` (bool), `sort_order` |
| 17 | `2026_01_22_090000_update_users_table_for_salesperson.php` | `users` | Adds `phone`, `avatar`, `status`, `employee_id`, `bit_id` (fk) |
| 18 | `2026_01_22_100000_add_work_hours_to_users_table.php` | `users` | Adds `work_start_time` (time), `work_end_time` (time) |
| 19 | `2026_01_22_120000_add_salesperson_to_shops_table.php` | `shops` | Adds `salesperson_id` (fk nullable to users) |
| 20 | `2026_01_22_140000_add_status_and_reason_to_visits_table.php` | `visits` | Adds `status`, `no_order_reason`, `order_id` (fk nullable to orders) |
| 21 | `2026_01_22_150000_add_extra_fields_to_products_table.php` | `products` | Adds `unit`, `mrp`, `stock` (integer default 0) |
| 22 | `2026_01_23_180538_update_orders_status_enum.php` | `orders` | Expands `status` to ('pending','confirmed','processing','shipped','dispatched','delivered','cancelled') and `payment_status` to ('pending','paid','failed','cancelled') |
| 23 | `2026_01_23_184857_update_users_role_enum.php` | `users` | Re-asserts `role` column enum |
| 24 | `2026_01_24_081700_update_visits_status_enum.php` | `visits` | Sets `status` enum ('pending','ordered','no_order','cancelled') |
| 25 | `2026_01_24_104812_make_bit_id_nullable_in_shops_table.php` | `shops` | Makes `shops.bit_id` nullable (nullOnDelete) |
| 26 | `2026_02_11_141823_fix_all_missing_columns_comprehensive.php` | Multiple tables | Idempotently checks & adds missing columns in `users`, `shops`, `products`, `visits` |
| 27 | `2026_02_12_051610_add_pincodes_to_bits_table.php` | `bits` | Guarantees `pincodes` JSON column on `bits` |
| 28 | `2026_02_12_052445_add_created_by_to_users_table.php` | `users` | Adds `created_by` (foreignId to users, nullOnDelete) |
| 29 | `2026_02_12_055627_add_status_to_bits_table.php` | `bits` | Adds `status` enum ('active','inactive') to `bits` |
| 30 | `2026_03_03_052236_update_users_for_creator_type_and_phone_unique.php` | `users` | Adds `creator_type` enum ('self','admin','salesperson') and unique index on `phone` |
| 31 | `2026_03_03_055229_add_dob_to_users_table.php` | `users` | Adds `dob` (date nullable) for shop owner birthday notifications |
| 32 | `2026_03_20_050540_refactor_products_table.php` | `products` | Drops legacy columns (`brand`, `sub_brand`, `division`) and standardizes on `category` |
| 33 | `2026_03_25_000000_create_categories_table.php` | `categories` | `id`, `name`, `slug` (unique), `description`, `status` ('active','inactive'), `sort_order`, timestamps. Seeds 11 default brand categories |

---

## 10. Eloquent Models & Relationship Mapping

```
 ┌──────────────┐          1:1          ┌──────────────┐
 │     User     │───────────────────────│     Shop     │
 │ (Shop Owner) │                       │              │
 └──────┬───────┘                       └──────┬───────┘
        │                                      │
        │ 1:N (Managed Shops)                  │ 1:1
        ▼                                      ▼
 ┌──────────────┐          1:N          ┌──────────────┐
 │     User     │───────────────────────│    Wallet    │
 │(Salesperson) │                       └──────┬───────┘
 └──────┬───────┘                              │ 1:N
        │                                      ▼
        │ 1:N                           ┌───────────────────────┐
        ▼                               │  WalletTransaction    │
 ┌──────────────┐                       └───────────────────────┘
 │    Visit     │
 └──────┬───────┘
        │
        │ 1:1 (Optional)
        ▼                                      1:N
 ┌──────────────┐ 1:N                   ┌──────────────┐
 │    Order     │───────────────────────│  OrderItem   │
 └──────────────┘                       └──────┬───────┘
        ▲                                      │ N:1
        │ N:1                                  ▼
 ┌──────────────┐                       ┌──────────────┐
 │     Bit      │                       │   Product    │
 └──────────────┘                       └──────┬───────┘
                                               │ 1:N
                                               ▼
                                        ┌──────────────┐
                                        │ ProductImage │
                                        └──────────────┘
```

### Detailed Model Relationships:
- **`User`**:
  - `hasOne(Shop::class, 'user_id')`: Returns owned shop (for shop owners).
  - `belongsTo(Bit::class, 'bit_id')`: Active operational territory (for salespersons).
  - `hasMany(Shop::class, 'salesperson_id')`: Outlets directly managed by this salesperson (`managedShops`).
  - `hasMany(Order::class, 'salesperson_id')`: Orders placed by this salesperson (`salespersonOrders`).
  - `hasMany(Visit::class, 'salesperson_id')`: Field visit logs.
  - `belongsTo(User::class, 'created_by')`: The user who created this account.
- **`Shop`**:
  - `belongsTo(User::class, 'user_id')`: Owner user entity.
  - `belongsTo(Bit::class, 'bit_id')`: Territory location.
  - `belongsTo(User::class, 'salesperson_id')`: Assigned sales representative.
  - `hasMany(Order::class, 'shop_id')`: All commercial orders placed for this outlet.
  - `hasMany(Visit::class, 'shop_id')`: Physical field visits recorded for this outlet.
  - `hasOne(Wallet::class, 'shop_id')`: Digital rebate/bonus wallet.
- **`Order`**:
  - `belongsTo(Shop::class, 'shop_id')`: Purchasing outlet.
  - `belongsTo(User::class, 'salesperson_id')`: Sales rep who booked the order (or null if self-service).
  - `hasMany(OrderItem::class, 'order_id')`: Line items.
  - `hasOne(Visit::class, 'order_id')`: Corresponding field visit record.
  - `hasMany(WalletTransaction::class, 'order_id')`: Related wallet debits/credits.
- **`Category`**:
  - `hasMany(Product::class, 'category', 'slug')`: Linked products.
  - `name`, `slug`, `description`, `status` ('active', 'inactive'), `sort_order`.
- **`Product`**:
  - `belongsTo(Category::class, 'category', 'slug')`: Associated dynamic category (`categoryDetails`).
  - `hasMany(ProductImage::class, 'product_id')`: Gallery images.
  - `hasMany(OrderItem::class, 'product_id')`: Order history lines.
  - **Dynamic Categories**:
    Dynamic table entries with `Product::CATEGORIES` fallback.
- **`Visit`**:
  - `belongsTo(User::class, 'salesperson_id')`
  - `belongsTo(Shop::class, 'shop_id')`
  - `belongsTo(Order::class, 'order_id')`

---

## 11. Seeders & Default Test Accounts

Located in `database/seeders/DatabaseSeeder.php`:

### Seeded Credentials:
| Role | Email Address | Password | Associated Entity |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@vamika.com` | `demo123` | Master Superadmin |
| **Salesperson** | `sales@vamika.com` | `demo123` | Assigned to Mumbai South (`MUM-S`) |
| **Shop Owner** | `shop@vamika.com` | `demo123` | *Healthy Life Pharmacy*, 123 Marine Drive |

### Seeded Territories (Bits):
1. **Mumbai South** (`MUM-S`): Pincodes `["400001", "400002"]`
2. **Delhi Central** (`DEL-C`): Pincodes `["110001", "110002"]`

### Seeded Products:
10 initial products spanning FMCG brand categories (`Durby Special`, `Forolly Premium`, `Million Gold`, `Michi's Choice`, `Oshon Classic`, etc.) with stock levels from 20 to 200 units.

---

## 12. Frontend Architecture & UI/UX Design System

### Layouts Hierarchy:
1. `layouts.guest`: Clean, centered container for Login, Register, Forgot Password.
2. `layouts.admin`: Desktop top navbar + mobile bottom navigation bar (`layouts.partials.bottom-nav.admin`). Full administrative navigation.
3. `layouts.salesperson`: Mobile-first layout optimized for handheld field use. Bottom bar (`layouts.partials.bottom-nav.salesperson`) provides one-tap switching between **Dashboard**, **Shops**, **Visits**, **Orders**, and **Profile**.
4. `layouts.shop-owner`: Consumer/retail-first layout with shopping cart badge counters and mobile bottom navigation (`layouts.partials.bottom-nav.shop-owner`).

### Common UI Components:
- **Toast Notifications**: Automatic flash message listener in base layouts triggering `Toastify` for session `success` or `error`.
- **Destructive Action Confirmations**: SweetAlert2 dialogs intercepting form submissions (e.g., product deletions, order cancellations, logout).
- **Stat Metric Cards**: Consistent CSS styling featuring soft background tints, high-contrast badges, and Lucide icons via Iconify.

---

## 13. Critical Architectural Nuances, Gotchas & Edge Cases

When modifying or extending this codebase, keep these critical details in mind:

1. **Working Hours Gating on Field Operations**:
   - Never remove or bypass `CheckWorkingHours` on order/visit submission routes without considering field policy.
   - If testing orders/visits outside 09:00 - 18:00 IST, either update the global setting at `/admin/settings` or adjust `work_start_time` / `work_end_time` on the test salesperson account.
2. **Double Stock Restitution Risk**:
   - Both `Admin\OrderController@updateStatus` (when setting status to `cancelled`) and `Salesperson\OrderController@cancel` / `ShopOwner\OrderController@cancel` contain stock restoration logic (`$product->increment('stock', $item->quantity)`).
   - Any new cancellation endpoint must ensure stock is not incremented twice.
3. **Product Refactoring Migration**:
   - Migration `2026_03_20_050540_refactor_products_table.php` dropped `brand`, `sub_brand`, and `division`.
   - Always reference `category` (which maps to `Product::CATEGORIES`). Do not re-introduce queries filtering on dropped columns.
4. **Database Connection Quirk with Raw SQL Migrations**:
   - Migration `2026_01_23_180538_update_orders_status_enum.php` executes raw `ALTER TABLE orders MODIFY COLUMN status ENUM(...)`.
   - On SQLite, `ALTER TABLE ... MODIFY COLUMN` is not supported. Running `php artisan migrate:fresh` on a pure SQLite file requires care if running MySQL-specific migrations.
5. **Bit Deletion Constraint**:
   - `BitController@destroy` strictly prohibits deleting bits with assigned shops (`$bit->shops_count > 0`). Reassign shops before deleting.
6. **Unique Phone Numbers**:
   - Migration `2026_03_03_052236_update_users_for_creator_type_and_phone_unique.php` enforces unique phone numbers on the `users` table. Seeders and test factories must supply distinct phone numbers.
7. **Order Consolidation Filter**:
   - The consolidation report includes orders where status is NOT `cancelled`, `delivered`, or `completed`. Newly added intermediate statuses will automatically appear in warehouse pick-lists.

---

## 14. Developer Guidelines & Safe Change Protocol

Before making changes to this codebase, adhere to the following protocol:

### Step 1: Pre-Change Verification
- Confirm which user role(s) the change impacts: Admin, Salesperson, Shop Owner, or Shared.
- Check if the route is protected by `CheckWorkingHours` or role middleware.
- Verify whether inventory stock is impacted (requires `DB::transaction` and stock increment/decrement).

### Step 2: Database Changes
- Always create a new migration rather than editing past migrations:
  ```bash
  php artisan make:migration add_xyz_to_tablename_table
  ```
- Always include an idempotent check (`Schema::hasColumn(...)`) or a corresponding `down()` method.

### Step 3: Controller & Service Structure
- Keep role separation intact. Never put shop-owner business logic into `Admin\` or `Salesperson\` controllers.
- Use explicit database transactions (`DB::transaction(...)` or `DB::beginTransaction()` / `DB::commit()`) for multi-table writes.

### Step 4: Verification Checklist
- [ ] Role authorization verified (User A cannot view User B's invoices or orders).
- [ ] Input validated with appropriate error messages.
- [ ] Stock integrity verified (stock properly decremented on order, incremented on cancel).
- [ ] Blade templates include CSRF token (`@csrf`) for POST/PUT/DELETE forms.
- [ ] Mobile bottom navigation and responsive layout remain intact.
- [ ] No regression in Toastify / SweetAlert2 notifications.
