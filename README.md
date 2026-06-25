# Transportation Dashboard

## About the System
The **Transportation Dashboard** is a web-based data visualization platform that displays quarterly transportation statistics. It features a dashboard to present visual data and allows authorized administrators to seamlessly import data through Excel files. It incorporates role-based access control with three distinct user roles: Superadmin, Admin, and Normal User.

## Features & Functions
- **Interactive Dashboard:** View comprehensive quarterly statistics and data visualizations.
- **Excel Data Import:** Admins can quickly upload and update the system's data by importing Excel spreadsheets.
- **Role-Based Access Control (RBAC):**
  - **Superadmin:** Has the highest privilege. Can access all features and has the exclusive ability to add new Admin users.
  - **Admin:** Can access the dashboard and upload Excel data files to update the statistics.
  - **Normal User:** Can access and view the dashboard statistics but cannot modify or upload data.
- **User Authentication:** Secure login, registration, and profile management functionality.

## Technologies Used
- **Language:** PHP 8.2+, JavaScript
- **Framework:** Laravel 12
- **Frontend Stack:** Tailwind CSS, Alpine.js, Vite
- **Database:** SQLite (default, easily configurable to MySQL/PostgreSQL)
- **Key Packages:**
  - `laravel/breeze` (Authentication)
  - `maatwebsite/excel` (Excel Import/Export)

## How to Install and Run Locally

Follow these instructions to get the project up and running on your local machine.

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm
- Git

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/azfrhkmi/Transportation-Dashboard.git
   cd Transportation-Dashboard
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install frontend dependencies**
   ```bash
   npm install
   ```

4. **Set up the environment variables**
   Copy the `.env.example` file to `.env`:
   ```bash
   cp .env.example .env
   ```
   *(On Windows Command Prompt, use `copy .env.example .env`)*

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Configure Database & Run Migrations**
   By default, the application uses SQLite. Ensure your `.env` file has `DB_CONNECTION=sqlite`. Then, run the database migrations and seed the default users:
   ```bash
   php artisan migrate --seed
   ```

7. **Run the Development Servers**
   You will need two terminal windows to run both the backend server and the frontend build tool simultaneously.

   **Terminal 1 (Backend):**
   ```bash
   php artisan serve
   ```

   **Terminal 2 (Frontend Vite Server):**
   ```bash
   npm run dev
   ```

8. **Access the Application**
   Open your browser and navigate to: [http://localhost:8000](http://localhost:8000)

## Default Test Accounts
After running `php artisan migrate --seed`, you can log in with the following default test accounts:

| Role | Email | Password |
|---|---|---|
| **Superadmin** | `superadmin@admin.com` | `password` |
| **Admin** | `admin@admin.com` | `password` |
| **Normal User** | `user@user.com` | `password` |
