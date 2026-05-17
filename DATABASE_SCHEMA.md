# PlatePal Database Schema Documentation

**Database Name:** `project_it9`  
**Database Type:** MySQL  
**Laravel Version:** 11.x

---

## Core Tables

### 1. users
Stores both clients and caterers with role-based access.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique user identifier |
| name | VARCHAR(255) | NOT NULL | User's full name |
| email | VARCHAR(255) | UNIQUE, NOT NULL | User's email address |
| email_verified_at | TIMESTAMP | NULLABLE | Email verification timestamp |
| password | VARCHAR(255) | NOT NULL | Hashed password |
| role | ENUM('client', 'caterer', 'admin') | NOT NULL | User role |
| phone | VARCHAR(255) | UNIQUE, NULLABLE | Contact phone number |
| business_name | VARCHAR(255) | NULLABLE | Caterer's business name |
| barangay | VARCHAR(255) | NULLABLE | Caterer's location |
| status | ENUM('pending', 'approved', 'rejected') | DEFAULT 'pending' | Account approval status |
| rejection_reason | TEXT | NULLABLE | Reason for account rejection |
| is_featured | BOOLEAN | DEFAULT false | Featured caterer flag |
| reviews_count | INTEGER | DEFAULT 0 | Cached review count |
| average_rating | DECIMAL(3,2) | NULLABLE | Cached average rating |
| auto_feature_reviews | BOOLEAN | DEFAULT true | Auto-feature reviews setting |
| about | TEXT | NULLABLE | Caterer's about section |
| gallery | JSON | NULLABLE | Caterer's gallery images |
| remember_token | VARCHAR(100) | NULLABLE | Remember me token |
| created_at | TIMESTAMP | NOT NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Record update timestamp |

**Indexes:**
- PRIMARY: id
- UNIQUE: email, phone
- INDEX: role, status, is_featured

---

### 2. bookings
Manages event bookings between clients and caterers.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique booking identifier |
| user_id | BIGINT | FOREIGN KEY → users(id), CASCADE | Client who made the booking |
| caterer_id | BIGINT | FOREIGN KEY → users(id), CASCADE | Caterer for the booking |
| event_title | VARCHAR(255) | NOT NULL | Event name/title |
| event_date | DATE | NOT NULL | Event date |
| guests | INTEGER | DEFAULT 0 | Number of guests |
| status | ENUM('pending', 'confirmed', 'cancelled', 'completed') | DEFAULT 'pending' | Booking status |
| decline_reason | TEXT | NULLABLE | Reason for cancellation |
| package_id | BIGINT | NULLABLE | Selected package |
| package_name | VARCHAR(255) | NULLABLE | Package name snapshot |
| package_price | DECIMAL(10,2) | NULLABLE | Package price snapshot |
| price_per_head | DECIMAL(10,2) | NULLABLE | Price per head |
| final_price | DECIMAL(10,2) | NULLABLE | Total final price |
| client_budget | DECIMAL(10,2) | NULLABLE | Client's budget |
| viewed_at | TIMESTAMP | NULLABLE | When caterer viewed booking |
| created_at | TIMESTAMP | NOT NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Record update timestamp |

**Indexes:**
- PRIMARY: id
- FOREIGN KEY: user_id, caterer_id
- INDEX: status, event_date

---

### 3. booking_items
Stores individual menu items and add-ons for each booking.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique item identifier |
| booking_id | BIGINT | FOREIGN KEY → bookings(id), CASCADE | Associated booking |
| menu_item_id | BIGINT | FOREIGN KEY → menu_items(id), CASCADE | Menu item reference |
| item_name | VARCHAR(255) | NOT NULL | Item name snapshot |
| item_type | VARCHAR(255) | NOT NULL | Item type (menu/addon) |
| item_price | DECIMAL(10,2) | NOT NULL | Item price snapshot |
| quantity | INTEGER | DEFAULT 1 | Quantity ordered |
| created_at | TIMESTAMP | NOT NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Record update timestamp |

**Indexes:**
- PRIMARY: id
- FOREIGN KEY: booking_id, menu_item_id

---

### 4. packages
Catering packages offered by caterers.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique package identifier |
| caterer_id | BIGINT | FOREIGN KEY → users(id), CASCADE | Package owner |
| name | VARCHAR(255) | NOT NULL | Package name |
| description | TEXT | NULLABLE | Package description |
| price | DECIMAL(10,2) | NOT NULL | Package price |
| min_guests | INTEGER | DEFAULT 1 | Minimum guest requirement |
| includes | TEXT | NULLABLE | JSON array of included items |
| status | ENUM('draft', 'live') | DEFAULT 'draft' | Package visibility status |
| category | VARCHAR(255) | NULLABLE | Package category |
| image | VARCHAR(255) | NULLABLE | Package image path |
| created_at | TIMESTAMP | NOT NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Record update timestamp |

**Indexes:**
- PRIMARY: id
- FOREIGN KEY: caterer_id
- INDEX: status, category

