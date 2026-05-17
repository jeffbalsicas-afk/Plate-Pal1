# PlatePal - Entity Relationship Diagram (ERD)

## Complete Database ERD

```mermaid
erDiagram
    %% Core Relationships
    USERS ||--o{ BOOKINGS : "creates (as client)"
    USERS ||--o{ BOOKINGS : "receives (as caterer)"
    USERS ||--o{ MESSAGES : "sends/receives"
    USERS ||--o{ PACKAGES : "offers"
    USERS ||--o{ MENU_ITEMS : "creates"
    USERS ||--o{ REVIEWS : "receives (as caterer)"
    USERS ||--o{ REVIEWS : "writes (as client)"
    USERS ||--o{ SAVED_CATERERS : "saves"
    USERS ||--o{ SYSTEM_FEEDBACK : "submits"
    
    BOOKINGS ||--o{ BOOKING_ITEMS : "contains"
    BOOKINGS ||--o| REVIEWS : "generates"
    
    MENU_ITEMS ||--o{ BOOKING_ITEMS : "included in"
    
    USERS ||--o{ SESSIONS : "has"

    %% Users Table
    USERS {
        bigint id PK
        string name
        string email UK "Unique"
        timestamp email_verified_at
        string password
        enum role "client, caterer, admin"
        string phone UK "Unique"
        string business_name "Caterer only"
        string barangay "Caterer location"
        enum status "pending, approved, rejected"
        text rejection_reason
        boolean is_featured "Featured caterer"
        int reviews_count "Cached count"
        decimal average_rating "Cached rating"
        boolean auto_feature_reviews
        text about "Caterer profile"
        json gallery "Image gallery"
        string remember_token
        timestamp created_at
        timestamp updated_at
    }

    %% Bookings Table
    BOOKINGS {
        bigint id PK
        bigint user_id FK "Client"
        bigint caterer_id FK "Caterer"
        string event_title
        date event_date
        int guests
        enum status "pending, confirmed, cancelled, completed"
        text decline_reason
        bigint package_id "Selected package"
        string package_name "Snapshot"
        decimal package_price "Snapshot"
        decimal price_per_head
        decimal final_price
        decimal client_budget
        timestamp viewed_at "Caterer viewed"
        timestamp created_at
        timestamp updated_at
    }

    %% Booking Items Table
    BOOKING_ITEMS {
        bigint id PK
        bigint booking_id FK
        bigint menu_item_id FK
        string item_name "Snapshot"
        string item_type "menu/addon"
        decimal item_price "Snapshot"
        int quantity
        timestamp created_at
        timestamp updated_at
    }

    %% Packages Table
    PACKAGES {
        bigint id PK
        bigint caterer_id FK
        string name
        text description
        decimal price
        int min_guests
        text includes "JSON array"
        enum status "draft, live"
        string category
        string image
        timestamp created_at
        timestamp updated_at
    }

    %% Menu Items Table
    MENU_ITEMS {
        bigint id PK
        bigint caterer_id FK
        string name
        text description
        decimal price
        string unit "head, tray, etc"
        enum type "menu, addon"
        string category
        string image_path
        timestamp created_at
        timestamp updated_at
    }

    %% Reviews Table
    REVIEWS {
        bigint id PK
        bigint booking_id FK UK "Unique"
        bigint client_id FK
        bigint caterer_id FK
        string reviewer_name
        string package_name
        string title
        text body
        tinyint rating "1-5"
        enum status "public, hidden"
        boolean is_featured
        boolean is_auto_featured
        text caterer_reply
        timestamp replied_at
        timestamp reported_at
        text report_reason
        timestamp reviewed_at
        timestamp created_at
        timestamp updated_at
    }

    %% Messages Table
    MESSAGES {
        bigint id PK
        bigint user_id FK "Client"
        bigint caterer_id FK "Caterer"
        text body
        boolean is_read
        enum sender "client, caterer"
        json attachments
        timestamp created_at
        timestamp updated_at
    }

    %% Saved Caterers Table
    SAVED_CATERERS {
        bigint id PK
        bigint user_id FK "Client"
        bigint caterer_id FK "Saved caterer"
        timestamp created_at
        timestamp updated_at
    }

    %% System Feedback Table
    SYSTEM_FEEDBACK {
        bigint id PK
        bigint user_id FK
        string role "Snapshot"
        string type "bug, feature, feedback"
        tinyint rating "1-5"
        text message
        string page_url
        text user_agent
        string status "new, reviewed, resolved"
        timestamp created_at
        timestamp updated_at
    }

    %% Sessions Table
    SESSIONS {
        string id PK
        bigint user_id FK
        string ip_address
        text user_agent
        longtext payload
        int last_activity
    }

    %% Password Reset Tokens
    PASSWORD_RESET_TOKENS {
        string email PK
        string token
        timestamp created_at
    }

    %% Cache Table
    CACHE {
        string key PK
        mediumtext value
        bigint expiration
    }

    %% Cache Locks Table
    CACHE_LOCKS {
        string key PK
        string owner
        bigint expiration
    }

    %% Jobs Table
    JOBS {
        bigint id PK
        string queue
        longtext payload
        smallint attempts
        int reserved_at
        int available_at
        int created_at
    }

    %% Job Batches Table
    JOB_BATCHES {
        string id PK
        string name
        int total_jobs
        int pending_jobs
        int failed_jobs
        longtext failed_job_ids
        mediumtext options
        int cancelled_at
        int created_at
        int finished_at
    }

    %% Failed Jobs Table
    FAILED_JOBS {
        bigint id PK
        string uuid UK "Unique"
        text connection
        text queue
        longtext payload
        longtext exception
        timestamp failed_at
    }
```

