<div align="center">

# 🌐 City Online AP Pvt. Ltd.
### Next-Generation Telecom Broadband ISP Portal & 24-Module CMS Administration Platform

[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2%20%7C%208.3%20%7C%208.4-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Laravel 12](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![React 19](https://img.shields.io/badge/React-19.0-61DAFB?style=for-the-badge&logo=react&logoColor=black)](https://react.dev)
[![TypeScript](https://img.shields.io/badge/TypeScript-5.8-3178C6?style=for-the-badge&logo=typescript&logoColor=white)](https://www.typescriptlang.org)
[![Tailwind CSS 4](https://img.shields.io/badge/Tailwind_CSS-4.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![MySQL 8](https://img.shields.io/badge/MySQL-8.0%20%7C%20MariaDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![cPanel Ready](https://img.shields.io/badge/cPanel-Git_Deploy_Ready-FF6C2C?style=for-the-badge&logo=cpanel&logoColor=white)](https://cpanel.net)
[![Tests Passing](https://img.shields.io/badge/Tests-21%20Passed-success?style=for-the-badge&logo=vitest&logoColor=white)](https://vitest.dev)

<p align="center">
  <b>Designed & Engineered by <a href="https://github.com/abhishekaddepalli">Infiniforge Technologies</a></b>
</p>

[Live Production Demo](https://cityonline.infiniforge.app) • [Customer Portal](https://customer.cityonlineap.com/) • [cPanel Deployment](#-cpanel-git-version-control-deployment) • [Admin Dashboard Modules](#-admin-dashboard--cms-modules) • [Installation Wizard](#-7-step-web-installation-wizard)

---

</div>

## 📌 Executive Summary

**City Online AP Pvt. Ltd.** is Kakinada's premier broadband Internet Service Provider (ISP). This project represents a complete, ground-up architectural transformation of the ISP's digital presence into an enterprise-grade, database-driven web application featuring:

1. **Ultra-Fast Public Broadband Portal**: Fully responsive, high-converting telecom experience with instant first paint, responsive WebP photography, bilingual localization (English & Telugu), real CORS network tools, and live lead capture.
2. **24-Module CMS Administration Platform**: A dedicated, role-protected management dashboard powering broadband plans, customer enquiries, support tickets, dynamic homepage sections, SEO, bilingual FAQs, theme tokens, backups, and security audit logs.
3. **7-Step Automated Web Installation Wizard (`/install`)**: A turn-key browser installer that checks server prerequisites, configures database credentials, provisions the Super Admin, seeds published plans and 40+ Kakinada localities, and permanently locks installation for production safety.
4. **cPanel Git Version Control Native**: Built specifically for standard Apache / LiteSpeed cPanel hosting without requiring persistent Node.js or Docker daemons in production. Pre-rendered static assets hydrate seamlessly via client-side React 19.

---

## 🎨 Brand Design System

Built strictly around the official City Online AP brand visual identity:
- **Primary Brand Blue**: `#073B91`
- **Deep Navy Background**: `#05265F`
- **Secondary Blue**: `#1765C0`
- **Brand Action Orange**: `#F36B21`
- **Orange Hover State**: `#E85A12`
- **Clean Background Canvas**: `#F5F8FF` / `#FFFFFF`
- **Typography**: *Plus Jakarta Sans* (English) + *Noto Sans Telugu* (తెలుగు)
- **Geometry**: Modern card-based UI with `12px` to `22px` border radiuses and natural soft shadows.

---

## 🏛️ System Architecture

```mermaid
graph TD
    Client[Web Visitors & Customers] -->|HTTPS / Port 443| Apache[LiteSpeed / Apache Web Server]
    Admin[Admin Staff & Engineers] -->|/admin/login| AuthGuard[Laravel Session Auth & CSRF]
    NewUser[First-Time Setup] -->|/install| WebInstaller[7-Step Installation Wizard]

    subgraph "cPanel Web Hosting Environment"
        Apache -->|Static Routes / Direct Paint| PublicAssets[public/assets & Pre-rendered HTML]
        Apache -->|Fallback & API Requests| PHP[PHP 8.2+ / 8.4+ Front Controller]
        
        PublicAssets -->|Hydrate with 0 Errors| React[React 19 SPA Client]
        React -->|Fetch Live Data| API[/api/v1/init, /plans, /enquiries, /support]
        
        PHP --> API
        AuthGuard --> BladeAdmin[Blade Admin Management Suite 24 Modules]
        
        WebInstaller -->|Run Migrations & Seeders| MySQL[(MySQL 8 / MariaDB Database)]
        API --> MySQL
        BladeAdmin --> MySQL
        WebInstaller -->|Write Lockfile| Lock[storage/installed.lock]
    end

    subgraph "Live External Telecom Endpoints"
        React -->|Direct Pay Bill| Portal[customer.cityonlineap.com]
        React -->|Real Speed Test| CloudflareEdge[Cloudflare CDN Speed Edge]
        React -->|Live IP Lookup| Ipify[api64.ipify.org CORS]
        React -->|Live DNS Query| GoogleDNS[dns.google DoH]
    end
```

---

## 🚀 Key Features

### 1. Public Broadband Experience
- **Hero Presentation**: *"One connection, the whole house."* with responsive high-density WebP family photography, trust indicators (Unlimited Data · No FUP · Symmetric Speeds · 24h Fault Resolution), and primary booking CTAs.
- **Published Broadband Plans**:
  - **Standard**: 60 Mbps · Truly Unlimited · ₹590/month (₹500 + 18% GST).
  - **Premium (★ Most Popular)**: 100 Mbps · Truly Unlimited · ₹767/month (₹650 + 18% GST).
  - **Ultra**: 150 Mbps · Truly Unlimited · ₹1,180/month (₹1,000 + 18% GST).
  - Dynamic pricing and tax calculations backed by database models.
- **Area Coverage Checker**: Live locality search covering 40+ Kakinada neighborhoods (*Sarpavaram, Bhanugudi, Ramanayyapeta, Jagannaickpur, Madhavapatnam, etc.*) with honest feasibility disclaimer.
- **Honest Client-Side Internet Tools** (Zero fake/simulated numbers):
  1. **Internet Speed Test**: Real download, upload, ping, and jitter against Cloudflare CDN edges.
  2. **My IP Address**: Real public IPv4/IPv6 detection with 1-click clipboard copy.
  3. **DNS Lookup**: Live DoH queries for `A`, `AAAA`, `MX`, `TXT`, `NS` records via Google DNS.
  4. **Ping & Latency Test**: Real round-trip network response times.
  5. **Wi-Fi Troubleshooter**: Interactive diagnostic flowchart to resolve home router issues.
  6. **Bandwidth Calculator**: Real transfer time estimates across common file sizes.
- **Bilingual Switcher**: Instant one-click toggle between English and Telugu (తెలుగు) across all UI cards, headers, footers, and FAQ responses.
- **Floating Support Assistant & Sticky Mobile Bar**: Fixed bottom thumb navigation on mobile and expandable FAQ assistant with WhatsApp and phone call deep links.

---

### 🎛️ Admin Dashboard — 24 CMS Modules

Accessed via `/admin` with role-based access control, session timeouts, and audit logging:

| Module | Route | Functionality |
| :--- | :--- | :--- |
| **1. System Dashboard** | `/admin` | Real-time KPI counters (Leads, Tickets, Plans), monthly enquiry chart, and recent activity logs. |
| **2. Broadband Plans** | `/admin/plans` | Create, edit, toggle, and reorder published plans, speeds, prices, GST rates, and "Most Popular" tags. |
| **3. Homepage Sections** | `/admin/sections` | Customize Hero headline, trust badges, benefits, coverage callout, and tool cards directly. |
| **4. Pages & CMS** | `/admin/pages` | Visual content page builder, slugs, meta descriptions, and publish status. |
| **5. Header & Nav** | `/admin/header` | Drag-and-drop navigation menus, Telugu translations, external portal URLs. |
| **6. Footer Manager** | `/admin/footer` | Office address, official phone numbers, WhatsApp link, and legal copyright text. |
| **7. Theme & Colors** | `/admin/theme` | Live customizer for primary blue (`#073B91`), brand orange (`#F36B21`), navy, font, and border radiuses. |
| **8. Centralized Links** | `/admin/links` | Single source of truth for payment portals, customer book links, and phone numbers. |
| **9. Internet Tools** | `/admin/tools` | Enable/disable network tools; override speed-test server with custom LibreSpeed endpoint. |
| **10. Bilingual FAQs** | `/admin/faqs` | Knowledge base manager with English & Telugu questions, answers, and search keywords. |
| **11. Customer Enquiries** | `/admin/enquiries` | Real-time lead capture from website with status tracking (New, Contacted, Converted) and CSV export. |
| **12. Support Tickets** | `/admin/support` | Customer trouble tickets with priority levels (Low, Medium, High, Critical) and resolution logs. |
| **13. Media Library** | `/admin/media` | Upload logos, banners, and network photos with instant URL copying and size optimization. |
| **14. Announcements & Blog**| `/admin/blog` | Publish maintenance notices, fiber cut alerts, network upgrades, and ISP news. |
| **15. SEO & Meta Manager** | `/admin/seo` | Title tags, OpenGraph previews, Twitter cards, sitemap generator, and robots.txt. |
| **16. URL 301 Redirects** | `/admin/seo` | Manage legacy URL redirects with automatic hit counting. |
| **17. Users & Permissions**| `/admin/users` | Multi-user administrator management with Super Admin and Editor roles. |
| **18. Database Backups** | `/admin/backups` | Generate on-demand full database SQL dumps with 1-click download. |
| **19. General Settings** | `/admin/settings` | Company name, email, timezone (`Asia/Kolkata`), currency (`₹`), and portal settings. |
| **20. Web Analytics** | `/admin/analytics`| Insert Google Analytics (GA4), Google Tag Manager, or Meta Pixel without touching code. |
| **21. Security & Logs** | `/admin/security` | IP tracking, CSRF verification status, login attempts, and full administrative audit trail. |
| **22. System Information**| `/admin/system` | Server environment stats, PHP version, database engine, and 1-click cache purge. |
| **23. Language Strings** | `/admin/languages`| Editor for English and Telugu UI strings. |
| **24. Notification Hub** | `/admin/notifications`| Quick alert badge for incoming customer connection enquiries. |

---

### 🧙‍♂️ 7-Step Web Installation Wizard

When the website is deployed to cPanel for the first time, accessing `/install` launches the automated setup wizard:

1. **Step 1: Welcome**: System overview and deployment pre-checks.
2. **Step 2: Server Requirements**: Verifies PHP 8.2+, PDO MySQL, OpenSSL, Mbstring, Tokenizer, XML, Ctype, JSON, Fileinfo, GD, and directory write permissions.
3. **Step 3: Database Configuration**: Interactive MySQL credentials input with live connection test before proceeding.
4. **Step 4: Administrator Account**: Creates the first Super Admin user with strong password hashing (Bcrypt).
5. **Step 5: Company Configuration**: Pre-populates Kakinada office address, GST configuration, phone numbers, and timezone.
6. **Step 6: Complete Installation**: Runs all migrations, seeds broadband plans, 40+ localities, and bilingual FAQs, and writes `storage/installed.lock`.
7. **Step 7: Finished**: Displays security checklist and provides a direct button to the Admin Dashboard.

---

## 📦 cPanel Git Version Control Deployment

Follow these steps to deploy this repository directly to your cPanel hosting account using cPanel's built-in **Git Version Control**:

### Step 1: Create the MySQL Database in cPanel
1. Log in to your cPanel control panel.
2. Navigate to **Databases** → **MySQL® Database Wizard**.
3. Create a database (e.g. `youruser_cityonline`).
4. Create a database user (e.g. `youruser_dbuser`) with a strong password.
5. Assign **ALL PRIVILEGES** to the user for this database.

### Step 2: Clone the Repository via cPanel Git Version Control
1. In cPanel, navigate to the **Files** section and click **Git™ Version Control**.
2. Click the blue **Create** button on the top right.
3. Configure the clone settings:
   - **Clone URL**: `https://github.com/abhishekaddepalli/cityonline-ap-website.git`
   - **Repository Path**: `/home/youruser/cityonline` *(outside `public_html` for maximum security)*
   - **Repository Name**: `cityonline`
4. Click **Create**. cPanel will automatically clone the repository.

### Step 3: Configure Domain Document Root
In cPanel, go to **Domains** (or **Subdomains** / **Addon Domains**):
- Edit the **Document Root** of your domain to point to:
  ```
  /home/youruser/cityonline/public
  ```
> **Alternative (If your host restricts Document Root to `public_html`):**  
> Clone the repo to `/home/youruser/repositories/cityonline`. The included `.cpanel.yml` file will automatically rsync the pre-rendered `public/` directory into `public_html` upon clicking **Deploy HEAD Commit** in cPanel.

### Step 4: Run the Web Installer
1. Open your browser and navigate to:
   ```
   https://yourdomain.com/install
   ```
2. Follow the 7-step wizard:
   - Test your MySQL connection from Step 1.
   - Set up your administrator credentials.
   - Click **Run Complete Installation**.
3. The wizard will migrate your database, seed the initial data, and automatically lock the installer.

### Step 5: 1-Click Future Updates
Whenever you push changes to this GitHub repository:
1. Go to cPanel → **Git Version Control**.
2. Click **Manage** next to `cityonline`.
3. Switch to the **Pull or Deploy** tab.
4. Click **Update from Remote** to pull latest changes instantly.

---

## 💻 Local Development Setup

If you want to run or test the project locally on your machine:

### Prerequisites
- PHP 8.2 or newer (`php -v`)
- Composer (`composer -v`)
- Node.js 20+ and npm (`node -v`)

### Quickstart
```bash
# 1. Clone the repository
git clone https://github.com/abhishekaddepalli/cityonline-ap-website.git
cd cityonline-ap-website

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy environment file
cp .env.example .env
php artisan key:generate

# 5. Run local tests
npm test

# 6. Start the development server
php artisan serve --port=8000
```
Visit `http://localhost:8000` to view the public website and `http://localhost:8000/admin` to access the administration panel.

---

## 🧪 Automated Testing

The project includes unit and integration test suites covering URL integrity, CORS internet tool endpoints, routing, and component boundaries:

```bash
npm test
```

```
 ✓ src/lib/links.test.ts (4 tests)
 ✓ src/lib/net.test.ts (4 tests)
 ✓ src/test/app-routing.test.tsx (13 tests)

 Test Files  3 passed (3)
      Tests  21 passed (21)
```

---

## 🔒 Security Best Practices

- **Zero Plaintext Secrets**: Passwords hashed using Bcrypt with 12 rounds. Database credentials stored exclusively in `.env` outside the web root.
- **CSRF & Rate Limiting**: All form submissions, enquiry forms, and admin mutation endpoints are guarded with CSRF protection and IP-based rate limiting.
- **Installer Safety Lock**: `storage/installed.lock` blocks all subsequent access to `/install` after setup.
- **Directory Protection**: Root and public `.htaccess` files enforce modern security headers (`X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`) and block direct access to hidden files (`.env`, `.git`).

---

## 👨‍💻 Credits & License

- **Client**: [City Online AP Pvt. Ltd.](https://cityonlineap.com) — Temple Street, Kakinada, Andhra Pradesh, India.
- **Architecture & Development**: [Infiniforge Technologies](https://github.com/abhishekaddepalli)
- **Design System**: Plus Jakarta Sans, Tailwind CSS, Brand Colors (`#073B91`, `#F36B21`, `#05265F`).
- **Copyright**: © 2026 City Online AP Pvt. Ltd. & Infiniforge Technologies. All rights reserved.