---

### 5. menu_items
Individual menu items and add-ons offered by caterers.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique menu item identifier |
| caterer_id | BIGINT | FOREIGN KEY → users(id), CASCADE | Item owner |
| name | VARCHAR(255) | NOT NULL | Item name |
| description | TEXT | NULLABLE | Item description |
| price | DECIMAL(10,2) | NOT NULL | Item price |
| unit | VARCHAR(255) | DEFAULT 'head' | Pricing unit |
| type | ENUM('menu', 'addon') | DEFAULT 'menu' | Item type |
| category | VARCHAR(255) | DEFAULT 'main' | Item category |
| image_path | VARCHAR(255) | NULLABLE | Item image path |
| created_at | TIMESTAMP | NOT NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Record update timestamp |

**Indexes:**
- PRIMARY: id
- FOREIGN KEY: caterer_id
- INDEX: type, category

---

### 6. reviews
Customer reviews and ratings for caterers.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique review identifier |
| booking_id | BIGINT | UNIQUE, FOREIGN KEY → bookings(id), NULL ON DELETE | Associated booking |
| client_id | BIGINT | FOREIGN KEY → users(id), NULL ON DELETE | Review author |
| caterer_id | BIGINT | FOREIGN KEY → users(id), CASCADE | Reviewed caterer |
| reviewer_name | VARCHAR(255) | NULLABLE | Reviewer name snapshot |
| package_name | VARCHAR(255) | NULLABLE | Package name snapshot |
| title | VARCHAR(255) | NULLABLE | Review title |
| body | TEXT | NOT NULL | Review content |
| rating | TINYINT | NOT NULL | Rating (1-5) |
| status | ENUM('public', 'hidden') | DEFAULT 'public' | Review visibility |
| is_featured | BOOLEAN | DEFAULT false | Featured review flag |
| is_auto_featured | BOOLEAN | DEFAULT false | Auto-featured flag |
| caterer_reply | TEXT | NULLABLE | Caterer's response |
| replied_at | TIMESTAMP | NULLABLE | Reply timestamp |
| reported_at | TIMESTAMP | NULLABLE | Report timestamp |
| report_reason | TEXT | NULLABLE | Report reason |
| reviewed_at | TIMESTAMP | NULLABLE | Review submission timestamp |
| created_at | TIMESTAMP | NOT NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Record update timestamp |

**Indexes:**
- PRIMARY: id
- UNIQUE: booking_id
- FOREIGN KEY: booking_id, client_id, caterer_id
- INDEX: (caterer_id, status), (caterer_id, is_featured)

---

### 7. messages
Real-time messaging between clients and caterers.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique message identifier |
| user_id | BIGINT | FOREIGN KEY → users(id), CASCADE | Client user |
| caterer_id | BIGINT | FOREIGN KEY → users(id), CASCADE | Caterer user |
| body | TEXT | NOT NULL | Message content |
| is_read | BOOLEAN | DEFAULT false | Read status |
| sender | ENUM('client', 'caterer') | NOT NULL | Message sender type |
| attachments | JSON | NULLABLE | Message attachments |
| created_at | TIMESTAMP | NOT NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Record update timestamp |

**Indexes:**
- PRIMARY: id
- FOREIGN KEY: user_id, caterer_id
- INDEX: (user_id, caterer_id), is_read

---

### 8. saved_caterers
Client's saved/favorited caterers.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique record identifier |
| user_id | BIGINT | FOREIGN KEY → users(id), CASCADE | Client user |
| caterer_id | BIGINT | FOREIGN KEY → users(id), CASCADE | Saved caterer |
| created_at | TIMESTAMP | NOT NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Record update timestamp |

**Indexes:**
- PRIMARY: id
- UNIQUE: (user_id, caterer_id)
- FOREIGN KEY: user_id, caterer_id

---

### 9. system_feedback
User feedback and bug reports.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique feedback identifier |
| user_id | BIGINT | FOREIGN KEY → users(id), NULL ON DELETE | Feedback author |
| role | VARCHAR(30) | NULLABLE | User role snapshot |
| type | VARCHAR(30) | NOT NULL | Feedback type |
| rating | TINYINT | NULLABLE | Rating (1-5) |
| message | TEXT | NOT NULL | Feedback content |
| page_url | VARCHAR(500) | NULLABLE | Page URL where feedback was given |
| user_agent | TEXT | NULLABLE | Browser user agent |
| status | VARCHAR(30) | DEFAULT 'new' | Feedback status |
| created_at | TIMESTAMP | NOT NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Record update timestamp |

**Indexes:**
- PRIMARY: id
- FOREIGN KEY: user_id
- INDEX: status, type

---

## System Tables

### 10. password_reset_tokens
Password reset token management.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| email | VARCHAR(255) | PRIMARY KEY | User email |
| token | VARCHAR(255) | NOT NULL | Reset token |
| created_at | TIMESTAMP | NULLABLE | Token creation timestamp |

---

