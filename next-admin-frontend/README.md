# Next.js Admin Panel Frontend

This project is a React (Next.js 15) frontend for the Laravel Admin API.

## Getting Started

1. Install dependencies:
   ```bash
   npm install
   ```

2. Run the development server:
   ```bash
   npm run dev
   ```

3. Open [http://localhost:3000](http://localhost:3000) with your browser. It will redirect you to `/login`.

## Features
- Login Page communicating with Laravel Sanctum token endpoint.
- Role-based Layout displaying user info.
- Users CRUD page (Add, Edit, List, Delete users and manage roles).

## Environment Variables
By default, the API base URL is set to `http://localhost:8000/api`. Ensure your Laravel server is running on port 8000 and has CORS properly configured to allow requests from `http://localhost:3000`.