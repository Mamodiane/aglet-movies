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
- Remove movies from favourites
- Contact page
- Responsive Bootstrap layout

---

# Tech Stack

- PHP 8.4
- Laravel 12
- SQLite
- Bootstrap 5
- TMDB API

---

# Installation

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

## 3. Install Dependencies

```bash
composer install
```

---

## 4. Create Environment File

```bash
cp .env.example .env
```

---

## 5. Generate Application Key

```bash
php artisan key:generate
```

---

## 6. Configure Database

This project uses SQLite.

Create the SQLite database file:

```bash
type nul > database/database.sqlite
```

Then update your `.env` file:

```env
DB_CONNECTION=sqlite
```

---

## 7. Configure TMDB API

Update your `.env` file with your TMDB API credentials:

```env
TMDB_BASE_URL=https://api.themoviedb.org/3
TMDB_API_KEY=YOUR_TMDB_API_KEY
```

TMDB API Documentation:
https://developers.themoviedb.org/3/getting-started/introduction

---

## 8. Run Migrations

```bash
php artisan migrate
```

---

## 9. Seed Default User

```bash
php artisan db:seed
```

---

## 10. Start Development Server

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
- Dynamic favourite button state ("Already Added")
- Responsive navigation bar

---

# Author

Mmatshipyane Pilato Pilato

GitHub:
https://github.com/Mamodiane

LinkedIn:
https://www.linkedin.com/in/pilato-mmatshipyane-6360a6122/