### 11. sessions
User session management.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | VARCHAR(255) | PRIMARY KEY | Session identifier |
| user_id | BIGINT | FOREIGN KEY → users(id), NULLABLE | Associated user |
| ip_address | VARCHAR(45) | NULLABLE | Client IP address |
| user_agent | TEXT | NULLABLE | Browser user agent |
| payload | LONGTEXT | NOT NULL | Session data |
| last_activity | INTEGER | NOT NULL | Last activity timestamp |

**Indexes:**
- PRIMARY: id
- INDEX: user_id, last_activity

---

### 12. cache
Application cache storage.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| key | VARCHAR(255) | PRIMARY KEY | Cache key |
| value | MEDIUMTEXT | NOT NULL | Cached value |
| expiration | BIGINT | NOT NULL | Expiration timestamp |

**Indexes:**
- PRIMARY: key
- INDEX: expiration

---

### 13. cache_locks
Cache lock management.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| key | VARCHAR(255) | PRIMARY KEY | Lock key |
| owner | VARCHAR(255) | NOT NULL | Lock owner |
| expiration | BIGINT | NOT NULL | Lock expiration |

**Indexes:**
- PRIMARY: key
- INDEX: expiration

---

### 14. jobs
Queue job management.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Job identifier |
| queue | VARCHAR(255) | NOT NULL | Queue name |
| payload | LONGTEXT | NOT NULL | Job payload |
| attempts | SMALLINT | NOT NULL | Attempt count |
| reserved_at | INTEGER | NULLABLE | Reserved timestamp |
| available_at | INTEGER | NOT NULL | Available timestamp |
| created_at | INTEGER | NOT NULL | Creation timestamp |

**Indexes:**
- PRIMARY: id
- INDEX: queue

---

### 15. job_batches
Batch job tracking.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | VARCHAR(255) | PRIMARY KEY | Batch identifier |
| name | VARCHAR(255) | NOT NULL | Batch name |
| total_jobs | INTEGER | NOT NULL | Total jobs in batch |
| pending_jobs | INTEGER | NOT NULL | Pending jobs count |
| failed_jobs | INTEGER | NOT NULL | Failed jobs count |
| failed_job_ids | LONGTEXT | NOT NULL | Failed job IDs |
| options | MEDIUMTEXT | NULLABLE | Batch options |
| cancelled_at | INTEGER | NULLABLE | Cancellation timestamp |
| created_at | INTEGER | NOT NULL | Creation timestamp |
| finished_at | INTEGER | NULLABLE | Completion timestamp |

---

### 16. failed_jobs
Failed job tracking.

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Record identifier |
| uuid | VARCHAR(255) | UNIQUE | Job UUID |
| connection | TEXT | NOT NULL | Connection name |
| queue | TEXT | NOT NULL | Queue name |
| payload | LONGTEXT | NOT NULL | Job payload |
| exception | LONGTEXT | NOT NULL | Exception details |
| failed_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Failure timestamp |

---

## Relationships Summary

### Users (Caterers & Clients)
- **Has Many:** bookings (as client)
- **Has Many:** bookings (as caterer)
- **Has Many:** packages
- **Has Many:** menu_items
- **Has Many:** reviews (as caterer)
- **Has Many:** reviews (as client)
- **Has Many:** messages
- **Has Many:** saved_caterers

### Bookings
- **Belongs To:** user (client)
- **Belongs To:** caterer (user)
- **Has Many:** booking_items
- **Has One:** review

### Packages
- **Belongs To:** caterer (user)

### Menu Items
- **Belongs To:** caterer (user)
- **Has Many:** booking_items

### Reviews
- **Belongs To:** booking
- **Belongs To:** client (user)
- **Belongs To:** caterer (user)

### Messages
- **Belongs To:** user (client)
- **Belongs To:** caterer (user)

### Booking Items
- **Belongs To:** booking
- **Belongs To:** menu_item

### Saved Caterers
- **Belongs To:** user (client)
- **Belongs To:** caterer (user)

### System Feedback
- **Belongs To:** user

---

## Enum Values Reference

### users.role
- `client` - Regular client user
- `caterer` - Catering service provider
- `admin` - System administrator

### users.status
- `pending` - Awaiting approval
- `approved` - Account approved
- `rejected` - Account rejected

### bookings.status
- `pending` - Awaiting caterer confirmation
- `confirmed` - Caterer confirmed booking
- `cancelled` - Booking cancelled
- `completed` - Event completed

### packages.status
- `draft` - Not visible to clients
- `live` - Visible to clients

### menu_items.type
- `menu` - Regular menu item
- `addon` - Additional item/service

### reviews.status
- `public` - Visible to all users
- `hidden` - Hidden from public view

### messages.sender
- `client` - Message from client
- `caterer` - Message from caterer

---

## Database Configuration

**Connection Details** (from .env):
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_it9
DB_USERNAME=root
DB_PASSWORD=
```

**Session & Cache:**
- Session Driver: database
- Cache Store: database
- Queue Connection: database
