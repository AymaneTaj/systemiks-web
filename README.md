# Systemiks – Ofseek-style site + Admin

Duplicate of [Ofseek](https://ofseek.com/) design for Systemiks, with an admin dashboard.

## URLs (XAMPP + systemiks.local)

- **Site:** http://systemiks.local  
- **Admin:** http://systemiks.local/admin/  
- **Login:** http://systemiks.local/admin/login.php  

**Default admin:** `admin` / `admin123` — change password after first login (e.g. via a future “Profile” page in admin).

## What’s included

### Front (Ofseek-style)

- **Header:** Fixed, transparent over hero, solid on scroll, entrance animation  
- **Hero:** Full-height yellow block, big “Systemiks” title, scroll-down arrow  
- **Line-reveal headings:** Sections “Our way of approaching…”, “Featured on”, “Our clients”  
- **Rotating words:** One word cycles under the main tagline  
- **Scrolling marquee:** “WEB · SEO · DIGITAL · GROWTH” (outline text)  
- **Ticker carousels:** “Featured on” and “Our clients” (endless scroll)  
- **Services slider:** Four slides (Web, SEO, SEA, Social) with dot navigation  
- **CTA + footer:** Contact and links  

### Admin

- **Login:** `/admin/login.php` (session-based)  
- **Dashboard:** `/admin/` (overview, link to site, placeholder cards)  
- **Logout:** `/admin/logout.php`  

Database: SQLite in `data/systemiks.sqlite` (created on first admin visit). Config in `config/config.php`.

## Structure

```
Systemiks/
├── config/          # config, DB, auth (not web-accessible)
├── data/            # SQLite DB (not web-accessible)
├── public/          # Document root
│   ├── index.php    # Homepage
│   ├── contact.php
│   ├── admin/       # Login, dashboard, logout, admin.css
│   └── assets/
│       ├── css/style.css
│       └── js/main.js
└── README.md
```

## Apache

VHost for `systemiks.local` points `DocumentRoot` to `Systemiks/public`. Restart Apache after changing vhosts.
