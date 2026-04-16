<?php
/**
 * Post meta under page header marquee (single blog posts).
 *
 * Expects: $post_id (int), $is_dark (bool)
 */

$post_id = isset($post_id) ? (int) $post_id : 0;
if ($post_id <= 0) {
	return;
}
$post = get_post($post_id);
if (!$post || $post->post_type !== 'post') {
	return;
}

$muted = $is_dark ? 'text-brand-white/75' : 'text-brand-black/70';
$link  = $is_dark ? 'text-brand-white hover:opacity-90' : 'text-brand-indigo hover:opacity-90';

$date_display = get_the_date(get_option('date_format'), $post_id);
$date_attr    = get_the_date('c', $post_id);
$author_id    = (int) $post->post_author;
$author_name  = $author_id ? get_the_author_meta('display_name', $author_id) : '';
$read_mins    = function_exists('boozed_post_reading_time_minutes') ? boozed_post_reading_time_minutes($post_id) : 1;
$news_url     = function_exists('boozed_news_index_url') ? boozed_news_index_url() : home_url('/');
$news_label   = __('Nieuws', 'boozed');
$home_label   = __('Home', 'boozed');
$title_text   = get_the_title($post_id);
?>
<div class="page-header__post-meta max-w-section mx-auto w-full px-4 md:px-section-x pointer-events-auto pb-8 md:pb-10">
	<div class="font-body text-body-sm <?php echo esc_attr($muted); ?> flex flex-wrap items-center gap-x-3 gap-y-1">
		<?php if ($date_display !== '') : ?>
			<time class="whitespace-nowrap" datetime="<?php echo esc_attr($date_attr); ?>"><?php echo esc_html($date_display); ?></time>
		<?php endif; ?>
		<?php if ($author_name !== '') : ?>
			<?php if ($date_display !== '') : ?>
				<span class="opacity-40" aria-hidden="true">·</span>
			<?php endif; ?>
			<span class="whitespace-nowrap"><?php echo esc_html($author_name); ?></span>
		<?php endif; ?>
		<?php if ($read_mins > 0) : ?>
			<?php if ($date_display !== '' || $author_name !== '') : ?>
				<span class="opacity-40" aria-hidden="true">·</span>
			<?php endif; ?>
			<span class="whitespace-nowrap"><?php echo esc_html(sprintf(__('%d min leestijd', 'boozed'), $read_mins)); ?></span>
		<?php endif; ?>
	</div>
	<nav class="page-header__breadcrumbs mt-4 font-body text-body-sm <?php echo esc_attr($muted); ?>" aria-label="<?php esc_attr_e('Breadcrumb', 'boozed'); ?>">
		<ol class="flex flex-wrap items-center gap-x-2 gap-y-1 list-none m-0 p-0">
			<li class="m-0 p-0">
				<a href="<?php echo esc_url(home_url('/')); ?>" class="<?php echo esc_attr($link); ?> no-underline"><?php echo esc_html($home_label); ?></a>
			</li>
			<li class="m-0 p-0 opacity-40" aria-hidden="true">/</li>
			<li class="m-0 p-0">
				<a href="<?php echo esc_url($news_url); ?>" class="<?php echo esc_attr($link); ?> no-underline"><?php echo esc_html($news_label); ?></a>
			</li>
			<li class="m-0 p-0 opacity-40" aria-hidden="true">/</li>
			<li class="m-0 p-0 min-w-0" aria-current="page">
				<span class="line-clamp-2 break-words <?php echo esc_attr($is_dark ? 'text-brand-white' : 'text-brand-black'); ?>"><?php echo esc_html($title_text); ?></span>
			</li>
		</ol>
	</nav>
</div>