---

## Relationship Details

### 1. Users ↔ Bookings
- **Type:** One-to-Many (bidirectional)
- **As Client:** A user (client) can create multiple bookings
- **As Caterer:** A user (caterer) can receive multiple bookings
- **Foreign Keys:** 
  - `bookings.user_id` → `users.id` (CASCADE)
  - `bookings.caterer_id` → `users.id` (CASCADE)

### 2. Users ↔ Packages
- **Type:** One-to-Many
- **Description:** A caterer can offer multiple packages
- **Foreign Key:** `packages.caterer_id` → `users.id` (CASCADE)

### 3. Users ↔ Menu Items
- **Type:** One-to-Many
- **Description:** A caterer can create multiple menu items
- **Foreign Key:** `menu_items.caterer_id` → `users.id` (CASCADE)

### 4. Users ↔ Reviews
- **Type:** One-to-Many (bidirectional)
- **As Caterer:** A caterer can receive multiple reviews
- **As Client:** A client can write multiple reviews
- **Foreign Keys:**
  - `reviews.caterer_id` → `users.id` (CASCADE)
  - `reviews.client_id` → `users.id` (NULL ON DELETE)

### 5. Bookings ↔ Reviews
- **Type:** One-to-One
- **Description:** Each booking can have one review
- **Foreign Key:** `reviews.booking_id` → `bookings.id` (UNIQUE, NULL ON DELETE)

### 6. Bookings ↔ Booking Items
- **Type:** One-to-Many
- **Description:** A booking can contain multiple menu items/add-ons
- **Foreign Key:** `booking_items.booking_id` → `bookings.id` (CASCADE)

### 7. Menu Items ↔ Booking Items
- **Type:** One-to-Many
- **Description:** A menu item can be included in multiple bookings
- **Foreign Key:** `booking_items.menu_item_id` → `menu_items.id` (CASCADE)

### 8. Users ↔ Messages
- **Type:** One-to-Many (bidirectional)
- **Description:** Messages between clients and caterers
- **Foreign Keys:**
  - `messages.user_id` → `users.id` (CASCADE)
  - `messages.caterer_id` → `users.id` (CASCADE)

