# Parish Website - Sto. Rosario Parish

A full-featured Catholic parish management web application built for **Sto. Rosario Parish - Pacita**, San Pedro, Laguna. Provides mass schedules, mass intention submissions, event management, gallery, AI-powered chatbot, and a comprehensive admin panel.

## Features

### Public-Facing
- **Home Page** - Hero section, next mass countdown, quick actions, events & announcements feed
- **Mass Schedule** - Weekly schedule display with iCal export
- **Mass Intention Submission** - Online form with `INT-YYYY-XXXX` auto-generated reference IDs, email notifications, and tracking
- **Intention & Inquiry Tracking** - Search and look up intention or inquiry status by Reference Number (`INT-...`/`INQ-...`) or Email Address
- **Events** - Event listing with calendar view and detail pages
- **Gallery** - Photo/video album browsing with video highlights and queued background uploads (`UploadGalleryMediaJob`)
- **Daily Readings** - Auto-preloaded daily Catholic readings (English & Tagalog) via USCCB (primary) or Evangelizo (fallback), with `GET /api/readings/today?language=EN|TG&refresh=true`
- **Bulletin Board** - Downloadable parish bulletins
- **Inquiry Form** - Contact/inquiry submission with `INQ-YYYY-XXXX` reference IDs and accept/decline workflow
- **Donation Page** - GCash, Maya, QR Ph, and card payment integration via PayMongo checkout with automated email receipts (`DonationReceiptMail`)
- **AI Chatbot** - AI-powered concierge with live agent handoff capability
- **Privacy & Legal Pages** - Privacy Policy (`/privacy-policy`) and Terms & Conditions (`/terms`) linked from the footer
- **Consent-Gated Analytics** - Cookie banner (Essential Only / Accept All) that loads GA4 only after explicit opt-in
- **Form Consent** - Mandatory consent checkboxes on the inquiry, mass intention, and donation forms, validated both client- and server-side

### Admin Panel
- **Dashboard** - Overview stats, activity logs, and real-time Server-Sent Events (SSE) notification streaming (`/internal/notifications/stream`)
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

## Legal, Compliance & Accessibility

| Area | Implementation |
|------|----------------|
| Cookie consent | Alpine `cookieConsent()` component in `components/public-layout.blade.php` (bottom banner, `role="region"`). Shown until the visitor chooses; choice persisted in `localStorage` key `parish_cookie_consent` |
| Analytics opt-in | GA4 is **only** injected after "Accept All" — gtag script appended with `anonymize_ip: true`, guarded by `window.__gaLoaded`. "Essential Only" sets consent without loading anything |
| Analytics config | `GTM_ID` env var → `config/services.php` (`analytics.gtm_id`). The "Accept All" button is hidden when unset (see GA4 Measurement ID setup below) |
| Form consent | `consent` field added to inquiry, mass intention, and donation forms with `required\|accepted` validation in `InquiryController`, `IntentionController`, and `DonationController` plus custom error messages; front-end gating too |
| Privacy pages | `privacy-policy.blade.php` and `terms.blade.php` behind `/privacy-policy` and `/terms` routes, linked in the footer Quick Links; consent labels link to both |
| Embed privacy | Third-party embeds hardened: YouTube uses `youtube-nocookie.com/embed` (`live-mass-slot`, `album-show`) and the Facebook plugin sets `dnt=1` |
| Accessibility | Global `:focus-visible` outlines (`resources/css/app.css`), pre-existing skip-link in the public layout, `aria-label` on the back-to-top button, corrected heading hierarchy, and WCAG AA contrast fixes (stat labels, eyebrows, chatbot timestamps) |

#### GA4 Measurement ID setup

1. Google Analytics → Admin → **Create property** (e.g. "Sto. Rosario Parish Website").
2. Admin → **Data Streams → Add stream → Web** → enter `https://sto-rosario-parish.onrender.com`.
3. Copy the **Measurement ID** (format `G-XXXXXXXXXX`) from the stream page — this is your `GTM_ID`.
4. On Render: service **Environment → Add Environment Variable** → `GTM_ID` = your ID → redeploy.
   *(GTM_ID is read at runtime — no `config:cache` in the deploy path, so no file changes needed. Never put it in `.env` for production; `.env` is gitignored and not deployed.)*

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Tailwind CSS v4, Alpine.js, Vite 7 |
| Database | SQLite (dev) / PostgreSQL (production) |
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
   - `GTM_ID` (optional) for consent-gated Google Analytics — see [GA4 setup](#ga4-measurement-id-setup)

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
