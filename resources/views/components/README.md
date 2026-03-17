# Design system components

Reusable UI components using the Boozed design system (Tailwind + brand colors). Use via `App\Components::render()` or `App\Components::get()`.

## Usage

```php
// Output in place
\App\Components::render('button', ['label' => 'Submit', 'variant' => 'primary']);

// Return HTML string
$html = \App\Components::get('input', ['name' => 'email', 'placeholder' => 'Email']);
```

## Components

| Component   | Key options |
|------------|-------------|
| **button** | `variant` (primary \| outline \| icon-only), `label`, `href`, `type`, `icon_left`, `icon_right`, `class` |
| **input**  | `name`, `id`, `value`, `placeholder`, `type` (text \| search), `prefix`, `suffix`, `icon_left`, `icon_right`, `required`, `disabled`, `class` |
| **textarea** | `name`, `id`, `value`, `placeholder`, `rows`, `required`, `disabled`, `class` |
| **select** | `name`, `id`, `options` (value => label), `selected`, `placeholder`, `icon_left`, `required`, `disabled`, `class` |
| **tag**    | `variant` (solid \| outline), `label`, `removable`, `on_remove`, `class` |
| **checkbox** | `variant` (default \| card), `name`, `id`, `label`, `description` (card), `checked`, `value`, `card_icon`, `class` |
| **radio**  | `variant` (default \| button), `name`, `id`, `label`, `value`, `checked`, `button_icon`, `class` |
| **toggle** | `variant` (default \| card), `name`, `id`, `label`, `description` (card), `checked`, `class` |
| **tabs**   | `tabs` (array of id, label), `active_id`, `class` |
| **filters**| `filters` (array of id, label, active), `class` |
| **tooltip**| `content`, `position` (top-left \| top-right \| bottom-left \| bottom-right), `trigger`, `id`, `class` |
| **slider-arrows** | `prev_label`, `next_label`, `class` (use with `.slider-prev` / `.slider-next` in JS) |
| **product-card**  | `image_url`, `image_alt`, `category_label`, `title`, `url` (always links to PDP when set), `link_text`, `show_link_label` (show "Bekijken >" below title), `price_html` (WooCommerce price HTML, shown when user is logged in), `class` |

All components use design system colors: `brand-purple`, `brand-indigo`, `brand-white`, `brand-nude`, `brand-border`, etc.
