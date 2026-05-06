# Entity Relationship Diagram

```mermaid
erDiagram
    USERS ||--o{ BOOKINGS : "creates"
    USERS ||--o{ MESSAGES : "sends"
    USERS ||--o{ PACKAGES : "offers"
    USERS ||--o{ MENU_ITEMS : "creates"
    USERS ||--o{ REVIEWS : "receives"
    USERS ||--o{ REVIEWS : "writes"
    BOOKINGS ||--o{ REVIEWS : "generates"

    USERS {
        bigint id PK
        string name
        string email UK
        string role
        string phone
        string business_name
        string barangay
        timestamp email_verified_at
        string password
        string remember_token
        boolean auto_feature_reviews
        timestamp created_at
        timestamp updated_at
    }

    BOOKINGS {
        bigint id PK
        bigint user_id FK
        bigint caterer_id FK
        string event_title
        date event_date
        int guests
        enum status
        timestamp created_at
        timestamp updated_at
    }

    MESSAGES {
        bigint id PK
        bigint user_id FK
        bigint caterer_id FK
        text body
        boolean is_read
        enum sender
        timestamp created_at
        timestamp updated_at
    }

    PACKAGES {
        bigint id PK
        bigint caterer_id FK
        string name
        text description
        decimal price
        int min_guests
        text includes
        enum status
        string category
        timestamp created_at
        timestamp updated_at
    }

    MENU_ITEMS {
        bigint id PK
        bigint caterer_id FK
        string name
        text description
        decimal price
        string unit
        enum type
        string category
        enum status
        string image_path
        timestamp created_at
        timestamp updated_at
    }

    REVIEWS {
        bigint id PK
        bigint booking_id FK UK
        bigint client_id FK
        bigint caterer_id FK
        string reviewer_name
        string package_name
        string title
        text body
        tinyint rating
        enum status
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
```

## Key Relationships

- **Users** (caterers & clients) - central entity with role-based access
- **Bookings** - connects clients to caterers for events
- **Messages** - communication between clients and caterers
- **Packages** - catering packages offered by caterers
- **Menu Items** - individual menu items and add-ons (type: menu/addon)
- **Reviews** - feedback on bookings with auto-feature capability

## Table Details

### Users
- Stores both clients and caterers (role-based)
- Email is unique identifier
- Caterers have business_name and barangay
- auto_feature_reviews controls automatic review featuring

### Bookings
- Links clients (user_id) to caterers (caterer_id)
- Tracks event details and guest count
- Status: pending, confirmed, cancelled, completed

### Messages
- Bidirectional communication between clients and caterers
- Tracks read status and sender type

### Packages
- Catering packages offered by caterers
- Includes pricing and minimum guest requirements
- Status: live or draft

### Menu Items
- Individual menu items and add-ons
- Type: menu or addon
- Status: live or draft
- Includes category and pricing per unit

### Reviews
- Feedback on bookings with optional client association
- Auto-featuring based on caterer preferences
- Caterer can reply to reviews
- Reporting system for inappropriate reviews
