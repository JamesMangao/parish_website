# Parish Website - Sto. Rosario Parish

A full-featured Catholic parish management web application built for **Sto. Rosario Parish - Pacita**, San Pedro, Laguna. Provides mass schedules, mass intention submissions, event management, gallery, AI-powered chatbot, and a comprehensive admin panel.

## Features

### Public-Facing
- **Home Page** - Hero section, next mass countdown, quick actions, events & announcements feed
- **Mass Schedule** - Weekly schedule display with iCal export
- **Mass Intention Submission** - Online form with email notifications and reference tracking
- **Intention Tracking** - Look up intention status by reference number
- **Events** - Event listing with calendar view and detail pages
- **Gallery** - Photo/video album browsing with video highlights
- **Daily Readings** - Auto-preloaded daily Catholic readings (English & Tagalog)
- **Bulletin Board** - Downloadable parish bulletins
- **Inquiry Form** - Contact/inquiry submission with accept/decline workflow
- **Donation Page** - GCash, Maya, and bank transfer information
- **AI Chatbot** - AI-powered concierge with live agent handoff capability

### Admin Panel
- **Dashboard** - Overview stats and activity logs
- **Mass Intentions** - CRUD with batch status updates, email notifications
- **Announcements** - CRUD with recruitment field support
- **Events** - CRUD with multiple time slots
- **Gallery** - Album management, image uploads, video highlights
- **Schedules** - Mass schedule management
- **Inquiries** - Review, accept, or decline inquiries
- **Live Chat** - Real-time chat monitoring and agent replies
- **Bulletins** - Upload and manage downloadable bulletins
- **Users** - User management with role-based access (super_admin, staff, soccom)
- **Settings** - Global site settings management
- **PPT Tools** - Auto-generate PowerPoint presentations from mass schedules
- **Google Slides** - Direct Google Slides integration via OAuth

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Tailwind CSS v4, Alpine.js, Vite 7 |
| Database | SQLite (dev) / MySQL (production) |
| Storage | Supabase (S3-compatible) |
| AI | Groq / OpenRouter API |
| Integrations | Google Drive/Slides API |

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- npm

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/JamesMangao/parish_website
   cd parish-website
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies and build assets**
   ```bash
   npm install
   npm run build
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your `.env`** with the following keys:
   - Database settings (SQLite by default)
   - `SUPABASE_*` keys for cloud storage
   - `GROQ_API_KEY` or `OPENROUTER_API_KEY` for AI chatbot
   - `GOOGLE_DRIVE_FOLDER_ID` and `GOOGLE_SHARE_EMAIL` for Slides integration
   - `GCASH_NUMBER`, `MAYA_NUMBER`, `BANK_DETAILS` for donation info

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Create an admin user**
   ```bash
   php artisan tinker
   ```
   ```php
   User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password'), 'role' => 'super_admin', 'is_active' => true]);
   ```

8. **Start the development server**
   ```bash
   composer dev
   ```
   This runs the artisan server, queue worker, pail log viewer, and Vite dev server concurrently.

## Docker

A Dockerfile is included for containerized deployment:

```bash
docker build -t parish-pal .
docker run -p 10000:10000 parish-pal
```

The app will be available at `http://localhost:10000`.

## Testing

```bash
php artisan test
```

## Project Structure

```
app/
├── Console/Commands/       # Artisan commands (readings preload, image migration)
├── Http/Controllers/       # 22 controllers (public + admin)
├── Http/Middleware/         # Role-based access control
├── Http/Requests/          # Form request validation
├── Mail/                   # Email templates
├── Models/                 # 15 Eloquent models
├── Notifications/          # Database notifications
├── Services/               # AI service, activity logging
resources/
├── css/                    # Tailwind v4 + custom parish styles
├── js/                     # Alpine.js bootstrap
└── views/
    ├── admin/              # Admin panel views
    ├── components/         # Reusable layout components
    ├── emails/             # Email blade templates
    └── *.blade.php         # Public page views
routes/
├── web.php                 # All routes (public + admin)
└── console.php             # Scheduled tasks
database/
└── migrations/             # 37 migration files
```

## Roles

| Role | Permissions |
|------|------------|
| `super_admin` | Full access to all features |
| `staff` | Mass intentions, inquiries, PPT/Slides tools |
| `soccom` | Announcements, events, gallery, schedules, inquiries, live chat |

## License

MIT License
