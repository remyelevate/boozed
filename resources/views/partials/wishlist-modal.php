<?php
if (!is_singular('product')) {
    return;
}

$product_id = get_the_ID();
$wishlists = is_user_logged_in() ? \App\WishlistHandler::getWishlistsForCurrentUser() : [];
$default_wishlist = !empty($wishlists) ? (int) $wishlists[0]['id'] : 0;
$default_wishlist_url = !empty($wishlists[0]['url']) ? (string) $wishlists[0]['url'] : home_url('/wishlist/');
?>
<div id="boozed-wishlist-modal" class="wishlist-modal" aria-hidden="true" data-wishlist-modal data-product-id="<?php echo esc_attr($product_id); ?>">
    <div class="wishlist-modal__backdrop" data-wishlist-close tabindex="-1" aria-hidden="true"></div>
    <div class="wishlist-modal__panel bg-brand-white text-brand-indigo shadow-2xl" role="dialog" aria-modal="true" aria-labelledby="wishlist-modal-title">
        <div class="wishlist-modal__shell">
            <button type="button" class="wishlist-modal__close" data-wishlist-close aria-label="<?php esc_attr_e('Sluiten', 'boozed'); ?>">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="wishlist-modal__state is-active" data-wishlist-state="form">
                <div class="wishlist-modal__icon" aria-hidden="true">
                    <svg viewBox="0 -960 960 960" fill="currentColor"><path d="M440-501Zm0 381L313-234q-72-65-123.5-116t-85-96q-33.5-45-49-87T40-621q0-94 63-156.5T260-840q52 0 99 22t81 62q34-40 81-62t99-22q81 0 136 45.5T831-680h-85q-18-40-53-60t-73-20q-51 0-88 27.5T463-660h-46q-31-45-70.5-72.5T260-760q-57 0-98.5 39.5T120-621q0 33 14 67t50 78.5q36 44.5 98 104T440-228q26-23 61-53t56-50l9 9 19.5 19.5L605-283l9 9q-22 20-56 49.5T498-172l-58 52Zm280-160v-120H600v-80h120v-120h80v120h120v80H800v120h-80Z"/></svg>
                </div>
                <h2 id="wishlist-modal-title" class="wishlist-modal__title"><?php esc_html_e('Kies Wenslijst:', 'boozed'); ?></h2>
                <?php if (empty($wishlists)) : ?>
                    <p class="wishlist-modal__hint"><?php esc_html_e('Je hebt nog geen wenslijst. We voegen dit product toe aan "Algemene Wenslijst", tenzij je hieronder een nieuwe naam invult.', 'boozed'); ?></p>
                <?php else : ?>
                    <p class="wishlist-modal__hint"><?php esc_html_e('Selecteer een bestaande wenslijst, of maak hieronder direct een nieuwe aan.', 'boozed'); ?></p>
                <?php endif; ?>
                <label class="sr-only" for="wishlist-select"><?php esc_html_e('Wenslijst', 'boozed'); ?></label>
                <select id="wishlist-select" class="wishlist-modal__input" data-wishlist-select>
                    <?php if (empty($wishlists)) : ?>
                        <option value="" selected><?php esc_html_e('Algemene Wenslijst (standaard)', 'boozed'); ?></option>
                    <?php else : ?>
                        <?php foreach ($wishlists as $list) : ?>
                            <option value="<?php echo esc_attr($list['id']); ?>"<?php selected((int) $list['id'], $default_wishlist); ?>>
                                <?php echo esc_html($list['title']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <input type="text" class="wishlist-modal__input" data-wishlist-new-name placeholder="<?php esc_attr_e('Nieuwe wenslijstnaam (optioneel)', 'boozed'); ?>">
                <button type="button" class="wishlist-modal__button wishlist-modal__button--primary" data-wishlist-add>
                    <?php esc_html_e('Toevoegen Wenslijst', 'boozed'); ?>
                </button>
                <button type="button" class="wishlist-modal__button" data-wishlist-close><?php esc_html_e('Sluiten', 'boozed'); ?></button>
            </div>
            <div class="wishlist-modal__state" data-wishlist-state="success">
                <div class="wishlist-modal__icon" aria-hidden="true">
                    <svg viewBox="0 -960 960 960" fill="currentColor"><path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>
                </div>
                <p class="wishlist-modal__message" data-wishlist-success-text></p>
                <a href="<?php echo esc_url($default_wishlist_url); ?>" class="wishlist-modal__button wishlist-modal__button--primary" data-wishlist-view-link><?php esc_html_e('Bekijk Wenslijst', 'boozed'); ?></a>
                <button type="button" class="wishlist-modal__button" data-wishlist-close><?php esc_html_e('Sluiten', 'boozed'); ?></button>
            </div>
        </div>
    </div>
</div>
