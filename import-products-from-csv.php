<?php

/**
 * WooCommerce product import from Dutch CSV export.
 *
 * Imports products from wc-product-export-*.csv (Dutch column headers).
 * Creates or updates by SKU. Creates product categories and tags as needed.
 *
 * Run from project root (WooCommerce must be active):
 *
 *   Docker:
 *     docker compose exec wordpress wp eval-file wp-content/themes/boozed/import-products-from-csv.php -- path/to/file.csv
 *
 *   WP-CLI (same DB as site):
 *     wp eval-file wp-content/themes/boozed/import-products-from-csv.php -- wc-product-export-16-2-2026-1771248016848.csv
 *
 *   Default CSV path (if no arg): ABSPATH . '../wc-product-export-16-2-2026-1771248016848.csv'
 *   (i.e. project root, one level above WordPress)
 *
 * Options (environment / constants):
 *   DRY_RUN=1     Only parse and log, do not insert/update products.
 *   BATCH_SIZE   Process only this many rows (default: all).
 *   SKIP_EXISTING=1  Skip rows when product with same SKU already exists (default: update).
 */

if (!defined('ABSPATH')) {
    if (php_sapi_name() !== 'cli' || !class_exists('WP_CLI')) {
        die('Load in WordPress context (e.g. wp eval-file import-products-from-csv.php).');
    }
}

if (!function_exists('wc_get_product')) {
    if (function_exists('WP_CLI::error')) {
        WP_CLI::error('WooCommerce is not active.');
    }
    exit(1);
}

// CSV path: first CLI arg (global $args from wp eval-file), or default
if (isset($GLOBALS['args']) && isset($GLOBALS['args'][0])) {
    $csv_path = $GLOBALS['args'][0];
} else {
    $csv_path = null;
}
if ($csv_path === null && defined('ABSPATH')) {
    $default_name = 'wc-product-export-16-2-2026-1771248016848.csv';
    $csv_path = is_file(ABSPATH . $default_name)
        ? ABSPATH . $default_name
        : dirname(ABSPATH) . '/' . $default_name;
}
if (!is_file($csv_path) || !is_readable($csv_path)) {
    if (function_exists('WP_CLI::error')) {
        WP_CLI::error('CSV file not found or not readable: ' . $csv_path);
    }
    exit(1);
}

$dry_run    = (int) getenv('DRY_RUN') === 1;
$batch_size = (int) getenv('BATCH_SIZE');
$skip_existing = (int) getenv('SKIP_EXISTING') === 1;

if (function_exists('WP_CLI::log')) {
    WP_CLI::log('CSV: ' . $csv_path);
    WP_CLI::log('DRY_RUN=' . ($dry_run ? '1' : '0') . ', SKIP_EXISTING=' . ($skip_existing ? '1' : '0') . ', BATCH_SIZE=' . ($batch_size ?: 'all'));
}

// Dutch header -> WC field mapping (key = CSV header as in file)
$header_map = [
    'ID'                        => 'id',
    'Type'                      => 'type',
    'SKU'                       => 'sku',
    'GTIN, UPC, EAN of ISBN'    => 'gtin',
    'Naam'                      => 'name',
    'Gepubliceerd'              => 'published',
    'Uitgelicht?'               => 'featured',
    'Zichtbaarheid in catalogus'=> 'catalog_visibility',
    'Korte beschrijving'        => 'short_description',
    'Beschrijving'              => 'description',
    'Startdatum actieprijs'     => 'sale_start',
    'Einddatum actieprijs'      => 'sale_end',
    'Btw status'                => 'tax_status',
    'Belastingklasse'           => 'tax_class',
    'Op voorraad?'              => 'stock_status',
    'Voorraad'                  => 'stock_quantity',
    'Lage voorraad'             => 'low_stock',
    'Nabestellingen toestaan?'  => 'backorders',
    'Wordt individueel verkocht?' => 'sold_individually',
    'Gewicht (kg)'              => 'weight',
    'Lengte (cm)'               => 'length',
    'Breedte (cm)'              => 'width',
    'Hoogte (cm)'               => 'height',
    'Klantbeoordelingen toestaan?' => 'reviews_allowed',
    'Aankoopnotitie'            => 'purchase_note',
    'Actieprijs'                => 'sale_price',
    'Reguliere prijs'           => 'regular_price',
    'Categorieën'               => 'categories',
    'Tags'                      => 'tags',
    'Verzendklasse'             => 'shipping_class',
    'Afbeeldingen'              => 'images',
    'Downloadlimiet'            => 'download_limit',
    'Dagen vervaltijd download' => 'download_expiry',
    'Hoofd'                     => 'parent_sku',
    'Gegroepeerde producten'    => 'grouped_products',
    'Upsells'                   => 'upsells',
    'Cross-sells'               => 'cross_sells',
    'Externe URL'               => 'external_url',
    'Knop tekst'                => 'button_text',
    'Positie'                   => 'menu_order',
    'Merken'                    => 'brands',
];

