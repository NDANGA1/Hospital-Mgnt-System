# Hospital Management System

A role-based hospital management web application built with PHP and MySQL, covering the core workflows shared between hospital administrators, doctors, and patients.

## Features

- **Three user roles, three dashboards:**
- **Admin** — manage doctors and patients, assign doctor specializations, view appointment history, generate date-range reports, handle contact/support queries, and manage user logs.
- **Doctor** — view assigned patients, manage patient records, review appointment history, and check availability.
- **Patient** — book appointments, view and manage personal medical history, and update their profile.
- **Appointment booking** — patients can check doctor availability and book appointments directly.
- **Medical history tracking** — patients and doctors can view/update medical history records.
- **Reporting** — admins can pull appointment reports over custom date ranges.
- **Secure data handling** — form inputs are sanitized and critical queries use prepared statements to guard against SQL injection.

## Tech Stack

- **Backend:** PHP, mysqli
- **Database:** MySQL
- **Frontend:** Bootstrap-based admin theme, SCSS, jQuery
- **Rich text editing:** CKEditor (for admin/doctor content areas)

## Project Structure

```
Hospital-Mgnt-System/
├── SQL File/hms.sql        # Database schema
└── hospital/
    ├── index.php            # Public landing page
    └── hms/
        ├── admin/           # Admin dashboard & management pages
        ├── doctor/          # Doctor dashboard & patient management
        └── *.php            # Patient-facing pages (booking, dashboard, medical history)
```

## Running Locally

1. Start Apache + MySQL (e.g. via XAMPP).
2. Import `SQL File/hms.sql` into MySQL.
3. Update the database credentials in the relevant `include/config.php` files under `hospital/hms/`.
4. Open `hospital/index.php` via `localhost` in your browser.


## Author

**Praygod Ndanga** — BSc. Information Technology, Institute of Finance Management

Login Details for admin : admin/Test@12345
Login Details for Patient: johndoe12@test.com/Test@123
Login Details for Doctor: john@john.com

