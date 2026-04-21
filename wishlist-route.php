<?php
/**
 * Private wishlist route template.
 * URL: /wishlist/<slug>/
 */

get_header();

$wishlist = get_query_var('boozed_current_wishlist');
if (!$wishlist instanceof \WP_Post) {
    status_header(404);
    ?>
    <section class="max-w-section mx-auto px-4 py-10 md:px-section-x md:py-section-y">
        <h1 class="font-heading font-bold text-h2 text-brand-indigo"><?php esc_html_e('Wenslijst niet gevonden', 'boozed'); ?></h1>
    </section>
    <?php
    get_footer();
    return;
}

$all = \App\WishlistHandler::getCurrentUserWishlistsWithProducts();
$current = null;
foreach ($all as $entry) {
    if ((int) ($entry['id'] ?? 0) === (int) $wishlist->ID) {
        $current = $entry;
        break;
    }
}

if (!$current) {
    status_header(404);
    ?>
    <section class="max-w-section mx-auto px-4 py-10 md:px-section-x md:py-section-y">
        <h1 class="font-heading font-bold text-h2 text-brand-indigo"><?php esc_html_e('Wenslijst niet gevonden', 'boozed'); ?></h1>
    </section>
    <?php
    get_footer();
    return;
}
?>
<section class="wishlist-page max-w-section mx-auto px-4 py-10 md:px-section-x md:py-section-y" data-wishlist-manager>
    <h1 class="font-heading font-bold text-h1 text-brand-indigo mb-8"><?php echo esc_html($current['title']); ?></h1>
    <div class="wishlist-manager__toolbar mb-6 flex flex-wrap items-center gap-3">
        <a href="<?php echo esc_url(home_url('/wishlist/')); ?>" class="wishlist-route__back-link"><?php esc_html_e('Mijn wenslijsten', 'boozed'); ?></a>
    </div>

    <div class="wishlist-manager__lists space-y-8" data-wishlist-lists>
        <article class="wishlist-list" data-wishlist-id="<?php echo esc_attr($current['id']); ?>">
            <div class="wishlist-list__header flex items-center justify-between gap-4 mb-3">
                <h2 class="font-heading font-bold text-h4 text-brand-indigo m-0"><?php echo esc_html($current['title']); ?></h2>
                <div class="flex items-center gap-2">
                    <button class="wishlist-list__action" data-wishlist-rename><?php esc_html_e('Hernoemen', 'boozed'); ?></button>
                    <button class="wishlist-list__action" data-wishlist-delete><?php esc_html_e('Verwijderen', 'boozed'); ?></button>
                </div>
            </div>
            <?php if (empty($current['products'])) : ?>
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
                            <?php foreach ($current['products'] as $item) : ?>
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
    </div>

    <div class="wishlist-manager__quote mt-8 flex justify-end">
        <button type="button" class="wishlist-modal__button wishlist-modal__button--primary w-auto !mb-0" data-wishlist-quote-open data-wishlist-id="<?php echo esc_attr($current['id']); ?>" data-wishlist-title="<?php echo esc_attr($current['title']); ?>">
            <?php esc_html_e('Vraag offerte aan', 'boozed'); ?>
        </button>
    </div>
</section>
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
<div id="boozed-wishlist-quote-modal" class="wishlist-modal" aria-hidden="true" data-wishlist-quote-modal>
    <div class="wishlist-modal__backdrop" data-wishlist-quote-close tabindex="-1" aria-hidden="true"></div>
    <div class="wishlist-modal__panel bg-brand-white text-brand-indigo shadow-2xl" role="dialog" aria-modal="true">
        <div class="wishlist-modal__shell">
            <button type="button" class="wishlist-modal__close" data-wishlist-quote-close aria-label="<?php esc_attr_e('Sluiten', 'boozed'); ?>"><span aria-hidden="true">&times;</span></button>
            <h2 class="wishlist-modal__title" data-wishlist-quote-title><?php esc_html_e('Vertel ons meer over jouw aanvraag', 'boozed'); ?></h2>
            <textarea class="wishlist-modal__input min-h-[180px] px-4 py-3" data-wishlist-quote-message placeholder="<?php esc_attr_e('Extra informatie...', 'boozed'); ?>"></textarea>
            <button type="button" class="wishlist-modal__button wishlist-modal__button--primary" data-wishlist-quote-submit><?php esc_html_e('Verstuur een Verzoek', 'boozed'); ?></button>
        </div>
    </div>
</div>
<?php
get_footer();
