# City Online AP — cPanel Production Deployment Guide
**Complete CMS Website + Administration Dashboard + Web Installation Wizard**

This guide provides step-by-step instructions for deploying the **City Online AP** website and management portal to any standard cPanel hosting environment (Apache / LiteSpeed, PHP 8.2+ or 8.4+, MySQL 8.0 or MariaDB 10.5+).

No continuous Node.js runtime or Docker container is required in production. All frontend assets are precompiled and served directly via the optimized PHP front controller.

---

## 1. Prerequisites Checklist

Before beginning the installation, ensure your cPanel account has:
- **PHP Version**: PHP 8.2 or newer (PHP 8.3 / 8.4 recommended).
- **PHP Extensions**:
  - `pdo_mysql` (MySQL database connectivity)
  - `openssl` (Secure cryptographic sessions and encryption)
  - `mbstring` (Multilingual Unicode support, English & Telugu copy)
  - `fileinfo` (MIME-type detection for image uploads)
  - `gd` or `imagick` (Dynamic thumbnail & plan graphic generation)
  - `curl` (Network speed testing & external API calls)
  - `xml` & `ctype` & `json` & `tokenizer` (Standard Laravel requirements)
- **MultiPHP INI Recommended Settings**:
  - `memory_limit` = `256M` (or `512M`)
  - `upload_max_filesize` = `32M`
  - `post_max_size` = `32M`
  - `max_execution_time` = `120`

---

## 2. Directory Structure & File Mapping

To keep credentials, database models, and application logic completely secure and inaccessible to web visitors, use the industry-standard two-folder architecture:

```
/home/yourusername/
├── cityonline_app/               <-- APPLICATION ROOT (Outside public_html)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── artisan
│   └── composer.json
│
└── public_html/                  <-- DOCUMENT ROOT (Web accessible)
    ├── assets/                   <-- Precompiled CSS, JS, responsive images
    ├── storage/                  <-- Symlink to storage/app/public
    ├── uploads/                  <-- Uploaded media & logos
    ├── .htaccess                 <-- Apache rewrite rules & security headers
    ├── favicon.png               <-- City Online AP brand favicon
    ├── index.php                 <-- PHP Front Controller
    └── robots.txt                <-- SEO directives & sitemap location
```

> **Alternative Option (Single Directory Upload):**  
> If your cPanel host allows pointing the primary domain root directly to a custom directory, you can upload the entire project to `/home/yourusername/cityonline_app/` and in cPanel **Domains / Document Root**, set the root to `/home/yourusername/cityonline_app/public/`.  
> If forced to upload directly inside `public_html/`, the included root `.htaccess` automatically redirects all traffic safely into `/public/` and blocks direct access to `.env` and application files.

---

## 3. Step-by-Step Deployment Instructions

### Step 1: Create the MySQL Database
1. Log into your cPanel dashboard.
2. Under the **Databases** section, click **MySQL® Database Wizard**.
3. **Step 1: Create a Database**:
   - Enter a name (e.g. `capp_cityonline`).
   - Click **Next Step**.
4. **Step 2: Create Database Users**:
   - Enter a username (e.g. `capp_dbuser`).
   - Generate a strong password using the Password Generator and copy it safely.
   - Click **Create User**.
5. **Step 3: Add User to Database**:
   - Check the box **ALL PRIVILEGES**.
   - Click **Make Changes**.

---

### Step 2: Upload Application Files
1. In cPanel, open **File Manager**.
2. Create a folder in your home root `/home/yourusername/` named `cityonline_app`.
3. Upload and extract the application archive:
   - Extract `app/`, `bootstrap/`, `config/`, `database/`, `resources/`, `routes/`, `storage/`, `vendor/`, `artisan`, and `composer.json` into `/home/yourusername/cityonline_app/`.
4. Upload all files from the `public/` directory into `/home/yourusername/public_html/`:
   - `public/assets/`
   - `public/favicon.png`
   - `public/robots.txt`
   - `public/index.php`
   - `public/.htaccess`

---

### Step 3: Set File & Directory Permissions
In cPanel **File Manager** (or via **cPanel Terminal**):
Ensure the web server has write access to `storage` and `bootstrap/cache`:

```bash
chmod -R 775 /home/yourusername/cityonline_app/storage
chmod -R 775 /home/yourusername/cityonline_app/bootstrap/cache
```

---

### Step 4: Create the Storage Symlink
To serve uploaded logos, brochures, and plan icons publicly:
- Open **cPanel Terminal** and run:
  ```bash
  cd /home/yourusername/cityonline_app
  php artisan storage:link
  ```
- *If Terminal is not enabled on your cPanel account:*
  Create a temporary Cron Job in cPanel (**Cron Jobs** section):
  - Command: `ln -s /home/yourusername/cityonline_app/storage/app/public /home/yourusername/public_html/storage`
  - Run it once, verify the symlink appears in `public_html/storage`, then delete the Cron Job.

---

### Step 5: Run the 7-Step Web Installation Wizard
1. Open your browser and navigate to:  
   `https://yourdomain.com/install`
2. **Step 1 — Welcome**:  
   Review the City Online AP installation overview and click **Start Installation**.
