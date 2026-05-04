<?php
/**
 * Fixed news article layout for blog posts (when use_news_article_template is on).
 * Centered 8/12 column: title, breadcrumbs, excerpt, meta, share, featured image, main content.
 *
 * Expects the main query to be on the current post.
 */

$post_id = isset($post_id) ? (int) $post_id : (int) get_the_ID();
if ($post_id <= 0) {
    return;
}

$post = get_post($post_id);
if (!$post || $post->post_type !== 'post') {
    return;
}

$permalink     = get_permalink($post_id);
$title_text    = get_the_title($post_id);
$encoded_url   = rawurlencode((string) $permalink);
$encoded_title = rawurlencode(html_entity_decode($title_text, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

$share_linkedin  = 'https://www.linkedin.com/sharing/share-offsite/?url=' . $encoded_url;
$share_whatsapp  = 'https://wa.me/?text=' . rawurlencode($title_text . ' ' . $permalink);
$share_email_href = 'mailto:?subject=' . $encoded_title . '&body=' . rawurlencode(sprintf("%s\n\n%s", $title_text, $permalink));

$author_id   = (int) $post->post_author;
$author_name = $author_id ? get_the_author_meta('display_name', $author_id) : '';
$read_mins   = function_exists('boozed_post_reading_time_minutes') ? boozed_post_reading_time_minutes($post_id) : 1;

$date_display = get_the_date('', $post_id);
$date_attr    = get_the_date('c', $post_id);

$news_url   = function_exists('boozed_news_index_url') ? boozed_news_index_url() : home_url('/');
$news_label = __('Nieuws', 'boozed');
$home_label = __('Home', 'boozed');

$thumb_id  = get_post_thumbnail_id($post_id);
$has_thumb = (int) $thumb_id > 0;

$show_excerpt = has_excerpt($post_id);
$excerpt_html = $show_excerpt ? get_the_excerpt($post_id) : '';

$muted    = 'text-brand-black/70';
$link_col = 'text-brand-indigo hover:opacity-90';
?>

<article class="single-post-news bg-brand-white">
    <section class="single-post-news__header-wrap max-w-section mx-auto w-full px-4 md:px-section-x pt-[200px] pb-6 md:pb-10">
        <div class="single-post-news__header w-full space-y-6 md:space-y-8 text-left">
            <h1 class="single-post-news__title font-heading font-bold text-brand-purple text-[2rem] leading-tight sm:text-[3rem] md:text-[64pt] md:leading-[1.1]">
                <?php echo esc_html($title_text); ?>
            </h1>

            <nav class="single-post-news__breadcrumbs mt-4 md:mt-6 font-body text-body-sm <?php echo esc_attr($muted); ?>" aria-label="<?php esc_attr_e('Breadcrumb', 'boozed'); ?>">
                <ol class="flex flex-wrap items-center justify-start gap-x-2 gap-y-1 list-none m-0 p-0">
                    <li class="m-0 p-0">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="<?php echo esc_attr($link_col); ?> no-underline"><?php echo esc_html($home_label); ?></a>
                    </li>
                    <li class="m-0 p-0 opacity-40" aria-hidden="true">/</li>
                    <li class="m-0 p-0">
                        <a href="<?php echo esc_url($news_url); ?>" class="<?php echo esc_attr($link_col); ?> no-underline"><?php echo esc_html($news_label); ?></a>
                    </li>
                    <li class="m-0 p-0 opacity-40" aria-hidden="true">/</li>
                    <li class="m-0 p-0 min-w-0 max-w-full" aria-current="page">
                        <span class="line-clamp-3 break-words text-brand-black"><?php echo esc_html($title_text); ?></span>
                    </li>
                </ol>
            </nav>

            <?php if ($excerpt_html !== '') : ?>
                <div class="single-post-news__excerpt mt-6 md:mt-8 font-body text-body-md text-brand-black text-left max-w-none">
                    <?php echo wp_kses_post(wpautop($excerpt_html)); ?>
                </div>
            <?php endif; ?>

            <hr class="single-post-news__rule border-0 border-t border-brand-border m-0" />

            <div class="single-post-news__meta font-body text-left space-y-2">
                <?php if ($author_name !== '') : ?>
                    <p class="text-body-md text-brand-black m-0">
                        <?php
                        echo esc_html(sprintf(
                            /* translators: %s: author display name */
                            __('Door %s', 'boozed'),
                            $author_name
                        ));
                        ?>
                    </p>
                <?php endif; ?>
                <p class="text-body-sm <?php echo esc_attr($muted); ?> m-0 flex flex-wrap items-center gap-x-2 gap-y-1">
                    <?php if ($date_display !== '') : ?>
                        <time datetime="<?php echo esc_attr($date_attr); ?>"><?php echo esc_html($date_display); ?></time>
                    <?php endif; ?>
                    <?php if ($date_display !== '' && $read_mins > 0) : ?>
                        <span class="opacity-50" aria-hidden="true">•</span>
                    <?php endif; ?>
                    <?php if ($read_mins > 0) : ?>
                        <span><?php echo esc_html(sprintf(__('%d min leestijd', 'boozed'), $read_mins)); ?></span>
                    <?php endif; ?>
                </p>
            </div>

            <hr class="single-post-news__rule border-0 border-t border-brand-border m-0" />

            <div class="single-post-news__share">
                <p class="font-body text-body-sm text-brand-black m-0 mb-3"><?php esc_html_e('Deel dit artikel', 'boozed'); ?></p>
                <ul class="flex flex-wrap items-center gap-3 list-none m-0 p-0" role="list">
                    <li>
                        <a href="<?php echo esc_url($share_linkedin); ?>" target="_blank" rel="noopener noreferrer" class="single-post-news__share-btn single-post-news__share-btn--linkedin flex h-12 w-12 items-center justify-center rounded-full bg-[#0A66C2] text-white no-underline hover:opacity-90 transition-opacity" aria-label="<?php esc_attr_e('Share on LinkedIn', 'boozed'); ?>">
                            <span class="font-heading font-bold text-sm leading-none" aria-hidden="true">in</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url($share_whatsapp); ?>" target="_blank" rel="noopener noreferrer" class="single-post-news__share-btn single-post-news__share-btn--whatsapp flex h-12 w-12 items-center justify-center rounded-full bg-[#25D366] text-white no-underline hover:opacity-90 transition-opacity" aria-label="<?php esc_attr_e('Share on WhatsApp', 'boozed'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url($share_email_href); ?>" class="single-post-news__share-btn single-post-news__share-btn--email flex h-12 w-12 items-center justify-center rounded-full bg-brand-purple text-white no-underline hover:opacity-90 transition-opacity" aria-label="<?php esc_attr_e('Share by email', 'boozed'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 256 256" fill="currentColor" aria-hidden="true"><path d="M224 48H32a8 8 0 0 0-8 8v136a8 8 0 0 0 8 8h192a8 8 0 0 0 8-8V56a8 8 0 0 0-8-8Zm-96 85.15L52.57 64h150.86ZM98.71 128 32 181.81V74.19Zm16.05 16.14 13 11a8 8 0 0 0 10.49 0l13-11 51.78 59.86H63Zm51.23-16.14L224 74.18v107.64Z"/></svg>
                        </a>
                    </li>
                    <li>
                        <button type="button" class="single-post-news__share-copy flex h-12 w-12 cursor-pointer items-center justify-center rounded-full bg-brand-coral text-white border-0 hover:opacity-90 transition-opacity" data-copy-url="<?php echo esc_attr($permalink); ?>" aria-label="<?php esc_attr_e('Copy link', 'boozed'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 256 256" fill="currentColor" aria-hidden="true"><path d="M216 32H88a8 8 0 0 0-8 8v40H40a8 8 0 0 0-8 8v128a8 8 0 0 0 8 8h128a8 8 0 0 0 8-8v-40h40a8 8 0 0 0 8-8V40a8 8 0 0 0-8-8Zm-56 176H48V96h112Zm48-48h-32V88a8 8 0 0 0-8-8H96V48h112Z"/></svg>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <?php if ($has_thumb) : ?>
        <?php
        $full_image_url = wp_get_attachment_image_url($thumb_id, 'full');
        $full_image_alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
        if ($full_image_alt === '') {
            $full_image_alt = $title_text;
        }
        ?>
        <section class="single-post-news__image-wrap max-w-section mx-auto w-full px-4 md:px-section-x overflow-hidden">
            <figure class="single-post-news__figure m-0 w-full md:h-[800px] overflow-hidden">
                <?php if ($full_image_url) : ?>
                    <?php
                    echo wp_get_attachment_image(
                        $thumb_id,
                        'full',
                        false,
                        [
                            'class'         => 'single-post-news__feat-img block w-full h-auto md:h-full object-cover aspect-[16/9]',
                            'loading'       => 'eager',
                            'fetchpriority' => 'high',
                            'decoding'      => 'async',
                            'sizes'         => '(min-width: 768px) calc(100vw - (2 * var(--section-padding-x))), calc(100vw - 2rem)',
                            'alt'           => $full_image_alt,
                        ]
                    );
                    ?>
                <?php endif; ?>
            </figure>
        </section>
    <?php endif; ?>

    <section class="single-post-news__content-wrap max-w-section mx-auto w-full px-4 md:px-section-x py-6 md:py-10">
        <div class="grid grid-cols-12 gap-y-6 md:gap-y-8">
            <div class="col-span-12 md:col-span-8 md:col-start-3">
                <div class="single-post-news__body entry-content prose prose-lg font-body text-body-md text-brand-black max-w-none pt-2 md:pt-4">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </section>
</article>

<?php if (empty($GLOBALS['boozed_single_news_copy_script'])) : $GLOBALS['boozed_single_news_copy_script'] = true; ?>
<script>
(function() {
    document.querySelectorAll('.single-post-news__share-copy').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var url = btn.getAttribute('data-copy-url');
            if (!url) return;
            function ok() {
                var prev = btn.getAttribute('aria-label');
                btn.setAttribute('aria-label', <?php echo json_encode(__('Link copied', 'boozed')); ?>);
                setTimeout(function() { btn.setAttribute('aria-label', prev || ''); }, 2500);
            }
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(ok).catch(function() {});
            } else {
                var ta = document.createElement('textarea');
                ta.value = url;
                ta.setAttribute('readonly', '');
                ta.style.position = 'fixed';
                ta.style.left = '-9999px';
                document.body.appendChild(ta);
                ta.select();
                try { document.execCommand('copy'); ok(); } catch (e) {}
                document.body.removeChild(ta);
            }
        });
    });
})();
</script>
<?php endif; ?>
