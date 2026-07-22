# Parish Website — Implementation Plan for Audit Fixes

**Repo:** `JamesMangao/parish_website` (Laravel 12, Blade, Tailwind CSS v4, Alpine.js, deployed on Render)  
**Audit Date:** July 18, 2026 (today) — Parish founded **Oct 16, 1983** → **42 years old** as of today  

---

## 📋 EXECUTIVE SUMMARY

The codebase is well-structured with a solid design system (Blue + Gold tokens in `app.css` `@theme` block). However, the audit revealed **7 critical gaps** that need fixing before production:

| # | Issue | Severity | Files Affected | Est. Effort |
|---|-------|----------|----------------|-------------|
| 1 | `.eyebrow` gold color fails WCAG AA (1.65:1) | **Critical** | `app.css`, 10+ view files | 15 min |
| 2 | Homepage stat "1 / Community" is meaningless | **High** | `home.blade.php` | 5 min |
| 3 | Duplicate Google Fonts requests (3 separate loads) | **High** | `public-layout.blade.php`, 5 view files | 20 min |
| 4 | Admin layout loads separate fonts (Playfair + Inter) | **Medium** | `admin-layout.blade.php` | 10 min |
| 5 | 3 form fields in `inquiry.blade.php` lack `aria-invalid`/`aria-describedby` | **High** | `inquiry.blade.php` | 20 min |
| 4 | Toast notifications missing `aria-atomic="true"` | **Medium** | `public-layout.blade.php` | 5 min |
| 5 | "1 / Community" stat + hardcoded "40+ Years" | **Medium** | `home.blade.php`, `about.blade.php` | 5 min |

---

## 🎯 PRIORITY 1 — CRITICAL FIXES (Do First)

### 1. Fix `.eyebrow` Contrast — **CRITICAL**
**File:** `resources/css/app.css` line 158-164  
**Current:** `color: var(--color-gold);` → `#F5C518` (1.65:1 on cream)  
**Fix:** Add `--color-gold-dark: #B8860B;` (4.6:1 on cream) and use it for `.eyebrow`

```css
/* In @theme block */
--color-gold-dark: #B8860B;

/* In .eyebrow rule */
.eyebrow {
  color: var(--color-gold-dark);
}
```

### 2. Homepage "1 / Community" Stat — **HIGH**
**File:** `resources/views/home.blade.php` line 48  
**Current:** `@foreach([['42+','Years of Service'],['7','Weekly Masses'],['1','Community']] as $stat)`  
**Fix:** Remove the third stat entirely → keep only 2 stats, increase gap to 60px

```php
@foreach([['42+','Years of Service'],['7','Weekly Masses']] as $stat)
```
And update gap: `gap:60px` in the parent `hero-stats-strip` div.

### 3. About Page "40+ Years" → **42+**
**File:** `resources/views/about.blade.php` line 670  
**Current:** `<p class="stat-number" data-target="40" data-suffix="+">40+</p>`  
**Fix:** `data-target="42"` and `>42+<`

---

## 🎯 PRIORITY 2 — FONT CONSOLIDATION (High Impact)

### 4. Public Layout — Single Font Request
**File:** `resources/views/components/public-layout.blade.php` lines 10-16  
**Current:** 3 separate `<link>` requests (Playfair Display, Source Sans 3, Cinzel)  
**Fix:** Single combined request for Cormorant Garamond + Cinzel + Jost

```html
<!-- Replace lines 10-16 with: -->
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=Cinzel:wght@400;500;600&family=Jost:wght@300;400;500;600&display=swap">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=Cinzel:wght@400;500;600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
```

### 5. Remove Per-Page Font Duplicates
**Files:** `events.blade.php`, `donate.blade.php`, `mass-schedule.blade.php`, `submit-intention.blade.php`  
**Action:** Delete the `<link rel="preconnect">` + `<link href="...Cormorant+Garamond...Cinzel...Jost...">` blocks from each file's `<x-slot name="meta">` — they're now loaded once in `public-layout.blade.php`.

### 6. Admin Layout — Inherit Public Fonts
**File:** `resources/views/components/admin-layout.blade.php` lines 10-13  
**Current:** Loads Playfair Display + Inter separately  
**Fix:** Remove the `<link>` tags entirely — admin inherits Cormorant/Jost/Cinzel from `app.css` `@theme` block.

---

## 🎯 PRIORITY 3 — FORM ACCESSIBILITY (High Impact)

### 6. Toast Notifications — Add `aria-atomic="true"`
**File:** `resources/views/components/public-layout.blade.php` line 89-102  
**Add to toast container div:**
```blade
aria-atomic="true"
```

### 7. Inquiry Form — 3 Fields Missing ARIA
**File:** `resources/views/inquiry.blade.php`  
**Fields:** `fullName`, `email`, `phone` (lines 559-596)

**For each field, add to input:**
```blade
aria-invalid="@error('fieldName') true @else false @enderror"
aria-describedby="fieldName-error"
value="{{ old('fieldName') }}"

@error('fieldName')
    <p id="fieldName-error" class="text-red-600 text-sm mt-1" role="alert">{{ $message }}</p>
@enderror
```

### 7b. Select Dropdowns — Add `aria-describedby`
**File:** `inquiry.blade.php` (inquiryType, preferredDate) + `submit-intention.blade.php` (intentionType, massTime, preferredDate)

**Add to each `<select>`:**
```blade
aria-describedby="fieldName-error"
```

And add error message `<p id="fieldName-error" ...>` after each select.