### 9. Users ↔ Saved Caterers
- **Type:** Many-to-Many (through saved_caterers)
- **Description:** Clients can save/favorite multiple caterers
- **Foreign Keys:**
  - `saved_caterers.user_id` → `users.id` (CASCADE)
  - `saved_caterers.caterer_id` → `users.id` (CASCADE)
- **Unique Constraint:** (user_id, caterer_id)

### 10. Users ↔ System Feedback
- **Type:** One-to-Many
- **Description:** Users can submit multiple feedback entries
- **Foreign Key:** `system_feedback.user_id` → `users.id` (NULL ON DELETE)

### 11. Users ↔ Sessions
- **Type:** One-to-Many
- **Description:** A user can have multiple active sessions
- **Foreign Key:** `sessions.user_id` → `users.id` (NULLABLE)

---

## Key Business Rules

### Booking Flow
1. Client creates booking → status: `pending`
2. Caterer views booking → `viewed_at` timestamp set
3. Caterer confirms → status: `confirmed`
4. Event occurs → status: `completed`
5. Client can leave review (one per booking)

### Review System
- One review per booking (unique constraint)
- Auto-featuring based on caterer's `auto_feature_reviews` setting
- Caterers can reply to reviews
- Reviews can be reported and hidden

### Package & Menu Management
- Packages have `draft` and `live` status
- Menu items can be `menu` or `addon` type
- Booking items snapshot prices at booking time

### User Roles
- **Client:** Can book caterers, leave reviews, send messages
- **Caterer:** Can offer packages/menus, receive bookings, reply to reviews
- **Admin:** System administration (approval, moderation)

### Caterer Approval
- New caterers start with status: `pending`
- Admin approves → status: `approved`
- Admin rejects → status: `rejected` + `rejection_reason`

---

## Indexes & Performance

### Critical Indexes
- `users.email` (UNIQUE) - Login authentication
- `users.phone` (UNIQUE) - Contact uniqueness
- `users.role` - Role-based queries
- `bookings.status` - Status filtering
- `bookings.event_date` - Date range queries
- `reviews.booking_id` (UNIQUE) - One review per booking
- `reviews(caterer_id, status)` - Public reviews lookup
- `reviews(caterer_id, is_featured)` - Featured reviews
- `saved_caterers(user_id, caterer_id)` (UNIQUE) - Prevent duplicates
- `sessions.last_activity` - Session cleanup

---

## Data Integrity

### Cascade Deletes
- Deleting a user (caterer) cascades to:
  - Their packages
  - Their menu items
  - Their bookings (as caterer)
  - Their reviews (as caterer)
  - Messages involving them

### Null on Delete
- Deleting a user (client) nullifies:
  - Their reviews (preserves review for caterer)
  - Their feedback (preserves feedback data)

### Soft Constraints
- Booking items snapshot data (name, price) to preserve history
- Reviews snapshot reviewer name and package name
- System feedback snapshots user role

---

## JSON Fields

### users.gallery
```json
["path/to/image1.jpg", "path/to/image2.jpg"]
```

### packages.includes
```json
["Item 1", "Item 2", "Item 3"]
```

### messages.attachments
```json
[
  {"name": "file.pdf", "path": "path/to/file.pdf", "size": 12345}
]
```

---

## Database Statistics (Typical)

| Table | Estimated Rows | Growth Rate |
|-------|---------------|-------------|
| users | 100-1000 | Low |
| bookings | 1000-10000 | High |
| booking_items | 5000-50000 | High |
| packages | 100-500 | Low |
| menu_items | 500-2000 | Medium |
| reviews | 500-5000 | Medium |
| messages | 5000-50000 | High |
| saved_caterers | 500-5000 | Medium |
| system_feedback | 100-1000 | Low |
| sessions | 50-500 | High (volatile) |
