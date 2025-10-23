# Waste2Product Platform

A comprehensive Laravel-based platform for waste management, recycling, and environmental sustainability education in Tunisia.

![Laravel](https://img.shields.io/badge/Laravel-11.x-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![License](https://img.shields.io/badge/License-MIT-green)

## Overview

Waste2Product is a multi-module platform designed to connect waste producers, recyclers, and environmental enthusiasts. The platform facilitates waste exchange, provides educational resources, organizes events, and fosters community engagement around recycling and sustainability.

## Features & Modules

### 1. Forum Module
Community discussion platform for environmental topics, recycling tips, and waste management best practices.

**Features:**
- Create and manage discussion topics
- Threaded comments and replies
- User reputation system
- Topic categories and tags
- Search functionality
- Moderation tools (BackOffice)

### 2. Dechets (Waste Items) Module
Core module for declaring and managing waste items available for recycling or reuse.

**Features:**
- Declare waste items with photos and descriptions
- **AI-Powered Image Recognition** - Filename-based keyword detection suggests waste categories automatically
- Category selection with visual card interface
- Tunisian location selector (24 governorates)
- Quantity and status tracking
- Search and filter capabilities
- Responsive grid/list views

**AI Recognition Keywords:**
- Plastique/Plastic, Métal/Metal, Papier/Paper, Verre/Glass
- Bois/Wood, Électronique/Electronic, Textile
- Works 100% offline - no API calls required!

### 3. Categories Module
Manage waste categories with ISO environmental certifications.

**Features:**
- CRUD operations for waste categories
- FontAwesome icon selection (15+ icon groups)
- Color customization with live preview
- Image upload support
- **AI Certification Suggestions** - Google Gemini AI analyzes category and automatically suggests relevant ISO standards
- Predefined certifications:
  - ISO 14001 (Environmental Management)
  - ISO 14040/14044 (Life Cycle Assessment)
  - FSC, PEFC (Forest Certifications)
  - Green Dot, Cradle to Cradle, EU Ecolabel
  - GRS, RCS (Recycled Standards)

### 4. Marketplace Module
Buy and sell recycled products and upcycled creations.

**Features:**
- Product listings with images
- Price management
- Categories and search
- Seller ratings
- Shopping cart functionality
- Order management

### 5. Events Module
Organize and participate in environmental events and cleanup activities.

**Features:**
- Event creation and management
- RSVP system
- Location-based events (Tunisian cities)
- Event categories (cleanup, workshop, conference)
- Calendar integration
- Participant tracking

### 6. Collection Points Module
Database of recycling centers and collection points across Tunisia.

**Features:**
- Map integration for collection points
- Location search by governorate
- Accepted materials listing
- Operating hours
- Contact information
- User reviews and ratings

### 7. Tutorials Module
Educational resources for recycling, composting, and DIY upcycling projects.

**Features:**
- Step-by-step tutorials
- Progress tracking system
- Difficulty levels (Beginner, Intermediate, Advanced, Expert)
- Categories (Recycling, Composting, Energy, Water, DIY)
- Video/image support
- Comments and ratings
- **Fun Environmental Facts** - Powered by Numbers API (free)
  - Auto-loads random environmental facts
  - Combines math trivia with sustainability data
  - "New Fact" button for fresh content
  - 100% free API, no authentication needed

**Tutorial Features:**
- Learning outcomes tracking
- Prerequisites listing
- Duration estimates
- Bookmark/save functionality
- Share on social media

## Technology Stack

### Backend
- **Framework:** Laravel 11.x
- **PHP:** 8.2+
- **Database:** SQLite (development), MySQL (production ready)
- **Authentication:** Laravel Breeze
- **File Storage:** Local/S3 compatible

### Frontend
- **CSS Framework:** Tailwind CSS 3.x
- **Icons:** FontAwesome 6.x
- **Build Tool:** Vite
- **JavaScript:** Vanilla JS (lightweight, no framework overhead)

### AI & APIs
- **Google Gemini API** - AI certification suggestions (free tier)
- **Numbers API** - Educational fun facts (100% free, no auth)
- **Filename Recognition** - Offline keyword-based category detection

## Installation

### Requirements
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- SQLite (development) or MySQL (production)

### Setup Steps

1. **Clone the repository**
```bash
git clone <repository-url>
cd waste2product-forum-module
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Environment configuration**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure Google Gemini API (Optional)**

Add to your `.env` file:
```env
GEMINI_API_KEY=your_api_key_here
GEMINI_MODEL=gemini-2.0-flash
```

Get your free API key at: https://makersuite.google.com/app/apikey

5. **Database setup**
```bash
touch database/database.sqlite
php artisan migrate --seed
```

6. **Build assets**
```bash
npm run dev
```

7. **Start the server**
```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Configuration

### Gemini AI Setup (Optional)
The AI certification suggestion feature uses Google's Gemini API. To enable:

1. Get a free API key from [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Add to `.env`:
```env
GEMINI_API_KEY=your_key_here
GEMINI_MODEL=gemini-2.0-flash
```

**SSL Certificate Fix (Windows):**
If you encounter SSL errors on Windows, the app automatically disables SSL verification for local development.

### Tunisian Locations
The platform includes all 24 Tunisian governorates:
- Tunis, Ariana, Ben Arous, Manouba
- Nabeul, Zaghouan, Bizerte
- Béja, Jendouba, Kef, Siliana
- Kairouan, Kasserine, Sidi Bouzid
- Sousse, Monastir, Mahdia, Sfax
- Gafsa, Tozeur, Kebili
- Gabès, Medenine, Tataouine

## Usage

### For Users (FrontOffice)
1. **Browse waste items** - Find recyclable materials
2. **Declare waste** - Upload photos, AI suggests categories
3. **Learn** - Follow tutorials with progress tracking
4. **Engage** - Participate in forums and events
5. **Shop** - Buy upcycled products

### For Administrators (BackOffice)
1. **Manage categories** - Use AI to suggest certifications
2. **Moderate content** - Approve/reject declarations
3. **Track analytics** - View usage statistics
4. **Manage users** - User roles and permissions

## Module Routes

### FrontOffice Routes
- `/` - Homepage
- `/dechets` - Browse waste items
- `/dechets/create` - Declare new waste
- `/tutorials` - Educational tutorials
- `/tutorials/{slug}` - View tutorial with fun facts
- `/forum` - Community discussions
- `/marketplace` - Recycled products marketplace
- `/events` - Environmental events
- `/collection-points` - Recycling centers

### BackOffice Routes (Admin)
- `/admin/categories` - Manage categories with AI
- `/admin/dechets` - Moderate waste declarations
- `/admin/tutorials` - Manage educational content
- `/admin/events` - Event management
- `/admin/users` - User administration

## AI Features

### 1. Category AI Certification Suggestion
**Location:** BackOffice Categories Create/Edit

Powered by Google Gemini AI to suggest relevant ISO environmental certifications based on category name and description.

**How it works:**
1. Enter category name (e.g., "Plastique recyclable")
2. Add description
3. Click "IA: Suggérer" button
4. AI analyzes and returns certifications
5. Auto-selects suggested certifications in dropdowns

### 2. Waste Image Recognition
**Location:** FrontOffice Dechets Create

Simple, offline keyword-based recognition using filename analysis.

**How it works:**
1. Upload image (e.g., `plastic_bottle.jpg`)
2. Click "Reconnaître la catégorie"
3. Detects keywords in filename
4. Shows suggestion with confidence
5. Apply to auto-select category

### 3. Environmental Fun Facts
**Location:** FrontOffice Tutorials Show

Displays random educational facts using Numbers API.

**How it works:**
1. Auto-loads on page visit
2. Combines math facts with environmental data
3. Click "New Fact" for random content
4. Fallback to local data if API unavailable

## Security

### Malware Protection
The platform includes route blocking for known malware trackers:
- `/hybridaction/*` routes return 403 Forbidden

### Data Validation
- Form input validation on all modules
- File upload restrictions (type, size)
- CSRF protection on all forms
- SQL injection prevention (Eloquent ORM)

## Performance

- **Lightweight:** No heavy JavaScript frameworks
- **Optimized:** Vite for fast asset compilation
- **Cached:** Query result caching
- **CDN Ready:** Asset organization for CDN deployment

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Credits

### APIs Used (All Free!)
- [Google Gemini AI](https://ai.google.dev/) - AI certification suggestions
- [Numbers API](http://numbersapi.com/) - Fun mathematical facts

### Technologies
- [Laravel](https://laravel.com) - PHP Framework
- [Tailwind CSS](https://tailwindcss.com) - CSS Framework
- [FontAwesome](https://fontawesome.com) - Icon library
- [Vite](https://vitejs.dev) - Build tool

## Support

For questions or issues:
- Open an issue on GitHub
- Contact: [your-email@example.com]

## Roadmap

- [ ] Mobile app (React Native)
- [ ] Real-time notifications
- [ ] Integration with waste collection companies
- [ ] Carbon footprint calculator
- [ ] Gamification and rewards system
- [ ] Multi-language support (Arabic, French, English)

---

Built with ♻️ for a sustainable Tunisia
