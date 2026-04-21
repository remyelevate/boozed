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
                    <svg viewBox="0 0 256 256" fill="currentColor"><path d="M178,40a54.83,54.83,0,0,0-48,28.8A54.83,54.83,0,0,0,82,40C51.72,40,28,64.92,28,96.8c0,70.42,91.05,120.14,95,122.23a8,8,0,0,0,7.54,0c3.91-2.09,95-51.81,95-122.23C228,64.92,204.28,40,174,40Zm0,16c22.2,0,38,17.24,38,40.8,0,56.74-71.5,100.12-86,108-14.5-7.88-86-51.26-86-108C44,73.24,59.8,56,82,56c20.31,0,34.88,18.55,40.62,31.22a8,8,0,0,0,14.76,0C143.12,74.55,157.69,56,178,56Zm52,28h-16V68a8,8,0,0,0-16,0V84H182a8,8,0,0,0,0,16h16v16a8,8,0,0,0,16,0V100h16a8,8,0,0,0,0-16Z"/></svg>
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
                    <svg viewBox="0 0 256 256" fill="currentColor"><path d="M243.33,96.35c0,70.42-91,120.14-94.95,122.23a8,8,0,0,1-7.54,0c-3.91-2.09-95-51.81-95-122.23C45.87,64.47,69.58,39.55,99.87,39.55A54.77,54.77,0,0,1,148,68.35a54.77,54.77,0,0,1,48.13-28.8C226.42,39.55,250.13,64.47,250.13,96.35Zm-55.47-20.93a8,8,0,0,0-11.32,0l-44.38,44.39-20.68-20.69a8,8,0,1,0-11.31,11.32l26.34,26.34a8,8,0,0,0,11.31,0l50-50A8,8,0,0,0,187.86,75.42Z"/></svg>
                </div>
                <p class="wishlist-modal__message" data-wishlist-success-text></p>
                <a href="<?php echo esc_url($default_wishlist_url); ?>" class="wishlist-modal__button wishlist-modal__button--primary" data-wishlist-view-link><?php esc_html_e('Bekijk Wenslijst', 'boozed'); ?></a>
                <button type="button" class="wishlist-modal__button" data-wishlist-close><?php esc_html_e('Sluiten', 'boozed'); ?></button>
            </div>
        </div>
    </div>
</div>