/** Ensure hierarchical product categories exist and return term IDs for a row. */
function import_ensure_categories($categories_str, $taxonomy = 'product_cat') {
    if (!taxonomy_exists($taxonomy) || trim((string) $categories_str) === '') {
        return [];
    }
    $term_ids = [];
    // Format: "TAFELS > STATAFELS" or "CAT1, CAT2 > SUB"
    $groups = array_map('trim', explode(',', $categories_str));
    foreach ($groups as $group) {
        $chain = array_map('trim', explode('>', $group));
        $parent = 0;
        foreach ($chain as $name) {
            if ($name === '') continue;
            $slug = sanitize_title($name);
            $term = term_exists($name, $taxonomy);
            if (!$term) {
                $term = term_exists($slug, $taxonomy);
            }
            if (!$term) {
                $t = wp_insert_term($name, $taxonomy, ['slug' => $slug, 'parent' => $parent]);
                $term = is_wp_error($t) ? null : ['term_id' => (int) $t['term_id']];
            }
            if ($term && isset($term['term_id'])) {
                $term_ids[] = (int) $term['term_id'];
                $parent = (int) $term['term_id'];
            }
        }
    }
    return array_unique($term_ids);
}

/** Ensure product tags exist and return term IDs. */
function import_ensure_tags($tags_str, $taxonomy = 'product_tag') {
    if (!taxonomy_exists($taxonomy) || trim((string) $tags_str) === '') {
        return [];
    }
    $term_ids = [];
    $tags = array_map('trim', preg_split('/[\s,]+/', $tags_str, -1, PREG_SPLIT_NO_EMPTY));
    foreach ($tags as $name) {
        if ($name === '') continue;
        $slug = sanitize_title($name);
        $term = term_exists($name, $taxonomy) ?: term_exists($slug, $taxonomy);
        if (!$term) {
            $t = wp_insert_term($name, $taxonomy, ['slug' => $slug]);
            $term = is_wp_error($t) ? null : ['term_id' => (int) $t['term_id']];
        }
        if ($term && isset($term['term_id'])) {
            $term_ids[] = (int) $term['term_id'];
        }
    }
    return array_unique($term_ids);
}

/** Resolve image URL to attachment ID (by URL or by filename); optionally download. */
function import_image_to_attachment_id($url, $post_id = 0) {
    $url = trim($url);
    if ($url === '') return 0;
    if (function_exists('attachment_url_to_postid')) {
        $aid = attachment_url_to_postid($url);
        if ($aid) return $aid;
    }
    if (!function_exists('media_sideload_image')) {
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }
    $tmp = download_url($url);
    if (is_wp_error($tmp)) return 0;
    $file_array = [
        'name'     => basename(parse_url($url, PHP_URL_PATH)),
        'tmp_name' => $tmp,
    ];
    $aid = media_handle_sideload($file_array, $post_id);
    if (is_wp_error($aid)) return 0;
    return (int) $aid;
}

