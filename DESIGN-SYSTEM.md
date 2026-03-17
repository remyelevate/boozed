# Boozed design system

Reference for building sections and UI. All values are available in Tailwind unless noted.

---

## Section layout

Use this for all page sections.

| Token | Value |
|-------|--------|
| **Max width** | 1920px |
| **Side padding** | 68px |
| **Vertical padding** | 48px (mobile) / 128px (md and up) |

**Tailwind (add to section wrapper):**
- `max-w-section mx-auto px-section-x py-section-y`

`py-section-y` is responsive: 48px (3rem) on viewports &lt; 768px, 128px from `md` up.

---

## Typography

### Font families
| Use | Font | Tailwind |
|-----|------|----------|
| Headings | Nexa Bold (local) | `font-heading` |
| Body | Poppins (Google) | `font-body` |

### Heading scale (Nexa)
Mobile first; use `md:text-*-lg` for desktop.

| Level | Mobile | Desktop | Line height | Tailwind (mobile) | Tailwind (desktop) |
|-------|--------|---------|-------------|-------------------|--------------------|
| H1 | 40px | 56px | 120% | `text-h1` | `md:text-h1-lg` |
| H2 | 36px | 48px | 120% | `text-h2` | `md:text-h2-lg` |
| H3 | 32px | 40px | 120% | `text-h3` | `md:text-h3-lg` |
| H4 | 24px | 32px | 130% | `text-h4` | `md:text-h4-lg` |
| H5 | 20px | 24px | 140% | `text-h5` | `md:text-h5-lg` |
| H6 | 18px | 20px | 140% | `text-h6` | `md:text-h6-lg` |

### Body scale (Poppins)
Line height 150% for all.

| Token | Size | Tailwind |
|-------|------|----------|
| Tagline / Discount | 16px | `text-tagline` / `text-discount` |
| Body large | 20px | `text-body-lg` |
| Body medium | 18px | `text-body-md` |
| Body | 16px | `text-body` |
| Body small | 14px | `text-body-sm` |
| Body tiny | 12px | `text-body-xs` |

### Font weights
| Weight | Tailwind |
|--------|----------|
| Light | `font-light` (300) |
| Normal | `font-normal` (400) |
| Medium | `font-medium` (500) |
| Semi bold | `font-semibold` (600) |
| Bold | `font-bold` (700) |
| Extra bold | `font-extrabold` (800) |

---

## Colors

| Name | Hex | Tailwind (bg / text / border) |
|------|-----|------------------------------|
| White | `#FFFFFF` | `brand-white` |
| Black | `#000000` | `brand-black` |
| Purple | `#312783` | `brand-purple` |
| Indigo | `#0C0A21` | `brand-indigo` |
| Coral | `#E83F44` | `brand-coral` |
| Nude | `#DDA692` | `brand-nude` |
| Border | `#E5E7EB` | `brand-border` |
| Border focus | `#312783` | `brand-border-focus` |

---

## Components

Use `\App\Components::render('name', $args)` or `\App\Components::get('name', $args)`.

| Component | Key options |
|-----------|-------------|
| **button** | `variant`: primary \| outline \| icon-only · `label` · `href` · `icon_left` · `icon_right` |
| **input** | `name` · `placeholder` · `type` (text \| search) · `prefix` · `suffix` · `icon_left` · `icon_right` |
| **textarea** | `name` · `placeholder` · `rows` |
| **select** | `name` · `options` (value => label) · `selected` · `placeholder` · `icon_left` |
| **tag** | `variant`: solid \| outline · `label` · `removable` · `on_remove` |
| **checkbox** | `variant`: default \| card · `name` · `label` · `description` (card) · `checked` |
| **radio** | `variant`: default \| button · `name` · `label` · `value` · `checked` · `button_icon` |
| **toggle** | `variant`: default \| card · `name` · `label` · `description` (card) · `checked` |
| **tabs** | `tabs` (array of id, label) · `active_id` |
| **filters** | `filters` (array of id, label, active) |
| **tooltip** | `content` · `position` (top-left \| top-right \| bottom-left \| bottom-right) · `trigger` |
| **slider-arrows** | `prev_label` · `next_label` (JS: `.slider-prev` / `.slider-next`) |

Full options: `resources/views/components/README.md`.

---

## Section markup checklist

When creating a new section:

1. **Wrapper:** `max-w-section mx-auto px-section-x py-section-y` (1920px max, 68px sides, 48px vertical on mobile / 128px from md up).
2. **Headings:** `font-heading font-bold` + size token (`text-h1` … `text-h6`, `md:text-*-lg` for desktop).
3. **Body text:** `font-body` + size token (`text-body`, `text-body-md`, etc.) + weight if needed.
4. **Colors:** use `brand-*` tokens for background, text, and borders.
5. **CTAs / controls:** use component `button`, `input`, etc. via `App\Components::render()`.
