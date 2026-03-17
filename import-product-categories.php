<?php

/**
 * One-off script: import WooCommerce product categories.
 *
 * Run from project root (use same DB as the site — do not run `wp core install`):
 *
 *   With Docker (recommended; uses existing DB):
 *     docker compose exec wordpress wp eval-file wp-content/themes/boozed/import-product-categories.php
 *
 *   Or locally if WP-CLI uses the same database as the site:
 *     wp eval-file wp-content/themes/boozed/import-product-categories.php
 *
 * Or from WordPress root in browser (temporary, remove after use):
 *   load this file via a custom admin action or run the logic in a plugin/theme init.
 */

if (!defined('ABSPATH')) {
    // Allow running via WP-CLI: wp eval-file ...
    if (php_sapi_name() !== 'cli' || !class_exists('WP_CLI')) {
        die('Load this file in WordPress context (e.g. wp eval-file).');
    }
}

$categories = [
    'ACHTERWANDEN',
    'AV',
    'BANKEN & FAUTEUILS',
    'BARS & BUFFETTEN',
    'BIER BUFFETTEN',
    'BIJZETTAFELS',
    'BIJZONDERE OBJECTEN',
    'BLOEMPOTTEN',
    'BRANDING',
    'BUFFETTEN',
    'CATERING',
    'CATERINGMATERIALEN',
    'COCKTAILWORKSTATIONS',
    'DECORATIE',
    'DIEREN',
    'DINERTAFELS',
    'DINERSTOELEN',
    'DISPLAY KASTEN',
    'DISPLAYS',
    'FLORA & FAUNA',
    'FOOD & BEVERAGE',
    'GARDEROBE/ENTREE',
    'Geen categorie',
    'GLASWERK',
    'GROEN/BLOEM',
    'GROENOBJECTEN',
    'HARDWARE',
    'HOLLANDS',
    'IJS & VERS',
    'KAAPSTAD',
    'KOPENHAGEN',
    'KOELVRIES',
    'KRUKKEN',
    'KUNST',
    'KUSSENS',
    'LANTAARNS EN OVERIGE KAARSHOUDERS',
    'LOUNGESTOELEN',
    'LOUNGETAFELS',
    'MANDEN/KISTEN',
    'MUZIEK',
    'OVERIG',
    'PARASOLS/HEATERS',
    'PLANTENBAKKEN',
    'PODIUM',
    'PRESENTATIE ITEMS',
    'PRULLENBAKKEN',
    'ROOMDIVIDERS',
    'SHAPE',
    'SPIEGELS',
    'SPORT & SPEL',
    'STATAFELS',
    'STELLINGEN',
    'STUDIO',
    'TAFELS',
    'TECHNIEK',
    'THEMA BARS',
    'TRANSPORT',
    'VAZEN',
    'VERLICHTING',
    'VERS',
    'VINTAGE, RETRO & ANTIEK',
    'VLOERKLEDEN',
    'WAND & PLAFONDDECORATIE',
    'WERELDS',
    'ZINK',
    'ZIT',
    'ZUILEN/SOKKELS',
];

$taxonomy = 'product_cat';
if (!taxonomy_exists($taxonomy)) {
    if (function_exists('WP_CLI::error')) {
        WP_CLI::error("Taxonomy {$taxonomy} does not exist. Is WooCommerce active?");
    }
    exit(1);
}

$created = 0;
$skipped = 0;

foreach ($categories as $name) {
    $name = trim($name);
    if ($name === '') {
        continue;
    }
    $slug = sanitize_title($name);
    if (term_exists($name, $taxonomy) || term_exists($slug, $taxonomy)) {
        $skipped++;
        if (function_exists('WP_CLI::log')) {
            WP_CLI::log("Skip (exists): {$name}");
        }
        continue;
    }
    $result = wp_insert_term($name, $taxonomy, ['slug' => $slug]);
    if (is_wp_error($result)) {
        if (function_exists('WP_CLI::warning')) {
            WP_CLI::warning("Failed: {$name} – " . $result->get_error_message());
        }
        continue;
    }
    $created++;
    if (function_exists('WP_CLI::log')) {
        WP_CLI::log("Created: {$name}");
    }
}

if (function_exists('WP_CLI::success')) {
    WP_CLI::success("Done. Created: {$created}, Skipped: {$skipped}");
}
