# PlatePal - Quick Start Guide

## 🚀 Getting Started

### Prerequisites
- PHP 8.3+
- MySQL 8.0+
- Composer
- Node.js & npm

### Installation

1. **Clone the repository**
```bash
cd Project_IT9
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database**
Edit `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_it9
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run migrations and seeders**
```bash
php artisan migrate:fresh --seed
```

6. **Build assets**
```bash
npm run build
```

7. **Start the server**
```bash
php artisan serve
```

Visit: `http://localhost:8000`

---

## 👤 Test Accounts

### Admin Account
- **Email:** admin@platepal.com
- **Password:** admin123
- **Access:** Admin Dashboard, Reports, Featured Caterers Management

### Sample Caterer
- Multiple test caterers are seeded
- Check database for credentials

### Sample Client
- Multiple test clients are seeded
- Check database for credentials

---

## 📋 Main Features

### For Clients
1. **Browse Caterers**
   - Advanced search and filtering
   - View profiles and reviews
   - Check pricing and packages

2. **Book Caterers**
   - Create booking requests
   - Track booking status
   - Message caterers

3. **Leave Reviews**
   - Rate completed bookings
   - Write detailed reviews
   - View other reviews

### For Caterers
1. **Manage Profile**
   - Update business information
   - Set pricing and packages
   - Upload profile image

2. **Manage Bookings**
   - Accept/decline requests
   - Mark as complete
   - Track bookings

3. **Manage Menu**
   - Add packages
   - Add menu items
   - Add add-ons

### For Admins
1. **Approve Caterers**
   - Review applications
   - Approve or reject

2. **Manage Featured**
   - Toggle featured status
   - Search caterers

3. **View Reports**
   - Analytics dashboard
   - Revenue tracking
   - Top performers

---

## 🔧 Configuration

### Email Setup (Optional)
Edit `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@platepal.com
```

### File Storage
Profile images are stored in `storage/app/public/caterers/`

Link storage:
```bash
php artisan storage:link
```

---

## 📁 Project Structure

```
Project_IT9/
├── app/
│   ├── Http/Controllers/     # All controllers
│   ├── Models/               # Database models
│   └── Mail/                 # Email classes
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Test data seeders
├── resources/
│   └── views/                # Blade templates
├── routes/
│   └── web.php               # All routes
└── storage/
    └── app/public/           # User uploads
```

---

## 🗄️ Database Schema

### Key Tables
- **users** - Clients, Caterers, Admins
- **bookings** - Booking requests and confirmations
- **messages** - Client-Caterer conversations
- **reviews** - Ratings and reviews
- **packages** - Catering packages
- **menu_items** - Menu items and add-ons

---

## 🔐 Security

- Role-based access control
- CSRF protection
- Password hashing
- Input validation
- SQL injection prevention

---

## 📞 Support

For issues or questions, check the documentation files:
- `COMPLETION_SUMMARY.md` - Feature overview
- `README.md` - Project information
- `ERD.md` - Database schema

---

## 🎯 Next Steps

1. Customize branding (colors, logo)
2. Configure email notifications
3. Set up payment processing (optional)
4. Deploy to production
5. Monitor analytics

---

**Happy Catering! 🍽️**