/** Build product data array from CSV row (associative by mapped keys). */
function import_row_to_product_data($row, $header_map) {
    $data = [];
    foreach ($header_map as $csv_header => $key) {
        $data[$key] = isset($row[$csv_header]) ? $row[$csv_header] : '';
    }
    return $data;
}

/** Create or update one WooCommerce product from data array. */
function import_upsert_product($data, $options = []) {
    $dry_run = !empty($options['dry_run']);
    $skip_existing = !empty($options['skip_existing']);

    $sku = isset($data['sku']) ? trim($data['sku']) : '';
    $name = isset($data['name']) ? trim($data['name']) : '';
    if ($name === '') {
        return ['success' => false, 'message' => 'Empty name'];
    }

    $product_id = 0;
    if ($sku !== '' && function_exists('wc_get_product_id_by_sku')) {
        $product_id = wc_get_product_id_by_sku($sku);
    }
    $is_update = $product_id > 0;

    if ($is_update && $skip_existing) {
        return ['success' => true, 'skipped' => true, 'id' => $product_id];
    }

    $type = isset($data['type']) ? trim($data['type']) : 'simple';
    if (!in_array($type, ['simple', 'variable', 'grouped', 'external'], true)) {
        $type = 'simple';
    }

    if ($dry_run) {
        return ['success' => true, 'dry_run' => true, 'would' => ($is_update ? 'update' : 'create') . ' ' . $name];
    }

    if ($product_id) {
        $product = wc_get_product($product_id);
        if (!$product) {
            return ['success' => false, 'message' => 'Product not found for ID ' . $product_id];
        }
    } else {
        $product = new WC_Product_Simple();
    }

    $product->set_name($name);
    if ($sku !== '') {
        $product->set_sku($sku);
    }

    $product->set_status(isset($data['published']) && (string) $data['published'] === '1' ? 'publish' : 'draft');
    $product->set_featured(isset($data['featured']) && (string) $data['featured'] === '1');
    $product->set_catalog_visibility(isset($data['catalog_visibility']) ? $data['catalog_visibility'] : 'visible');
    $product->set_description(isset($data['description']) ? $data['description'] : '');
    $product->set_short_description(isset($data['short_description']) ? $data['short_description'] : '');

    $product->set_tax_status(isset($data['tax_status']) ? $data['tax_status'] : 'taxable');
    $product->set_tax_class(isset($data['tax_class']) ? $data['tax_class'] : '');

    $stock = isset($data['stock_status']) ? (string) $data['stock_status'] : '1';
    $product->set_stock_status($stock === '1' ? 'instock' : 'outofstock');
    if (isset($data['stock_quantity']) && $data['stock_quantity'] !== '') {
        $product->set_manage_stock(true);
        $product->set_stock_quantity((int) $data['stock_quantity']);
    }
    if (isset($data['backorders']) && (string) $data['backorders'] === '1') {
        $product->set_backorders('yes');
    }
    $product->set_sold_individually(isset($data['sold_individually']) && (string) $data['sold_individually'] === '1');

    if (isset($data['weight']) && $data['weight'] !== '') {
        $product->set_weight((float) str_replace(',', '.', $data['weight']));
    }
    if (isset($data['length']) && $data['length'] !== '') {
        $product->set_length((float) str_replace(',', '.', $data['length']));
    }
    if (isset($data['width']) && $data['width'] !== '') {
        $product->set_width((float) str_replace(',', '.', $data['width']));
    }
    if (isset($data['height']) && $data['height'] !== '') {
        $product->set_height((float) str_replace(',', '.', $data['height']));
    }

    $product->set_reviews_allowed(isset($data['reviews_allowed']) && (string) $data['reviews_allowed'] === '1');
    if (isset($data['purchase_note']) && $data['purchase_note'] !== '') {
        $product->set_purchase_note($data['purchase_note']);
    }

    $regular = isset($data['regular_price']) ? trim($data['regular_price']) : '';
    $sale = isset($data['sale_price']) ? trim($data['sale_price']) : '';
    if ($regular !== '') {
        $product->set_regular_price(str_replace(',', '.', $regular));
    }
    if ($sale !== '') {
        $product->set_sale_price(str_replace(',', '.', $sale));
    }

    if (isset($data['menu_order']) && $data['menu_order'] !== '') {
        $product->set_menu_order((int) $data['menu_order']);
    }

    $product->save();
    $product_id = $product->get_id();

    // Categories
    if (!empty($data['categories'])) {
        $cat_ids = import_ensure_categories($data['categories']);
        if (!empty($cat_ids)) {
            wp_set_object_terms($product_id, $cat_ids, 'product_cat');
        }
    }

    // Tags
    if (!empty($data['tags'])) {
        $tag_ids = import_ensure_tags($data['tags']);
        if (!empty($tag_ids)) {
            wp_set_object_terms($product_id, $tag_ids, 'product_tag');
        }
    }

    // Gallery: comma-separated URLs
    if (!empty($data['images'])) {
        $urls = array_map('trim', explode(',', $data['images']));
        $attachment_ids = [];
        foreach ($urls as $url) {
            if ($url === '') continue;
            $aid = import_image_to_attachment_id($url, $product_id);
            if ($aid) {
                $attachment_ids[] = $aid;
            }
        }
        if (!empty($attachment_ids)) {
            $product->set_image_id($attachment_ids[0]);
            if (count($attachment_ids) > 1) {
                $product->set_gallery_image_ids(array_slice($attachment_ids, 1));
            }
            $product->save();
        }
    }

    return ['success' => true, 'id' => $product_id, 'updated' => $is_update];
}