---

## 🎯 PRIORITY 4 — ADMIN DASHBOARD POLISH

### 8. Dashboard Stat Cards — Responsive Grid
**File:** `resources/views/admin/dashboard.blade.php` lines 147-175  
**Current:** `sm:grid-cols-2 lg:grid-cols-3` → 4th card orphaned  
**Fix:** `grid-cols-1 sm:grid-cols-2 lg:grid-cols-4`

### 9. Stat Cards — Unified Style + "Pending Intentions" Highlight
**File:** `resources/views/admin/dashboard.blade.php` lines 150-190  
**Changes:**
- Remove `border-l-4 border-l-accent` / `border-l-blue-600` / `border-l-purple-600` from all cards
- Use single style: `border border-border shadow-sm`
- **Only** "Pending Intentions" card gets: `border-l-4 border-l-amber-500 bg-amber-50/50`

### 10. Notification Bell — Consolidate to One
**File:** `resources/views/components/admin-layout.blade.php` lines 152-180 + 165-190  
**Issue:** Two bells (lines 152-162 and 165-190)  
**Fix:** Remove the first bell (lines 152-162) entirely — keep the second one with full dropdown.

### 11. Mass Presentation Panel → Card Style
**File:** `resources/views/admin/dashboard.blade.php` lines 210-240  
**Changes:**
- Remove gradient background, use `bg-white border border-border rounded-2xl shadow-sm`
- "Generate PPT" → `gold-btn` utility class
- "Quick Preview" → `ghost-btn` utility class (or secondary style)

### 11b. Chart Zero-Week Bug
**File:** Controller for `admin.preview-ppt` (check `app/Http/Controllers/Admin/DashboardController.php` or similar)  
**Issue:** Weeks with zero submissions excluded from chart data  
**Fix:** Ensure query returns all 8 weeks with `0` for missing weeks.  
**If controller not found:** Add `// TODO` comment in dashboard view.

### 11c. Sidebar "SYSTEM" Divider Contrast
**File:** `resources/views/components/admin-layout.blade.php` line 104-105  
**Current:** `text-primary-foreground/40` → too dim on navy  
**Fix:** `text-primary-foreground/70` or `text-gold/80`

---

## 🎯 PRIORITY 5 — PERFORMANCE & SEO

### 12. Hero Images — `fetchpriority="high"` + `decoding="async"`
**Files:** `home.blade.php` line 12, `about.blade.php` hero image  
**Add to hero `<img>`:**
```html
fetchpriority="high" decoding="async"
```

### 12. Supabase Image Transforms
**All views using Supabase images**  
Add transform params: `?width=1200&quality=75&format=webp`  
Example:
```blade
{{ \Storage::disk('supabase')->url('assets/bg.webp?width=1200&quality=75&format=webp') }}
```

### 13. Canonical URLs + Structured Data
**Files:** `public-layout.blade.php`, `events.blade.php`, `events-show.blade.php`  
Add to `<head>`:
```html
<link rel="canonical" href="{{ url()->current() }}">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Church",
  "name": "Sto. Rosario Parish",
  "address": { "@type": "PostalAddress", "streetAddress": "Pacita Complex 1", "addressLocality": "San Pedro", "addressRegion": "Laguna", "addressCountry": "PH" },
  "telephone": "+63288692742",
  "url": "{{ url('/') }}"
}
</script>
```
Events pages: add `Event` schema with `startDate`, `endDate`, `location`.

### 13. Sitemap + Robots.txt
**Action:** Install `spatie/laravel-sitemap`, configure, add `robots.txt` to `public/`.

---

## 📦 WORKSTREAM BREAKDOWN (for parallel execution)

| Workstream | Files | Agent |
|------------|-------|-------|
| **WS1: Design Tokens** | `resources/css/app.css` | Agent 1 |
| **WS2: Public Layout & Fonts** | `public-layout.blade.php`, 5 view files | Agent 2 |
| **WS3: Admin Layout & Dashboard** | `admin-layout.blade.php`, `admin/dashboard.blade.php` | Agent 3 |
| **WS4: Homepage & About** | `home.blade.php`, `about.blade.php` | Agent 4 |
| **WS5: Form Accessibility** | `inquiry.blade.php`, `submit-intention.blade.php` | Agent 5 |

---

## ✅ VERIFICATION CHECKLIST (post-merge)

```bash
# 1. Build passes
npm run build

# 2. Lint passes
npm run lint        # if configured
./vendor/bin/pint   # Laravel Pint

# 3. Test suite passes
php artisan test

# 4. Manual checks
# - .eyebrow contrast (DevTools → Accessibility pane)
# - Form submission → error messages announced by NVDA/VoiceOver
# - Toast announced by screen reader
# - 42+ stats on home & about pages
# - Single Google Fonts request in Network tab
# - Admin dashboard: 4 cards in row on lg, 2 on sm
# - Single notification bell in admin header
# - Toast has aria-atomic="true"
```

---

## 🚀 EXECUTION ORDER

1. **Start with WS1 + WS2** (design tokens + fonts) — affects all pages
2. **Then WS4** (homepage/about stats + hero images) — visible changes
3. **Then WS5** (form a11y) — highest user impact
4. **Then WS3** (admin polish) — internal tool
4. **Then remaining perf/SEO** (images, schema, sitemap)

**Total estimated effort:** ~2-3 developer days for all Critical/High items. Quick Wins = ~20 minutes total.

---

**Ready to proceed when you give the go-ahead.** Which workstream should I start with?
