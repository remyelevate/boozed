<?php
/**
 * Breadcrumbs under page header on the Nieuws overview page (Home / Nieuws).
 *
 * Expects: $is_dark (bool)
 */

$muted = $is_dark ? 'text-brand-white/75' : 'text-brand-black/70';
$link  = $is_dark ? 'text-brand-white hover:opacity-90' : 'text-brand-indigo hover:opacity-90';
$home_label = __('Home', 'boozed');
$news_label = __('Nieuws', 'boozed');
?>
<div class="page-header__post-meta max-w-section mx-auto w-full px-4 md:px-section-x pointer-events-auto pb-8 md:pb-10">
	<nav class="page-header__breadcrumbs font-body text-body-sm <?php echo esc_attr($muted); ?>" aria-label="<?php esc_attr_e('Breadcrumb', 'boozed'); ?>">
		<ol class="flex flex-wrap items-center gap-x-2 gap-y-1 list-none m-0 p-0">
			<li class="m-0 p-0">
				<a href="<?php echo esc_url(home_url('/')); ?>" class="<?php echo esc_attr($link); ?> no-underline"><?php echo esc_html($home_label); ?></a>
			</li>
			<li class="m-0 p-0 opacity-40" aria-hidden="true">/</li>
			<li class="m-0 p-0 <?php echo esc_attr($is_dark ? 'text-brand-white' : 'text-brand-black'); ?>" aria-current="page"><?php echo esc_html($news_label); ?></li>
		</ol>
	</nav>
</div>
