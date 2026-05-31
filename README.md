<<<<<<< HEAD
# WPoets Full Stack Developer Test

Production-ready PHP 8 and MySQL implementation of the WPoets Full Stack Developer Test. The project includes a responsive frontend that mirrors the supplied desktop and mobile references, plus an admin panel for managing categories and slides with full CRUD operations.

## Project Overview

The application renders a three-column showcase on desktop and an accordion-driven slider on mobile. All frontend content is pulled from MySQL and managed through a lightweight MVC-style PHP structure with secure form handling, file uploads, and reusable repositories.

## Features

- Responsive desktop tabs and mobile accordion that match the supplied visual references
- Synchronized content and image sliders powered by Swiper and jQuery
- Category CRUD with icon path support and optional icon uploads
- Slide CRUD with image uploads, sort order, status toggles, and validation
- PDO-based database layer with prepared statements
- CSRF protection, escaped output, and upload restrictions
- Bootstrap 5 powered admin UI
- Seed data mapped to the provided local design assets

## Tech Stack

- PHP 8+
- MySQL 8+ or MariaDB equivalent
- HTML5
- CSS3
- Bootstrap 5
- jQuery 3.7
- Swiper 11

## Folder Structure

```txt
.
├── admin/
│   ├── categories.php
│   ├── index.php
│   └── slides.php
├── app/
│   ├── Controllers/
│   ├── Repositories/
│   ├── Services/
│   ├── Support/
│   └── Views/
├── assets/
│   ├── css/
│   ├── images/
│   └── js/
├── config/
├── files/
├── includes/
├── sql/
│   └── wpoets_full_stack.sql
├── uploads/
│   ├── categories/
│   └── slides/
├── Answers to technical questions.md
├── index.php
├── README.md
└── TESTING_CHECKLIST.md
```

## Installation

1. Copy the project into your PHP web root, for example `htdocs/wpoets-full-stack`.
2. Ensure PHP 8+ and MySQL are running.
3. Create a virtual host if desired, or access the project through your local Apache document root.
4. Import the SQL file:

```sql
SOURCE path/to/sql/wpoets_full_stack.sql;
```

5. Update database credentials in [config/database.php](/d:/project/full-stack-test-master/full-stack-test-master/config/database.php).
6. Ensure `uploads/categories` and `uploads/slides` are writable by the web server.
7. Open the frontend:

```txt
http://localhost/<your-project-folder>/
```

8. Open the admin panel:

```txt
http://localhost/<your-project-folder>/admin/
```

## Database Setup

The provided SQL file creates:

- `wpoets_full_stack.categories`
- `wpoets_full_stack.slides`

It also includes:

- foreign key constraints
- indexes for status and slider ordering
- sample categories and slides using the bundled design assets

## Local Environment Notes

- Frontend libraries are loaded via CDN, so internet access is required for Bootstrap, jQuery, Swiper, and Google Fonts.
- Uploaded assets are stored under `uploads/`.
- Seed records use image and icon paths from `files/images/`.

## Security Notes

- All write operations use prepared statements.
- Output is escaped before rendering into HTML.
- Admin forms include CSRF tokens.
- Uploaded files are validated by MIME type, extension, and size before being moved.
- SVG uploads are limited to category icons only; slide uploads are restricted to raster image formats.

## Responsive Behavior

- `>= 992px`: desktop tab layout with synchronized content and image sliders
- `< 992px`: accordion layout with touch-friendly background-image slides
- Styling has tuned breakpoints for `1200px`, `992px`, `768px`, and `576px`

## Assumptions

- A simple admin interface is sufficient as long as the code quality, validation, and structure are production-ready.
- CTA buttons are presentation-focused because the supplied schema did not include destination URLs.
- Provided assets are used as-is, including the sample stock watermarks visible in the design source files.

## What To Review

- Frontend rendering in [app/Views/home.php](/d:/project/full-stack-test-master/full-stack-test-master/app/Views/home.php)
- Responsive styles in [assets/css/style.css](/d:/project/full-stack-test-master/full-stack-test-master/assets/css/style.css)
- Admin CRUD flow in [admin/categories.php](/d:/project/full-stack-test-master/full-stack-test-master/admin/categories.php) and [admin/slides.php](/d:/project/full-stack-test-master/full-stack-test-master/admin/slides.php)
- Database schema in [sql/wpoets_full_stack.sql](/d:/project/full-stack-test-master/full-stack-test-master/sql/wpoets_full_stack.sql)
=======
# full-stack-test
>>>>>>> 7b0e7d1825d8b2bb0ccdaa7ca81c5c64cf67d154
