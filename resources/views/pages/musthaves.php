<?php
$is_logged_in = is_user_logged_in();
$wishlists = $is_logged_in ? \App\WishlistHandler::getCurrentUserWishlistsWithProducts() : [];
$musthaves_login = function_exists('boozed_login_url') ? boozed_login_url(get_permalink()) : wp_login_url(get_permalink());
?>
<section class="wishlist-page max-w-section mx-auto px-4 py-10 md:px-section-x md:py-section-y" data-wishlist-manager>
    <div class="wishlist-page__header mb-8 flex items-center justify-between gap-4">
        <h1 class="font-heading font-bold text-h1 text-brand-indigo m-0"><?php esc_html_e('Wenslijsten', 'boozed'); ?></h1>
        <?php if ($is_logged_in) : ?>
            <button
                type="button"
                class="wishlist-list__action inline-flex h-[50px] items-center justify-center px-5 bg-brand-purple text-brand-white border-brand-purple hover:opacity-90"
                data-wishlist-open-create
                aria-label="<?php esc_attr_e('Nieuwe wenslijst aanmaken', 'boozed'); ?>">
                <?php esc_html_e('Nieuwe wenslijst', 'boozed'); ?>
            </button>
        <?php endif; ?>
    </div>

    <?php if (!$is_logged_in) : ?>
        <div class="wishlist-page__login">
            <p class="font-body text-body text-brand-black mb-4"><?php esc_html_e('Log in om je wenslijsten te beheren.', 'boozed'); ?></p>
            <a href="<?php echo esc_url($musthaves_login); ?>" class="wishlist-modal__button wishlist-modal__button--primary inline-flex"><?php esc_html_e('Inloggen', 'boozed'); ?></a>
        </div>
    <?php else : ?>
        <div class="wishlist-manager__lists space-y-8" data-wishlist-lists>
            <?php if (empty($wishlists)) : ?>
                <p class="font-body text-body text-brand-black/70"><?php esc_html_e('Je hebt nog geen wenslijsten.', 'boozed'); ?></p>
            <?php endif; ?>
            <?php foreach ($wishlists as $wishlist) : ?>
                <article class="wishlist-list" data-wishlist-id="<?php echo esc_attr($wishlist['id']); ?>">
                    <div class="wishlist-list__header flex items-center justify-between gap-4 mb-3">
                        <h2 class="font-heading font-bold text-h4 text-brand-indigo m-0"><?php echo esc_html($wishlist['title']); ?></h2>
                        <div class="flex items-center gap-2">
                            <button class="wishlist-list__action" data-wishlist-rename><?php esc_html_e('Hernoemen', 'boozed'); ?></button>
                            <button class="wishlist-list__action" data-wishlist-delete><?php esc_html_e('Verwijderen', 'boozed'); ?></button>
                        </div>
                    </div>
                    <?php if (empty($wishlist['products'])) : ?>
                        <p class="font-body text-body-sm text-brand-black/60"><?php esc_html_e('Deze wenslijst is nog leeg.', 'boozed'); ?></p>
                    <?php else : ?>
                        <div class="wishlist-list__table-wrap">
                            <table class="wishlist-list__table w-full">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e('Productnaam', 'boozed'); ?></th>
                                        <th><?php esc_html_e('Stuksprijs', 'boozed'); ?></th>
                                        <th><?php esc_html_e('Voorraadstatus', 'boozed'); ?></th>
                                        <th class="wishlist-list__actions-col"><?php esc_html_e('Acties', 'boozed'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($wishlist['products'] as $item) : ?>
                                        <tr data-product-id="<?php echo esc_attr($item['id']); ?>">
                                            <td>
                                                <div class="wishlist-list__product">
                                                    <?php if (!empty($item['image_url'])) : ?>
                                                        <img src="<?php echo esc_url($item['image_url']); ?>" alt="<?php echo esc_attr($item['title']); ?>">
                                                    <?php endif; ?>
                                                    <a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['title']); ?></a>
                                                </div>
                                            </td>
                                            <td><?php echo wp_kses_post($item['price_html'] ?: '&mdash;'); ?></td>
                                            <td><?php echo esc_html($item['stock_text']); ?></td>
                                            <td class="wishlist-list__actions-col">
                                                <div class="wishlist-list__actions">
                                                    <button type="button" data-wishlist-remove><?php esc_html_e('Verwijderen', 'boozed'); ?></button>
                                                    <button type="button" data-wishlist-move><?php esc_html_e('Verplaatsen', 'boozed'); ?></button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php if ($is_logged_in) : ?>
<div id="boozed-wishlist-manager-modal" class="wishlist-modal" aria-hidden="true" data-wishlist-manager-modal>
    <div class="wishlist-modal__backdrop" data-wishlist-manager-close tabindex="-1" aria-hidden="true"></div>
    <div class="wishlist-modal__panel bg-brand-white text-brand-indigo shadow-2xl" role="dialog" aria-modal="true">
        <div class="wishlist-modal__shell">
            <button type="button" class="wishlist-modal__close" data-wishlist-manager-close aria-label="<?php esc_attr_e('Sluiten', 'boozed'); ?>"><span aria-hidden="true">&times;</span></button>
            <div class="wishlist-modal__state is-active" data-manager-state="create">
                <h2 class="wishlist-modal__title"><?php esc_html_e('Wenslijst aanmaken', 'boozed'); ?></h2>
                <input type="text" class="wishlist-modal__input" data-manager-create-name placeholder="<?php esc_attr_e('Geef je lijst een naam', 'boozed'); ?>">
                <button type="button" class="wishlist-modal__button wishlist-modal__button--primary" data-manager-create-submit><?php esc_html_e('Lijst aanmaken', 'boozed'); ?></button>
            </div>
            <div class="wishlist-modal__state" data-manager-state="move">
                <h2 class="wishlist-modal__title"><?php esc_html_e('Verplaatsen naar', 'boozed'); ?></h2>
                <select class="wishlist-modal__input" data-manager-move-target></select>
                <button type="button" class="wishlist-modal__button wishlist-modal__button--primary" data-manager-move-submit><?php esc_html_e('Verplaatsen naar', 'boozed'); ?></button>
            </div>
            <div class="wishlist-modal__state" data-manager-state="rename">
                <h2 class="wishlist-modal__title"><?php esc_html_e('Wenslijst hernoemen', 'boozed'); ?></h2>
                <input type="text" class="wishlist-modal__input" data-manager-rename-name placeholder="<?php esc_attr_e('Nieuwe naam', 'boozed'); ?>">
                <button type="button" class="wishlist-modal__button wishlist-modal__button--primary" data-manager-rename-submit><?php esc_html_e('Opslaan', 'boozed'); ?></button>
            </div>
            <div class="wishlist-modal__state" data-manager-state="delete">
                <h2 class="wishlist-modal__title"><?php esc_html_e('Wenslijst verwijderen', 'boozed'); ?></h2>
                <p class="wishlist-modal__message"><?php esc_html_e('Weet je zeker dat je deze wenslijst wilt verwijderen?', 'boozed'); ?></p>
                <button type="button" class="wishlist-modal__button wishlist-modal__button--primary" data-manager-delete-submit><?php esc_html_e('Verwijderen', 'boozed'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
