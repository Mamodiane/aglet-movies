# Aglet Movies Assessment

A Laravel-based movie application built for the Aglet Backend / Full Stack assessment.

The application integrates with The Movie Database (TMDB) API to display popular movies, allow authenticated users to add movies to favourites, and manage their favourites list.

---

# Features

- Display popular movies from TMDB API
- Pagination (9 movies per page)
- View up to 45 movies
- User authentication using Laravel Breeze
- Add movies to favourites
- Prevent duplicate favourites
- Dynamic favourite button state ("Already Added")
- Remove movies from favourites
- Contact page
- Responsive Bootstrap layout

---

# Tech Stack

- PHP 8.4+
- Laravel 13
- SQLite
- Bootstrap 5
- TMDB API
- Node.js
- Vite

---

# Requirements

Before running the project, ensure the following are installed:

- PHP 8.4 or higher
- Composer
- SQLite extension enabled in PHP
- Node.js (LTS)
- npm

---

# Windows Setup

## 1. Install PHP 8.4

Open PowerShell as Administrator and run:

```powershell
winget install PHP.PHP.8.4
```

Verify installation:

```powershell
php -v
```

---

## 2. Install Composer

Download Composer installer:

https://getcomposer.org/Composer-Setup.exe

Run the installer and complete setup.

Verify installation:

```powershell
composer -V
```

---

## 3. Install Node.js

Download Node.js LTS version:

https://nodejs.org

Verify installation:

```powershell
node -v
npm -v
```

---

## PowerShell npm Fix (Windows)

If npm scripts are blocked, run PowerShell as Administrator:

```powershell
Set-ExecutionPolicy RemoteSigned -Scope CurrentUser
```

Press:

```plaintext
Y
```

Then restart PowerShell.

---

# Enable SQLite Extension

Run:

```powershell
php --ini
```

Open the loaded `php.ini` file.

Find these lines:

```ini
;extension=pdo_sqlite
;extension=sqlite3
```

Remove the semicolons:

```ini
extension=pdo_sqlite
extension=sqlite3
```

Save the file.

Close and reopen terminal.

Verify SQLite extensions:

```powershell
php -m | findstr sqlite
```

You should see:

```plaintext
pdo_sqlite
sqlite3
```

---

# Project Installation

## 1. Clone Repository

```bash
git clone https://github.com/Mamodiane/aglet-movies.git
```

---

## 2. Enter Project Directory

```bash
cd aglet-movies
```

---

## 3. Install Backend Dependencies

```bash
composer install
```

---

## 4. Install Frontend Dependencies

```bash
npm install
npm run build
```

---

## 5. Create Environment File

### Windows CMD

```bash
copy .env.example .env
```

### PowerShell

```powershell
cp .env.example .env
```

---

## 6. Generate Application Key

```bash
php artisan key:generate
```

---

# Configure Database

This project uses SQLite.

## 7. Create SQLite Database File

### Windows CMD

```bash
type nul > database/database.sqlite
```

### PowerShell

```powershell
New-Item database/database.sqlite
```

If the file already exists, continue.

---

## 8. Update `.env`

Update your `.env` file:

```env
DB_CONNECTION=sqlite

TMDB_BASE_URL=https://api.themoviedb.org/3
TMDB_API_KEY=YOUR_TMDB_API_KEY
```

TMDB API Documentation:
https://developers.themoviedb.org/3/getting-started/introduction

---

# SSL Compatibility Note

For easier local Windows testing, the TMDB API requests use:

```php
Http::withoutVerifying()
```

This avoids common OpenSSL certificate issues on local Windows environments.

---

## 9. Run Migrations

```bash
php artisan migrate
```

---

## 10. Seed Default User

```bash
php artisan db:seed
```

---

## 11. Start Development Server

```bash
php artisan serve
```

Visit:

```plaintext
http://127.0.0.1:8000
```

---

# Default Login Credentials

Email:

```plaintext
jointheteam@aglet.co.za
```

Password:

```plaintext
@TeamAglet
```

---

# Project Structure

- `MovieController` handles movie listing logic
- `FavoriteController` handles favourites functionality
- `TmdbService` handles TMDB API communication
- Blade templates used for frontend rendering
- SQLite used for lightweight local database setup

---

# Architectural Decisions

## Why Laravel?

Laravel was chosen because it provides:

- Clean MVC architecture
- Built-in authentication
- Excellent routing system
- Easy database migrations
- Strong HTTP client support for API integration

## Why SQLite?

SQLite was selected to simplify project setup and portability for assessment review.

## Why a Service Layer?

A dedicated `TmdbService` class was used to separate external API communication from controller logic, improving maintainability and scalability.

---

# Optional Improvements Implemented

- Duplicate favourite prevention
- Dynamic favourite button state
- Responsive navigation bar

---

# Author

Pilato Mamodiane Mmatshipyane

GitHub:
https://github.com/Mamodiane

LinkedIn:
https://www.linkedin.com/in/pilato-mmatshipyane-6360a6122/
