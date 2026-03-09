# Laravel Admin API - Role & Permission Based Backend

A robust, enterprise-level Laravel 11 API backend for managing users, roles, and permissions. Built with **Laravel Sanctum** for authentication and **Spatie Laravel Permission** for advanced role-based access control.

## 🚀 Features

- ✅ **Laravel Sanctum** API Token Authentication
- ✅ **Spatie Laravel Permission** - Enterprise-grade role & permission management
- ✅ **Form Requests** - Clean validation logic separation
- ✅ **API Resources** - Secure, formatted JSON responses
- ✅ **RESTful API** - Following Laravel API First architecture
- ✅ **SQLite Database** - Lightweight, no MySQL setup required

## 📋 Prerequisites

- PHP >= 8.2
- Composer
- SQLite (included with PHP)

## 🔧 Installation & Setup

### Step 1: Navigate to the Laravel API Directory

```bash
cd laravel-admin-api
```

### Step 2: Install Dependencies

```bash
composer install
```

### Step 3: Configure Environment

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

The default configuration uses SQLite, which requires no additional database setup. If you want to use MySQL instead, edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 4: Generate Application Key

```bash
php artisan key:generate
```

### Step 5: Run Migrations & Seed Database

This will create all necessary tables and seed an admin user:

```bash
php artisan migrate --seed
```

**Default Admin Credentials:**
- Email: `admin@admin.com`
- Password: `password`

**Default Editor Credentials:**
- Email: `editor@editor.com`
- Password: `password`

### Step 6: Start the Development Server

```bash
php artisan serve
```

The API will be available at: **http://localhost:8000**

## 📡 API Endpoints

### Authentication Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/login` | Login and receive token | No |
| POST | `/api/logout` | Logout and revoke token | Yes |
| GET | `/api/me` | Get current authenticated user | Yes |

### User Management Endpoints (Admin Only)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/users` | List all users (paginated) |
| POST | `/api/users` | Create a new user |
| GET | `/api/users/{id}` | Get a specific user |
| PUT | `/api/users/{id}` | Update a user |
| DELETE | `/api/users/{id}` | Delete a user |

### Role Endpoints (Admin Only)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/roles` | List all available roles |

## 🔐 Authentication

All protected endpoints require a Bearer token in the Authorization header:

```
Authorization: Bearer {your-token-here}
```

### Example Login Request

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@admin.com",
    "password": "password"
  }'
```

### Example Authenticated Request

```bash
curl -X GET http://localhost:8000/api/users \
  -H "Authorization: Bearer {your-token-here}"
```

## 🎯 Roles & Permissions

The system comes pre-configured with:

- **Admin Role**: Full access to manage users and roles
  - Permissions: `manage users`, `view users`
- **Editor Role**: Read-only access
  - Permissions: `view users`

You can extend this by creating new roles and permissions using Spatie's methods:

```php
$role = Role::create(['name' => 'manager']);
$permission = Permission::create(['name' => 'edit posts']);
$role->givePermissionTo($permission);
```

## 📁 Project Structure

```
laravel-admin-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       ├── AuthController.php
│   │   │       ├── UserController.php
│   │   │       └── RoleController.php
│   │   ├── Requests/
│   │   │   ├── Auth/
│   │   │   │   └── LoginRequest.php
│   │   │   └── User/
│   │   │       ├── StoreUserRequest.php
│   │   │       └── UpdateUserRequest.php
│   │   └── Resources/
│   │       ├── UserResource.php
│   │       └── RoleResource.php
│   └── Models/
│       └── User.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── DatabaseSeeder.php
└── routes/
    └── api.php
```

## 🛠️ Development

### Running Tests

```bash
php artisan test
```

### Clearing Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Resetting Database

```bash
php artisan migrate:fresh --seed
```

## 🔒 Security Notes

- Passwords are automatically hashed using Laravel's `Hash` facade
- API tokens are stored securely using Laravel Sanctum
- CORS is configured to allow requests from the Next.js frontend
- All validation is handled via Form Requests

## 📝 License

This project is part of the Pella assignment submission.
