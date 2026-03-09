# Next.js Admin Panel Frontend

A modern, enterprise-grade admin panel built with **Next.js 15**, **TypeScript**, **TailwindCSS**, and **TanStack React Table**. Features beautiful UI components, real-time data fetching with SWR, and seamless integration with the Laravel API backend.

## 🚀 Features

- ✅ **Modern UI/UX** - Professional design with Lucide React icons
- ✅ **TanStack React Table** - Advanced data tables with sorting, filtering, and pagination
- ✅ **SWR Data Fetching** - Automatic caching, revalidation, and loading states
- ✅ **React Hot Toast** - Elegant success/error notifications
- ✅ **Axios Interceptors** - Centralized API calls with automatic token injection
- ✅ **TypeScript** - Full type safety throughout the application
- ✅ **Responsive Design** - Mobile-friendly sidebar and layouts

## 📋 Prerequisites

- Node.js >= 18.x
- npm or yarn
- Laravel API backend running on `http://localhost:8000`

## 🔧 Installation & Setup

### Step 1: Navigate to the Frontend Directory

```bash
cd next-admin-frontend
```

### Step 2: Install Dependencies

```bash
npm install
```

If you encounter peer dependency conflicts, use:

```bash
npm install --legacy-peer-deps
```

### Step 3: Configure API Endpoint (Optional)

The default API URL is set to `http://localhost:8000/api` in `lib/axios.ts`. If your Laravel backend runs on a different port, update it:

```typescript
// lib/axios.ts
const axios = Axios.create({
    baseURL: 'http://localhost:YOUR_PORT/api',
    // ...
});
```

### Step 4: Start the Development Server

```bash
npm run dev
```

The application will be available at: **http://localhost:3000**

You will be automatically redirected to the login page.

## 🔐 Login Credentials

Use the credentials seeded in the Laravel backend:

**Admin Account:**
- Email: `admin@admin.com`
- Password: `password`

**Editor Account:**
- Email: `editor@editor.com`
- Password: `password`

## 📱 Application Structure

### Pages

- **`/login`** - Authentication page with email/password form
- **`/admin/users`** - User management dashboard with CRUD operations

### Components

- **`app/admin/layout.tsx`** - Main admin layout with sidebar navigation
- **`app/admin/users/page.tsx`** - Users table with search, sort, and pagination
- **`lib/axios.ts`** - Centralized Axios instance with interceptors

## 🎨 Features Breakdown

### User Management Table

- **Search Functionality**: Real-time filtering by name or email
- **Column Sorting**: Click column headers to sort ascending/descending
- **Pagination**: Navigate through pages of users
- **Role Badges**: Visual indicators for user roles
- **Action Buttons**: Edit and Delete with confirmation modals

### Add/Edit User Modal

- **Form Validation**: Required fields and password strength
- **Role Selection**: Dropdown populated from API
- **Loading States**: Disabled buttons during submission
- **Success/Error Toasts**: Instant feedback on actions

### Delete Confirmation

- **Modern Modal**: Replaces browser `confirm()` dialog
- **Visual Warning**: Red icon and clear messaging
- **Loading State**: Prevents double-clicks during deletion

## 🛠️ Development

### Build for Production

```bash
npm run build
npm start
```

### Linting

```bash
npm run lint
```

## 📦 Key Dependencies

- **next**: 15.0.0 - React framework
- **react**: 19.0.0 - UI library
- **axios**: HTTP client
- **swr**: Data fetching and caching
- **@tanstack/react-table**: Table component library
- **react-hot-toast**: Toast notifications
- **lucide-react**: Icon library
- **tailwindcss**: Utility-first CSS framework

## 🔄 API Integration

The frontend communicates with the Laravel API using:

1. **Axios Instance** (`lib/axios.ts`):
   - Automatically adds Bearer token to all requests
   - Intercepts 401 responses to redirect to login
   - Centralized error handling

2. **SWR Hooks**:
   - Automatic data fetching and caching
   - Real-time revalidation on focus
   - Optimistic updates

### Example API Call

```typescript
import useSWR from 'swr';
import axios from '@/lib/axios';

const fetcher = (url: string) => axios.get(url).then((res) => res.data.data);

function UsersPage() {
  const { data, mutate } = useSWR('/users', fetcher);
  // data is automatically cached and revalidated
}
```

## 🎯 Environment Variables

Create a `.env.local` file if needed (currently not required):

```env
NEXT_PUBLIC_API_URL=http://localhost:8000/api
```

## 🐛 Troubleshooting

### CORS Errors

Ensure your Laravel backend has CORS configured to allow requests from `http://localhost:3000`. Check `config/cors.php` in your Laravel project.

### Token Not Persisting

Tokens are stored in `localStorage`. If you're experiencing issues:
1. Clear browser localStorage
2. Check browser console for errors
3. Verify the API is returning tokens correctly

### Build Errors

If you encounter build errors related to React 19, ensure you're using `--legacy-peer-deps` when installing:

```bash
rm -rf node_modules package-lock.json
npm install --legacy-peer-deps
```

## 📝 License

This project is part of the Pella assignment submission.
