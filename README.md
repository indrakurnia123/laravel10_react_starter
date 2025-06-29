# Laravel React Boilerplate

A comprehensive boilerplate project combining Laravel 10 backend with React 18 frontend, featuring modern authentication, role-based access control, and a responsive dashboard.

## 🚀 Features

### Backend (Laravel 10)
- ✅ **Authentication & Authorization**
  - Laravel Sanctum for API token authentication
  - Role-based access control (RBAC) with Spatie Permission
  - JWT token management with refresh functionality
  - Password reset and email verification
  
- ✅ **User Management**
  - Complete user CRUD operations
  - Profile management with avatar upload
  - User activity logging
  - Account status management (active/inactive)
  
- ✅ **Menu Management**
  - Dynamic menu system based on user roles
  - Hierarchical menu structure support
  - Permission-based menu visibility
  - Admin interface for menu management
  
- ✅ **Security Features**
  - Input validation and sanitization
  - Rate limiting
  - CORS protection
  - Activity logging with Spatie Activity Log
  
- ✅ **API Features**
  - RESTful API design
  - Consistent JSON response structure
  - API versioning (v1)
  - Resource transformers
  - Comprehensive error handling

### Frontend (React 18 + TypeScript)
- ✅ **Modern React Setup**
  - React 18 with TypeScript
  - Vite for fast development
  - Tailwind CSS for styling
  - Responsive design
  
- ✅ **Authentication System**
  - Login/Register forms with validation
  - Protected routes
  - Token management
  - Automatic logout on token expiry
  
- ✅ **State Management**
  - Zustand for global state
  - React Query for server state
  - Persistent auth state
  
- ✅ **UI Components**
  - Modern dashboard layout
  - Sidebar navigation
  - Responsive mobile menu
  - Toast notifications
  - Loading states
  
- ✅ **Form Handling**
  - React Hook Form with Zod validation
  - File upload support
  - Real-time validation feedback

## 📁 Project Structure

```
laravel-react-boilerplate/
├── backend/                     # Laravel API
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/Api/V1/  # API Controllers
│   │   │   ├── Requests/            # Form Request Validation
│   │   │   └── Resources/           # API Resources
│   │   ├── Models/                  # Eloquent Models
│   │   ├── Services/                # Business Logic
│   │   └── Repositories/            # Data Access Layer
│   ├── database/
│   │   ├── migrations/              # Database Migrations
│   │   ├── seeders/                 # Database Seeders
│   │   └── factories/               # Model Factories
│   └── routes/
│       ├── api.php                  # API Routes
│       └── web.php                  # Web Routes
│
├── frontend/                    # React Application
│   ├── public/                      # Static Assets
│   ├── src/
│   │   ├── components/              # Reusable Components
│   │   │   ├── layout/             # Layout Components
│   │   │   └── ui/                 # UI Components
│   │   ├── pages/                   # Page Components
│   │   │   ├── auth/               # Authentication Pages
│   │   │   └── dashboard/          # Dashboard Pages
│   │   ├── hooks/                   # Custom React Hooks
│   │   ├── services/                # API Services
│   │   ├── store/                   # Global State Management
│   │   ├── types/                   # TypeScript Type Definitions
│   │   └── utils/                   # Utility Functions
│   ├── package.json
│   └── vite.config.ts
│
└── README.md
```

## 🛠️ Installation & Setup

### Prerequisites
- PHP 8.1+
- Composer
- Node.js 18+
- MySQL 8.0+
- Git

### Backend Setup

1. **Navigate to backend directory:**
   ```bash
   cd backend
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Create environment file:**
   ```bash
   cp .env.example .env
   ```

4. **Configure database in `.env`:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_react_boilerplate
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

6. **Run migrations and seeders:**
   ```bash
   php artisan migrate --seed
   ```

7. **Publish Sanctum config:**
   ```bash
   php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider"
   ```

8. **Start development server:**
   ```bash
   php artisan serve
   ```

### Frontend Setup

1. **Navigate to frontend directory:**
   ```bash
   cd frontend
   ```

2. **Install dependencies:**
   ```bash
   npm install
   ```

3. **Create environment file:**
   ```bash
   cp .env.example .env
   ```

4. **Configure API URL in `.env`:**
   ```env
   VITE_API_URL=http://localhost:8000/api/v1
   ```

5. **Start development server:**
   ```bash
   npm run dev
   ```

## 🔧 Environment Variables

### Backend (.env)
```env
APP_NAME="Laravel React Boilerplate"
APP_ENV=local
APP_KEY=base64:your-app-key
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_react_boilerplate
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost:3000
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