3. **Step 2 — Server Requirements**:  
   The installer verifies PHP version, required PHP extensions (PDO, OpenSSL, Mbstring, Fileinfo, GD), and writable directory permissions. All green checks will display.
4. **Step 3 — Database Configuration**:  
   Enter the MySQL credentials created in Step 1:
   - Database Host: `127.0.0.1` (or `localhost`)
   - Database Port: `3306`
   - Database Name: `capp_cityonline`
   - Database Username: `capp_dbuser`
   - Database Password: `<your-password>`
   - Click **Test Database Connection** to confirm connectivity.
5. **Step 4 — Administrator Account**:  
   Create the primary administrative login:
   - Full Name: `City Online AP Admin`
   - Email: `admin@cityonlineap.com`
   - Username: `admin`
   - Password: `<secure-password>`
6. **Step 5 — Company Information**:  
   Review and customize company contact info:
   - Company Name: `City Online AP Pvt. Ltd.`
   - Primary Phone: `+91 97009 28928`
   - Support Helpline: `+91 8844 6668999`
   - Office Address: `#34-1-6, Temple Street, Opp. Joyalukas Jewellers, Kakinada 533001`
   - Currency: `INR (₹)`
   - Timezone: `Asia/Kolkata`
7. **Step 6 — Installation Process**:  
   Click **Run Complete Installation**. The installer will:
   - Run all database schema migrations.
   - Seed default published broadband plans (Standard 60 Mbps, Premium 100 Mbps, Ultra 150 Mbps).
   - Seed 40+ Kakinada localities.
   - Seed bilingual English/Telugu FAQs and homepage sections.
   - Create the administrator account with `Super Admin` role.
   - Permanently write `storage/installed.lock` to secure the installer.
8. **Step 7 — Finish**:  
   The installation is complete! Click **Go to Admin Dashboard** or browse to `https://yourdomain.com/admin/login`.

---

## 4. Admin Dashboard Module Overview

Log into the administration panel at `https://yourdomain.com/admin/login` to access all 24 management modules:

| Module | URL Path | Capabilities |
| :--- | :--- | :--- |
| **Dashboard** | `/admin` | Live metrics, enquiry trends, system status, quick stats |
| **Broadband Plans** | `/admin/plans` | Create, edit, and reorder plans (speed, price, GST, popular tag) |
| **Homepage Sections** | `/admin/sections` | Edit Hero headline, benefits cards, coverage, and tools layout |
| **Pages & CMS** | `/admin/pages` | Custom content pages, templates, block builder, slugs |
| **Navigation Menus** | `/admin/navigation` | Header & footer links, Telugu translated menu titles |
| **Footer & Contacts** | `/admin/footer` | Office address, phone numbers, WhatsApp link, copyright |
| **Theme & Branding** | `/admin/theme` | Live brand colors (`#073B91`, `#F36B21`), font, border radius |
| **Portal & Links** | `/admin/links` | Centralized customer portal destinations (`customer.cityonlineap.com`) |
| **Internet Tools** | `/admin/tools` | Toggle speed test, IP lookup, ping test, DNS tool, custom LibreSpeed |
| **Bilingual FAQs** | `/admin/faqs` | Manage English & Telugu FAQ questions, answers & search keywords |
| **Customer Enquiries** | `/admin/enquiries` | Manage new connection leads, status tracking (New/Contacted/Closed), CSV export |
| **Support Tickets** | `/admin/support` | Manage customer trouble tickets, priority levels, assignment |
| **Media Library** | `/admin/media` | Upload banners, network photos, logos; copy URLs in 1 click |
| **Announcements** | `/admin/blog` | Publish maintenance notices, network upgrades, company news |
| **SEO & Redirects** | `/admin/seo` | Title tags, meta descriptions, Open Graph, 301 redirects, robots.txt |
| **Admin Users & Roles** | `/admin/users` | Manage staff accounts, granular role permissions (Admin, Editor, Support) |
| **Database Backups** | `/admin/backups` | Generate on-demand database snapshots and download for safekeeping |
| **General Settings** | `/admin/settings` | Company name, logo, favicon, timezone, GST rate, canonical base URL |
| **Analytics & Tags** | `/admin/analytics` | Embed Google Analytics (GA4), GTM, Meta Pixel without touching code |
| **Security & Logs** | `/admin/security` | Audit logs of all administrative actions, IP logs, CSRF protection |
| **System Info & Cache** | `/admin/system` | PHP version, database engine, 1-click cache purge (`optimize:clear`) |
| **Languages** | `/admin/languages` | Bilingual English & Telugu translation strings editor |

---

## 5. Security & Maintenance Best Practices

1. **Keep `/install` Locked**:  
   The installation wizard automatically writes `storage/installed.lock`. Once this file exists, `/install` is completely inaccessible and immediately redirects to `/admin`.
2. **Scheduled Backups**:  
   Use `/admin/backups` periodically or schedule a cPanel Cron Job to back up the MySQL database weekly.
3. **SSL Certificate**:  
   Ensure **cPanel AutoSSL** or **Let's Encrypt** is active on your domain so all customer enquiries and admin logins are encrypted over HTTPS.
4. **Cache Clearing**:  
   Whenever updating settings or theme colors, you can clear the application cache anytime with a single click at **Admin -> System Information -> Clear Application Cache**.
