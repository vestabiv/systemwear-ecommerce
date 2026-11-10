# SYSTEM:WEAR — E-commerce Web Application

A university coursework project demonstrating a PHP-based clothing store with a relational MySQL database, shopping cart, user authentication, and an administration panel. The website has a techwear / futuristic fashion theme and a Lithuanian-language interface.

> **Project type:** Educational prototype. This is not a production-ready store; no real payment processing is implemented.

## Features

- Product catalogue with categories, product details and attributes (size, colour and material)
- User registration, login, logout, password change and basic password recovery
- Session-based shopping cart: add, update and remove items
- Checkout for registered customers and guests; orders saved to MySQL
- First-order 10% discount for registered users
- Admin-only pages for product and category management and order overview/status updates
- Prepared SQL database structure and sample product data

## Tech stack

**Frontend:** HTML, CSS, JavaScript  
**Backend:** PHP (PDO, sessions)  
**Database:** MySQL / MariaDB  
**Local development:** XAMPP, phpMyAdmin

## Project structure

```text
systemwear-ecommerce/
├── SYSTEMWEAR/
│   ├── admin/              # Administrative pages
│   ├── auth/               # Registration and login
│   ├── cart/               # Cart and checkout
│   ├── config/db.php       # Database connection
│   ├── public/images/      # Product images
│   ├── index.php           # Storefront
│   └── product.php         # Product details
├── database/
│   └── cyberpunk_parduotuve.sql
├── screenshots/            # Add screenshots here
└── README.md
```

## Run locally (XAMPP)

1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache** and **MySQL**.
2. Place the `SYSTEMWEAR` folder in `C:\xampp\htdocs\`.
3. Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
4. Import `database/cyberpunk_parduotuve.sql` using the **Import** tab. The script creates the `cyberpunk_parduotuve` database and loads sample product catalogue data. Use a local development database, not a database containing important data.
5. Check `SYSTEMWEAR/config/db.php`: by default it expects database `cyberpunk_parduotuve` on `localhost`, username `root`, with a blank password (a common **local-only** XAMPP setup). Update these details for your machine, and do not commit real database credentials.
6. Visit **http://localhost/SYSTEMWEAR/**.
7. Create a user account using the registration page to try login and checkout.

**Admin access:** No pre-created admin user is included in the public SQL export. For a **local test database only**, register a new account, then change its `role` from `user` to `admin` in phpMyAdmin's `vartotojai` table. Log out and back in before visiting `/SYSTEMWEAR/admin/`. Do not assign admin privileges this way in a deployed application.

**Email features:** Password recovery and order notifications use PHP's `mail()` function. Email delivery may not work in a default XAMPP installation without mail server configuration.

## Screenshots

Screenshots can be added to the `screenshots/` directory after the project is running locally.

<!-- Example: ![Storefront](screenshots/HomePage.png) -->

## Database

The SQL script defines the following tables: `vartotojai` (users), `kategorijos` (categories), `prekes` (products), `atributai` (attributes), `prekes_atributai` (product attributes), `uzsakymai` (orders) and `uzsakymo_prekes` (order items). Foreign keys connect products to categories, orders to users and order items to products.

The public SQL export retains **sample product/category data**, but excludes original test users, order records, addresses, emails and stored password hashes.

## Notes and limitations

- Educational project developed for a university course, not designed for production deployment.
- Checkout records an order with pending-payment status; there is **no actual payment integration**.
- Password-reset emails are basic demo functionality, not a production-grade password-reset-token flow.
- Additional security review, validation and testing would be required before deployment.
- Product images are included for coursework demonstration; check image rights before commercial reuse.

## Author

Vesta Biveinytė — Multimedia and Computer Design student.