### Frontend (.env)
```env
VITE_API_URL=http://localhost:8000/api/v1
VITE_APP_NAME="Laravel React Boilerplate"
```

## 📊 Database Schema

### Core Tables
- `users` - User accounts and authentication
- `roles` - User roles (admin, user, etc.)
- `permissions` - System permissions
- `model_has_roles` - User-role relationships
- `role_has_permissions` - Role-permission relationships
- `menus` - Dynamic menu system
- `menu_role` - Menu-role relationships
- `notifications` - User notifications
- `system_settings` - Application settings
- `activity_log` - User activity tracking

### Key Relationships
- Users have many Roles (many-to-many)
- Roles have many Permissions (many-to-many)
- Users have many Notifications (one-to-many)
- Menus have many Roles (many-to-many)
- Menus have hierarchical structure (self-referencing)

## 🔐 Authentication Flow

1. **Registration/Login** → User provides credentials
2. **Validation** → Backend validates and creates user
3. **Token Generation** → Sanctum generates API token
4. **Frontend Storage** → Token stored in localStorage
5. **API Requests** → Token sent in Authorization header
6. **Middleware** → Laravel validates token on protected routes
7. **Auto Refresh** → Token refreshed before expiry

## 🎨 UI/UX Features

- **Responsive Design** - Mobile-first approach
- **Dark Mode** - Theme switching capability
- **Loading States** - Skeleton screens and spinners
- **Error Handling** - User-friendly error messages
- **Toast Notifications** - Success/error feedback
- **Form Validation** - Real-time validation with Zod
- **Accessibility** - WCAG compliant components

## 🧪 Testing

### Backend Testing
```bash
cd backend
php artisan test
```

### Frontend Testing
```bash
cd frontend
npm run test
```

## 📚 API Documentation

### Authentication Endpoints
- `POST /api/v1/auth/login` - User login
- `POST /api/v1/auth/register` - User registration
- `POST /api/v1/auth/logout` - User logout
- `GET /api/v1/auth/me` - Get authenticated user
- `POST /api/v1/auth/refresh` - Refresh token
- `PUT /api/v1/auth/profile` - Update profile

### Menu Endpoints
- `GET /api/v1/menus` - Get user accessible menus
- `GET /api/v1/menus/all` - Get all menus (admin)
- `POST /api/v1/menus` - Create menu (admin)
- `PUT /api/v1/menus/{id}` - Update menu (admin)
- `DELETE /api/v1/menus/{id}` - Delete menu (admin)

## 🔧 Customization

### Adding New Permissions
1. Create permission in database seeder
2. Assign to appropriate roles
3. Use in middleware or policies
4. Check in frontend components

### Adding New Menu Items
1. Create menu via admin interface or seeder
2. Assign to roles
3. Menu automatically appears in sidebar

### Creating New Pages
1. Create React component in `frontend/src/pages/`
2. Add route in `App.tsx`
3. Create corresponding API endpoints
4. Add menu item if needed

## 🚀 Deployment

### Backend Deployment
1. Configure production environment
2. Run migrations: `php artisan migrate --force`
3. Optimize: `php artisan optimize`
4. Configure web server (Apache/Nginx)

### Frontend Deployment
1. Build for production: `npm run build`
2. Upload `dist/` folder to web server
3. Configure routing for SPA

## 🤝 Contributing

1. Fork the repository
2. Create feature branch: `git checkout -b feature/amazing-feature`
3. Commit changes: `git commit -m 'Add amazing feature'`
4. Push to branch: `git push origin feature/amazing-feature`
5. Open Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Laravel team for the excellent framework
- React team for the powerful library
- Spatie for permission and activity log packages
- Tailwind CSS for the utility-first CSS framework
- All contributors and the open-source community

## 📞 Support

For support, email support@example.com or create an issue in the repository.

---

**Happy Coding! 🎉**
