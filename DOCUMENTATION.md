# PlatePal - Catering Booking Platform Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [System Architecture](#system-architecture)
3. [Database Schema](#database-schema)
4. [User Roles & Permissions](#user-roles--permissions)
5. [Features](#features)
6. [Setup Instructions](#setup-instructions)
7. [Routes](#routes)
8. [Middleware](#middleware)
9. [Key Components](#key-components)
10. [Responsive Design](#responsive-design)
11. [Troubleshooting](#troubleshooting)
12. [Development Workflow](#development-workflow)
13. [Performance Considerations](#performance-considerations)
14. [Security Best Practices](#security-best-practices)
15. [Future Enhancements](#future-enhancements)

## Project Overview

PlatePal is a Laravel-based web application that connects clients with caterers in Tagum City. The platform enables clients to browse caterers, book services, and manage their events, while caterers can manage their profiles, receive bookings, and grow their business. The application is fully responsive and optimized for mobile, tablet, and desktop devices.

## System Architecture

### Technology Stack
- **Backend**: Laravel 13
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: MySQL 8.0+
- **JavaScript**: Alpine.js for interactive components
- **Build Tool**: Vite
- **PHP**: 8.4+

### Directory Structure
```
d:\Project_IT9\
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminDashboardController.php
│   │   │   ├── ClientDashboardController.php
│   │   │   ├── CatererDashboardController.php
│   │   │   ├── CatererDetailController.php
│   │   │   ├── CatererProfileController.php
│   │   │   ├── LandingPageController.php
│   │   │   └── AuthController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Booking.php
│   │   ├── Message.php
│   │   └── Package.php
│   └── Providers/
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   │   ├── caterer-login.blade.php
│   │   │   ├── caterer-register.blade.php
│   │   │   ├── client-login.blade.php
│   │   │   └── client-register.blade.php
│   │   ├── client/
│   │   │   ├── dashboard.blade.php
│   │   │   └── browse.blade.php
│   │   ├── caterer/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── profile.blade.php
│   │   │   └── detail.blade.php
│   │   ├── admin/
│   │   │   └── dashboard.blade.php
│   │   ├── components/
│   │   │   ├── dashboard-layout.blade.php
│   │   │   ├── layout.blade.php
│   │   │   └── home/
│   │   │       ├── navbar.blade.php
│   │   │       ├── hero.blade.php
│   │   │       ├── grid.blade.php
│   │   │       ├── card.blade.php
│   │   │       ├── footer.blade.php
│   │   │       ├── search-box.blade.php
│   │   │       └── stats.blade.php
│   │   ├── landingpage/
│   │   │   └── home.blade.php
│   │   └── welcome.blade.php
│   └── css/
│       └── app.css
├── routes/
│   └── web.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── AdminSeeder.php
├── bootstrap/
│   └── app.php
└── DOCUMENTATION.md
```

## Database Schema

### Users Table
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    business_name VARCHAR(255),
    barangay VARCHAR(100),
    role ENUM('client', 'caterer', 'admin') DEFAULT 'client',
    approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    is_active BOOLEAN DEFAULT TRUE,
    profile_image VARCHAR(255),
    cuisine VARCHAR(255),
    price_min DECIMAL(10, 2),
    price_max DECIMAL(10, 2),
    description TEXT,
    min_guest INT,
    max_guest INT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Bookings Table
```sql
CREATE TABLE bookings (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    client_id BIGINT NOT NULL,
    caterer_id BIGINT NOT NULL,
    event_date DATE NOT NULL,
    event_time TIME NOT NULL,
    guest_count INT NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES users(id),
    FOREIGN KEY (caterer_id) REFERENCES users(id)
);
```

### Messages Table
```sql
CREATE TABLE messages (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    sender_id BIGINT NOT NULL,
    recipient_id BIGINT NOT NULL,
    content TEXT NOT NULL,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (recipient_id) REFERENCES users(id)
);
```

### Packages Table
```sql
CREATE TABLE packages (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    caterer_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    min_guests INT DEFAULT 1,
    includes TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (caterer_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## User Roles & Permissions

### Client
- Browse available caterers
- View caterer profiles and details
- Book catering services
- Manage bookings
- Send messages to caterers
- View dashboard with upcoming events and messages
- Search for caterers by barangay and specialty

### Caterer
- Create and manage business profile
- View and respond to booking requests
- Communicate with clients via messages
- Manage availability and services
- View dashboard with bookings and messages
- Create service packages
- Requires admin approval before becoming visible to clients

### Admin
- Access admin dashboard at `/admin/dashboard`
- Approve or reject caterer registrations
- View all users and bookings
- Manage platform settings
- Monitor system activity
- View caterer approval status

**Default Admin Account**:
- Email: `admin@platepal.com`
- Password: `admin123`

## Features

### Authentication
- Separate login/registration for clients and caterers
- Email-based authentication
- Password hashing with bcrypt
- Session management
- Form validation and error handling

### Client Dashboard
- Sidebar navigation with mobile support
- Stats cards showing total bookings, upcoming events, and messages
- Search functionality for caterers
- Upcoming events list
- Recent messages section
- Featured caterers display
- Browse caterers page with filtering

### Caterer Registration & Profile
- Business information form
- Owner details
- Contact information
- Barangay selection from 23 Tagum City barangays:
  - Magugpo Poblacion, Apokon, Visayan Village, Mankilam, New Balamban
  - Pagsabangan, Magugpo East, Magugpo West, San Isidro, San Miguel
  - San Agustin, Nueva Fuerza, Bincungan, Busaon, Canocotan
  - La Filipina, Liboganon, Madaum, Magugpo North, Magugpo South
  - Pandapan, Cuambogan, Magdum
- Profile editing capability
- Approval status tracking
- Service package management

### Admin Dashboard
- Caterer approval/rejection interface
- User management
- Booking overview
- System statistics
- Approval status tracking

### Landing Page
- Hero section with call-to-action
- Featured caterers grid
- Statistics display
- Search functionality
- Responsive navigation
- Footer with links

### Responsive Design
- Mobile-first approach
- Fully responsive across all screen sizes
- Mobile sidebar with Alpine.js toggle
- Touch-friendly interface
- Optimized images and assets

## Setup Instructions

### Prerequisites
- PHP 8.4+
- Composer
- Node.js & npm
- MySQL 8.0+

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd Project_IT9
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   - Update `.env` with your database credentials
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=project_it9
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```
   This will create all necessary tables including users, bookings, messages, and packages.

6. **Seed admin account**
   ```bash
   php artisan db:seed --class=AdminSeeder
   ```
   Creates default admin account with credentials:
   - Email: `admin@platepal.com`
   - Password: `admin123`

7. **Build assets**
   ```bash
   npm run build
   ```

8. **Start development server**
   ```bash
   php artisan serve
   ```

9. **Access the application**
   - Landing page: `http://localhost:8000`
   - Client dashboard: `http://localhost:8000/dashboard`
   - Caterer dashboard: `http://localhost:8000/caterer/dashboard`
   - Admin dashboard: `http://localhost:8000/admin/dashboard`

## Routes

### Public Routes
- `GET /` - Landing page
- `GET /register` - Client registration
- `GET /login` - Client login
- `GET /caterer/register` - Caterer registration
- `GET /caterer/login` - Caterer login
- `POST /register` - Client registration submission
- `POST /login` - Client login submission
- `POST /caterer/register` - Caterer registration submission
- `POST /caterer/login` - Caterer login submission

### Protected Routes (Authenticated Users)
- `POST /logout` - Logout
- `GET /dashboard` - Client dashboard
- `GET /browse-caterers` - Browse all caterers
- `GET /caterer/{id}` - View caterer details
- `GET /caterer/dashboard` - Caterer dashboard
- `GET /caterer/profile` - Edit caterer profile
- `POST /caterer/profile` - Update caterer profile

### Admin Routes (Protected - Admin Only)
- `GET /admin/dashboard` - Admin dashboard
- `POST /admin/caterers/{user}/approve` - Approve caterer
- `POST /admin/caterers/{user}/reject` - Reject caterer

## Middleware

### AdminMiddleware
- Location: `app/Http/Middleware/AdminMiddleware.php`
- Purpose: Protects admin routes by checking user authentication and admin role
- Redirects unauthorized users to home page
- Registered in `bootstrap/app.php` as `admin`

### Authentication Middleware
- Protects all authenticated routes
- Redirects unauthenticated users to login page

## Key Components

### Dashboard Layout Component
- Location: `resources/views/components/dashboard-layout.blade.php`
- Features:
  - Mobile sidebar with Alpine.js toggle
  - Fixed positioning on mobile
  - Static positioning on large screens
  - Overlay when sidebar is open
  - Navigation menu
  - User profile section
  - Responsive design

### Home Page Components
- **Navbar**: Responsive navigation with mobile menu
- **Hero**: Eye-catching hero section with search
- **Grid**: Responsive caterer cards grid
- **Card**: Individual caterer card component
- **Search Box**: Barangay and specialty search
- **Stats**: Statistics display
- **Footer**: Footer with links

### Authentication Controllers
- `AuthController.php` - Handles login/registration for clients and caterers
- `ClientDashboardController.php` - Manages client dashboard data
- `CatererDashboardController.php` - Manages caterer dashboard
- `CatererDetailController.php` - Shows caterer details
- `CatererProfileController.php` - Manages caterer profile
- `AdminDashboardController.php` - Manages admin dashboard and approvals
- `LandingPageController.php` - Manages landing page

## Responsive Design

### Mobile-First Approach
The application uses Tailwind CSS with responsive breakpoints to ensure optimal viewing on all devices:

- **Mobile (default)**: Base styles for phones (320px+)
- **sm (640px)**: Small tablets and large phones
- **md (768px)**: Tablets
- **lg (1024px)**: Desktops
- **xl (1280px)**: Large desktops

### Responsive Components

#### Authentication Pages
- Responsive padding and margins
- Stacked layout on mobile, side-by-side on desktop
- Responsive text sizing (text-2xl sm:text-3xl md:text-4xl)
- Mobile-optimized form inputs
- Hidden decorative elements on small screens

#### Dashboard Layout
- Mobile sidebar with Alpine.js toggle
- Fixed positioning on mobile, static on desktop
- Responsive grid layouts for stats and cards
- Adaptive spacing and padding
- Touch-friendly buttons and inputs

#### Home Page
- Responsive hero section with hidden images on mobile
- Flexible grid for caterer cards (1 column mobile, 2 sm, 3 lg)
- Responsive search box with stacked inputs on mobile
- Adaptive footer layout
- Responsive navigation bar with mobile menu

#### Forms
- Single column on mobile, multi-column on desktop
- Responsive input sizing
- Adaptive label and error message sizing
- Touch-optimized spacing

### Responsive Utilities Used
```
px-4 sm:px-6 lg:px-8          // Responsive padding
text-sm sm:text-base md:text-lg // Responsive text
grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 // Responsive grid
flex flex-col sm:flex-row      // Responsive flex direction
hidden sm:block lg:flex        // Responsive visibility
```

## Troubleshooting

### Common Issues

**Issue**: Admin dashboard not accessible
- **Solution**: Ensure you've run `php artisan db:seed --class=AdminSeeder` to create the admin account
- **Credentials**: admin@platepal.com / admin123

**Issue**: Caterer dashboard returns 404
- **Solution**: Ensure routes are ordered correctly with specific routes before dynamic routes
- **Check**: `/caterer/dashboard` should be defined before `/caterer/{id}`

**Issue**: Packages table doesn't exist
- **Solution**: Run `php artisan migrate` to create all tables
- **Command**: `php artisan migrate --step` to run pending migrations

**Issue**: Barangay dropdown not showing options
- **Solution**: Check that the barangay field is using the select element with all 23 barangays
- **File**: `resources/views/caterer/profile.blade.php` and `resources/views/auth/caterer-register.blade.php`

**Issue**: Mobile sidebar not working
- **Solution**: Ensure Alpine.js is loaded and the x-data directive is properly initialized
- **File**: `resources/views/components/dashboard-layout.blade.php`

**Issue**: Database connection error
- **Solution**: Verify `.env` database credentials match your MySQL configuration
- **Check**: DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD

**Issue**: Assets not loading
- **Solution**: Run `npm run build` to compile Tailwind CSS and JavaScript
- **Development**: Use `npm run dev` for development with hot reload

**Issue**: Route not found error
- **Solution**: Clear route cache with `php artisan route:clear`
- **Alternative**: Run `php artisan optimize:clear` to clear all caches

## Development Workflow

### Adding a New Feature
1. Create migration if database changes needed: `php artisan make:migration create_table_name`
2. Create model if new entity required: `php artisan make:model ModelName`
3. Create controller for business logic: `php artisan make:controller ControllerName`
4. Create routes in `routes/web.php`
5. Create views in `resources/views/`
6. Test functionality

### Code Style
- Follow PSR-12 PHP coding standards
- Use Blade templating for views
- Use Tailwind CSS for styling
- Use Alpine.js for interactive components
- Use responsive design patterns

### Running Tests
```bash
php artisan test
```

### Clearing Caches
```bash
php artisan optimize:clear
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

## Performance Considerations

- Use eager loading for relationships to avoid N+1 queries
- Cache frequently accessed data
- Optimize database queries
- Minimize CSS/JS bundle size
- Use CDN for static assets in production
- Implement pagination for large datasets
- Use database indexing on frequently queried columns

## Security Best Practices

- Always validate and sanitize user input
- Use CSRF protection on forms (automatically included in Laravel)
- Hash passwords with bcrypt
- Implement rate limiting on authentication endpoints
- Use HTTPS in production
- Keep dependencies updated: `composer update`
- Validate file uploads
- Implement proper authorization checks
- Use environment variables for sensitive data
- Never commit `.env` file to version control

## Future Enhancements

- Payment integration (Stripe, PayPal)
- Rating and review system
- Advanced search filters
- Email notifications
- SMS notifications
- Analytics dashboard
- Service packages with customization
- Availability calendar
- Real-time notifications with WebSockets
- Image gallery for caterers
- Menu management system
- Booking confirmation emails
- Invoice generation
- Customer testimonials
- Promotional codes/discounts

## Support & Contact

For issues or questions, please refer to the Laravel documentation at https://laravel.com/docs or contact the development team.

## License

This project is licensed under the MIT License.