// --- Main: read CSV and process rows ---
$fp = fopen($csv_path, 'r');
if (!$fp) {
    if (function_exists('WP_CLI::error')) {
        WP_CLI::error('Could not open CSV.');
    }
    exit(1);
}

$headers = fgetcsv($fp, 0, ',', '"', '');
fclose($fp);
if (!$headers) {
    if (function_exists('WP_CLI::error')) {
        WP_CLI::error('Empty or invalid CSV header.');
    }
    exit(1);
}

// Re-open for full read (fgetcsv handles quoted newlines)
$fp = fopen($csv_path, 'r');
$headers = fgetcsv($fp, 0, ',', '"', '');
$created = 0;
$updated = 0;
$skipped = 0;
$errors = 0;
$row_index = 0;

while (($cells = fgetcsv($fp, 0, ',', '"', '')) !== false) {
    $row_index++;
    if ($batch_size && $row_index > $batch_size) {
        break;
    }
    $row = array_combine($headers, array_pad($cells, count($headers), ''));
    if ($row === false) {
        $errors++;
        if (function_exists('WP_CLI::warning')) {
            WP_CLI::warning("Row {$row_index}: column count mismatch");
        }
        continue;
    }
    $data = import_row_to_product_data($row, $header_map);
    $result = import_upsert_product($data, [
        'dry_run'       => $dry_run,
        'skip_existing' => $skip_existing,
    ]);
    if (!empty($result['skipped'])) {
        $skipped++;
        continue;
    }
    if (!empty($result['dry_run'])) {
        if (function_exists('WP_CLI::log')) {
            WP_CLI::log("[{$row_index}] " . $result['would']);
        }
        $created++;
        continue;
    }
    if (empty($result['success'])) {
        $errors++;
        if (function_exists('WP_CLI::warning')) {
            WP_CLI::warning("Row {$row_index}: " . ($result['message'] ?? 'Unknown error'));
        }
        continue;
    }
    if (!empty($result['updated'])) {
        $updated++;
    } else {
        $created++;
    }
    if (function_exists('WP_CLI::log') && $row_index <= 20) {
        WP_CLI::log("Row {$row_index}: " . ($result['updated'] ? 'Updated' : 'Created') . " ID " . $result['id'] . " – " . $data['name']);
    }
}
fclose($fp);

if (function_exists('WP_CLI::success')) {
    WP_CLI::success(sprintf(
        'Done. Created: %d, Updated: %d, Skipped: %d, Errors: %d',
        $created,
        $updated,
        $skipped,
        $errors
    ));
}
