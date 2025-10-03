# Waste2Product 🌱♻️

A comprehensive Laravel-based platform for waste management, recycling projects, and environmental events. Transform waste into valuable resources while building a sustainable community.

---

## 📋 Table of Contents

- [About](#about)
- [Core Modules](#core-modules)
- [Tech Stack](#tech-stack)
- [Features](#features)
- [Potential Intermediate-Level Features](#potential-intermediate-level-features)
- [Installation](#installation)
- [Database Schema](#database-schema)
- [Usage](#usage)
- [Contributing](#contributing)

---

## 🌍 About

*Waste2Product* is an e-waste management and recycling platform (not an e-commerce system) that connects users who want to:
- Declare and manage waste materials
- Find creative DIY projects to repurpose waste
- Participate in environmental events and workshops
- Learn through tutorials and share knowledge
- Build a community around sustainability

---

## 🎯 Core Modules

### *Module 1: Waste Management (Déchets)* 🗑️

Manage and declare waste materials for recycling or repurposing.

*Current Features:*
- ✅ Full CRUD operations (Create, Read, Update, Delete)
- ✅ Waste categorization system
- ✅ Reservation system for available waste
- ✅ Location-based filtering (Tunisian cities)
- ✅ Photo uploads for waste items
- ✅ Status tracking (available, reserved, collected)
- ✅ View counter for popularity tracking
- ✅ Search and advanced filtering
- ✅ "My Waste" dashboard for users
- ✅ Similar waste suggestions
- ✅ Contact information management

*Key Files:*
- Model: app/Models/Dechet.php
- Controller: app/Http/Controllers/Frontoffice/DechetController.php
- Request Validation: app/Http/Requests/DechetRequest.php
- Migration: database/migrations/2025_10_01_203443_create_dechets_table.php

---

### *Module 2: DIY Recycling Projects* 🔨

Discover and share creative projects to transform waste into valuable products.

*Current Features:*
- ✅ Full CRUD operations
- ✅ Step-by-step project instructions (ProjectStep model)
- ✅ Difficulty levels (facile, intermédiaire, difficile)
- ✅ Estimated time tracking
- ✅ Impact score system
- ✅ Category-based organization
- ✅ Photo uploads
- ✅ Status management (draft, published, archived)
- ✅ View counter
- ✅ Favorites system (placeholder)
- ✅ Rating and reviews structure (fields present)
- ✅ Search and filtering (by difficulty, category, duration)
- ✅ "My Projects" dashboard
- ✅ Similar projects suggestions
- ✅ Materials and tools lists per step

*Key Files:*
- Models: app/Models/Project.php, app/Models/ProjectStep.php
- Controller: app/Http/Controllers/Frontoffice/ProjectController.php
- Request Validation: app/Http/Requests/ProjectRequest.php
- Migrations:
  - database/migrations/2025_10_02_170221_create_projects_table.php
  - database/migrations/2025_10_02_170235_create_project_steps_table.php

---

### *Module 3: Events & Workshops* 📅

Organize and participate in environmental events, workshops, and community activities.

*Current Features:*
- ✅ Full CRUD operations
- ✅ Multiple event types:
  - 🛠️ Workshops
  - 🌱 Collection drives
  - 📚 Training sessions
  - ☕ Repair Cafés
- ✅ Participant registration system
- ✅ Maximum capacity management
- ✅ Event status tracking (draft, published, ongoing, completed, cancelled)
- ✅ Price management (free/paid events)
- ✅ Date and time management with countdown
- ✅ Location-based filtering (Tunisian cities)
- ✅ Participant feedback and rating system
- ✅ Event duplication feature
- ✅ Participant export to CSV
- ✅ Email notification system (placeholder)
- ✅ "My Events" dashboard (participating & organizing)
- ✅ Attendance tracking (registered, confirmed, attended, absent)
- ✅ Fill percentage and remaining seats calculation
- ✅ Similar events suggestions
- ✅ Organizer statistics and analytics

*Key Files:*
- Models: app/Models/Event.php, app/Models/Participant.php
- Controller: app/Http/Controllers/Frontoffice/EventController.php
- Request Validation: app/Http/Requests/EventRequest.php
- Migrations:
  - database/migrations/2025_10_02_215742_create_events_table.php
  - database/migrations/2025_10_02_215827_create_participants_table.php

---

### *Module 4: Tutorials* 📚

Integrated learning system through step-by-step project instructions.

*Current Features:*
- ✅ Integrated within Projects module as ProjectStep model
- ✅ Step-by-step instructions
- ✅ Materials needed per step
- ✅ Tools required per step
- ✅ Duration estimation per step
- ✅ Ordered step navigation

*Note:* Tutorials are implemented as part of the Projects module rather than a standalone feature. Each project contains multiple steps that serve as tutorials.

*Key Files:*
- Model: app/Models/ProjectStep.php
- Migration: database/migrations/2025_10_02_170235_create_project_steps_table.php

---

### *Module 5: Reviews & Testimonials* ⭐

Community feedback and rating system (partially implemented).

*Current Features:*
- ⚠️ Participant event ratings (1-5 stars)
- ⚠️ Participant event feedback/testimonials
- ⚠️ Project rating fields (average_rating, reviews_count) - structure exists but no Review model

*Implementation Status:*
- Event reviews: ✅ Fully functional (via Participant model)
- Project reviews: ⚠️ Database fields exist but needs Review model and controller
- General testimonials: ❌ Not implemented

*Existing Review Features:*
- Rating system in Participant model (for events)
- Feedback text field
- Rating stars display helper
- Scopes for filtering rated/reviewed participants

*Key Files:*
- Partial implementation in: app/Models/Participant.php
- Project rating fields in: app/Models/Project.php

---

### *User Management* 👥

Complete user authentication and profile system.

*Current Features:*
- ✅ User registration and authentication
- ✅ User profiles with avatar upload
- ✅ Admin role system (is_admin)
- ✅ CO2 saved tracking (total_co2_saved)
- ✅ Projects completed counter
- ✅ User contact information (phone, address, city)
- ✅ Relationships:
  - User → Wastes (one-to-many)
  - User → Projects (one-to-many)
  - User → Events (one-to-many as organizer)
  - User → Event Participations (through Participant model)

*Key Files:*
- Model: app/Models/User.php
- Migration: database/migrations/0001_01_01_000000_create_users_table.php

---

## 🛠️ Tech Stack

- *Framework:* Laravel 12.x
- *PHP:* ^8.2
- *Database:* MySQL/PostgreSQL (configurable)
- *Frontend:* Blade Templates
- *Authentication:* Laravel's built-in authentication
- *File Storage:* Local storage for uploads
- *Testing:* PHPUnit
- *Development Tools:*
  - Laravel Tinker
  - Laravel Pail
  - Laravel Sail
  - Laravel Pint (code styling)

*Key Dependencies:*
{
  "laravel/framework": "^12.0",
  "laravel/tinker": "^2.10.1"
}

---

## ✨ Features

### General Features

- 🔍 *Advanced Search & Filtering* - Search across all modules with multiple filters
- 📱 *Responsive Design* - Works on desktop, tablet, and mobile (Blade templates)
- 🏷️ *Categorization System* - Shared categories across wastes and projects
- 📊 *Statistics Dashboard* - Track impact, participation, and engagement
- 🔐 *Role-Based Access* - User and Admin roles
- 📸 *Image Upload* - Photo support for wastes, projects, and events
- 🇹🇳 *Tunisia-Focused* - Tunisian cities and French language support

### BackOffice (Admin Panel)

- ✅ Separate admin routes under /admin prefix
- ✅ Admin controllers for Wastes, Projects, Events
- ✅ Participant management for events
- ✅ CRUD operations with admin oversight

*Admin Controllers:*
- app/Http/Controllers/Backoffice/Dechet1Controller.php
- app/Http/Controllers/Backoffice/Project1controller.php
- app/Http/Controllers/Backoffice/Event1controller.php

---

## 🚀 Potential Intermediate-Level Features

### Module 1: Waste Management Enhancements
*🔹 Notifications System**
- Email/SMS notifications when waste is reserved
- Alerts for waste expiration or pickup reminders
- Notification preferences per user
*🔹 QR Code Generation**
- Generate QR codes for each waste item
- Quick scanning for waste pickup and tracking
*🔹 Waste Collection Scheduling**
- Calendar integration for pickup scheduling
- Automated reminders for scheduled pickups
- Route optimization for collectors
*🔹 Waste Analytics Dashboard**
- Charts showing waste types over time
- Location-based heat maps
- Monthly waste reduction statistics
*🔹 Gamification**
- Points system for declaring waste
- Badges for active contributors
- Leaderboards for top waste recyclers
*🔹 Image Recognition (AI)**
- Auto-categorize waste from uploaded photos
- Suggest appropriate categories using ML

---

### Module 2: Projects Enhancements
*🔹 Project Reviews & Comments**
- Full Review model implementation
- Comment threads on projects
- Reply to comments functionality
*🔹 Project Completion Tracking**
- Mark projects as completed by users
- Progress tracking per step
- Completion certificates
*🔹 Video Tutorials**
- Embed YouTube/Vimeo videos per step
- Video upload support
- Video thumbnail generation
*🔹 Materials Marketplace Integration**
- Link materials needed to available wastes
- Suggest matching wastes for project materials
- Shopping list generator
*🔹 Project Forking/Remixing**
- Allow users to create variations of projects
- Version control for project updates
- Original project attribution
*🔹 Difficulty Calculator**
- Auto-suggest difficulty based on steps, materials, and time
- Skill level recommendations
*🔹 3D Model Support**
- Upload 3D models for projects (STL files)
- Interactive 3D viewer in browser

---

### Module 3: Events Enhancements
*🔹 Calendar View**
- Full calendar interface for events
- Month/Week/Day views
- iCal export for personal calendars
*🔹 Event Check-In System**
- QR code-based check-in at events
- Real-time attendance tracking
- Mobile check-in app
*🔹 Waitlist Management**
- Automatic waitlist when events are full
- Auto-promote from waitlist when spots open
- Waitlist notifications
*🔹 Recurring Events**
- Support for weekly/monthly recurring events
- Series management
- Bulk event creation
*🔹 Live Event Features**
- Live streaming integration (YouTube, Zoom)
- Chat functionality during online events
- Q&A sessions
*🔹 Post-Event Reports**
- Automated attendance reports
- Participant feedback analysis
- Photo galleries from events
*🔹 Event Certificates**
- Auto-generate participation certificates
- PDF download with QR verification
- Certificate templates per event type

---

### Module 4: Tutorials Enhancements
*🔹 Standalone Tutorial System**
- Separate Tutorial model (not just project steps)
- Tutorial categories (beginner, intermediate, advanced)
- Skill-based filtering
*🔹 Interactive Tutorials**
- Checkboxes to mark steps completed
- Progress saving per user
- Timer for each step
*🔹 Tutorial Series**
- Group related tutorials into courses
- Prerequisites system
- Learning paths
*🔹 Quiz System**
- Knowledge checks after tutorials
- Certificates upon passing quizzes
- Skill badges

---

### Module 5: Reviews & Testimonials Enhancements
*🔹 Full Review System Implementation**
- Create Review model for projects
- Photo uploads in reviews
- Helpful/Unhelpful voting on reviews
*🔹 Featured Testimonials**
- Admin-curated testimonials on homepage
- Video testimonials support
- Success stories section
*🔹 Review Moderation**
- Admin approval workflow
- Report inappropriate reviews
- Review editing/deletion by users
*🔹 Verified Reviews**
- Only users who completed projects can review
- Verified badge for legitimate reviews
- Review authenticity scoring

---

### User Management Enhancements
*🔹 Social Login**
- Login with Google, Facebook, GitHub
- OAuth integration
- Social profile syncing
*🔹 User Profiles Enhancement**
- Public profile pages
- Portfolio of completed projects
- Follower/Following system
*🔹 Achievements & Badges**
- Achievement system based on activities
- Display badges on profile
- Shareable achievement cards
*🔹 Impact Dashboard**
- Personal CO2 savings calculator
- Waste diverted from landfills
- Environmental impact visualization
*🔹 Email Verification**
- Implement email verification on signup
- Forgot password workflow
- 2FA (Two-Factor Authentication)
*🔹 User Messaging System**
- Direct messages between users
- Inbox/Sent messages
- Message notifications

---

### General Platform Enhancements
*🔹 API Development**
- RESTful API for mobile apps
- API authentication (Laravel Sanctum/Passport)
- API documentation (Swagger/OpenAPI)
*🔹 Multi-Language Support**
- Arabic and English translations
- Language switcher
- RTL support for Arabic
*🔹 Advanced Search**
- Elasticsearch integration
- Full-text search
- Search suggestions/autocomplete
*🔹 Reporting & Analytics**
- Admin analytics dashboard
- User behavior tracking
- Google Analytics integration
*🔹 Export Features**
- Export data to CSV/Excel
- PDF report generation
- Data backup system
*🔹 Mobile App**
- React Native or Flutter mobile app
- Push notifications
- Offline mode support
*🔹 Payment Integration**
- Stripe/PayPal for paid events
- Donation system
- Subscription tiers
*🔹 Email Campaigns**
- Newsletter system
- Event reminders via email
- Marketing campaigns (Mailchimp integration)
*🔹 Social Sharing**
- Share projects/events on social media
- Open Graph meta tags
- Social media preview cards
*🔹 SEO Optimization**
- Meta descriptions and keywords
- Sitemap generation
- Schema.org markup for rich snippets

---

## 📦 Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL or PostgreSQL
- Node.js & NPM (for frontend assets)

### Setup Instructions

1. *Clone the repository*
   
   git clone https://github.com/yourusername/waste2product.git
   cd waste2product
   

2. *Install dependencies*
   
   composer install
   npm install
   

3. *Environment configuration*
   
   cp .env.example .env
   php artisan key:generate
   

4. *Configure database*

   Edit .env file:
   
env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=waste2product
   DB_USERNAME=root
   DB_PASSWORD=
   

5. *Run migrations and seeders*
   
   php artisan migrate
   php artisan db:seed
   

6. *Create storage link*
   
   php artisan storage:link
   

7. *Build frontend assets*
   
   npm run dev
   

8. *Start the development server*
   
   php artisan serve
   

9. *Access the application*
   - Frontend: http://localhost:8000
   - Admin: http://localhost:8000/admin

### Default Test Users

After seeding, you can log in with:
- *User ID 4:* Test user for waste management
-*User ID 6:** Test user for projects and events

---

## 🗄️ Database Schema

### Main Tables

- *users* - User accounts and profiles
- *categories* - Shared categories for wastes and projects
- *dechets* - Waste items
- *projects* - DIY recycling projects
- *project_steps* - Step-by-step instructions for projects
- *events* - Environmental events and workshops
- *participants* - Event registrations and attendance

### Key Relationships

users
├── hasMany → dechets
├── hasMany → projects
├── hasMany → events (as organizer)
└── hasMany → participants

categories
├── hasMany → dechets
└── hasMany → projects

projects
├── belongsTo → user
├── belongsTo → category
└── hasMany → project_steps

events
├── belongsTo → user (organizer)
└── hasMany → participants

participants
├── belongsTo → event
└── belongsTo → user

---

## 🎯 Usage

### FrontOffice Routes

*Waste Management:*
- GET /dechets - List all wastes
- GET /dechets/create - Create new waste
- GET /dechets/mesdechets - My wastes dashboard
- POST /dechets/{id}/reserve - Reserve a waste item

*Projects:*
- GET /projects - List all projects
- GET /projects/create - Create new project
- GET /projects/mesprojets - My projects dashboard
- POST /projects/{id}/favorite - Toggle favorite

*Events:*
- GET /events - List all events
- GET /events/create - Create new event
- GET /events/myEvents - My events dashboard
- POST /events/{id}/register - Register for event
- DELETE /events/{id}/unregister - Cancel registration

### BackOffice Routes (Admin)

All admin routes are prefixed with /admin:

- /admin/dechets - Manage all wastes
- /admin/projects - Manage all projects
- /admin/events - Manage all events
- /admin/events/{id}/participants - Manage event participants

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (git checkout -b feature/AmazingFeature)
3. Commit your changes (git commit -m 'Add some AmazingFeature')
4. Push to the branch (git push origin feature/AmazingFeature)
5. Open a Pull Request

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 🙏 Acknowledgments

- Laravel Framework Team
- All contributors to this project
- The open-source community

---

## 📞 Contact

For questions or support, please open an issue on GitHub.

---

*Built with ❤️ for a sustainable future*