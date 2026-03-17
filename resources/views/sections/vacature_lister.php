<?php

/**
 * Vacature lister section
 * Table layout with column headings (Locatie, Functie, Opleidingsniveau, Contract type),
 * row hover showing featured image on the right and custom cursor (blob + "Bekijk vacature").
 */

$is_archive = is_post_type_archive('vacature');

$heading        = !$is_archive && function_exists('get_sub_field') ? get_sub_field('vacature_lister_heading') : '';
$posts_per_page = !$is_archive && function_exists('get_sub_field') ? (int) get_sub_field('vacature_lister_posts_per_page') : 10;
$cursor_text    = !$is_archive && function_exists('get_sub_field') ? get_sub_field('vacature_lister_cursor_text') : 'Bekijk vacature';

$posts_per_page = max(1, min(50, $posts_per_page));
$cursor_text    = $cursor_text ?: 'Bekijk vacature';

$filter_slug = isset($_GET['locatie']) ? sanitize_text_field(wp_unslash($_GET['locatie'])) : '';

if ($is_archive) {
	global $wp_query;
	$vacature_query    = $wp_query;
	$has_vacatures     = $vacature_query->have_posts();
	$paged             = max(1, (int) get_query_var('paged', 1));
	$current_url       = get_post_type_archive_link('vacature');
	$filter_query_args = $filter_slug ? [ 'locatie' => $filter_slug ] : [];
} else {
	$paged = 1;
	if (isset($_GET['paged']) && is_numeric($_GET['paged'])) {
		$paged = max(1, (int) $_GET['paged']);
	}

	$query_args = [
		'post_type'      => 'vacature',
		'posts_per_page' => $posts_per_page,
		'paged'          => $paged,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'post_status'    => 'publish',
	];

	if ($filter_slug) {
		$query_args['tax_query'] = [
			[
				'taxonomy' => 'locatie',
				'field'    => 'slug',
				'terms'    => $filter_slug,
			],
		];
	}

	$vacature_query    = new \WP_Query($query_args);
	$has_vacatures     = $vacature_query->have_posts();
	$current_url       = get_permalink();
	$filter_query_args = $filter_slug ? [ 'locatie' => $filter_slug ] : [];
}

$locatie_terms = get_terms([
	'taxonomy'   => 'locatie',
	'hide_empty' => true,
]);
?>

<section class="vacature-lister max-w-section mx-auto px-4 md:px-section-x py-section-y">
	<!-- Filters bar -->
	<div class="vl-filters flex items-center gap-6 pb-8 md:pb-10">
		<div class="flex items-center gap-2 text-brand-black/60">
			<!-- Phosphor Funnel icon (regular weight) -->
			<svg class="vl-icon vl-icon--filter" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 256 256" fill="currentColor" aria-hidden="true"><path d="M230.6 49.53A15.81 15.81 0 0 0 216 40H40a16 16 0 0 0-11.76 26.68L96 139.32V216a16 16 0 0 0 24.87 13.32l32-21.34A16 16 0 0 0 160 194.66v-55.34l67.74-72.64a15.79 15.79 0 0 0 2.86-16.49ZM40 56h0Zm106.18 74.8A8 8 0 0 0 144 136v58.66l-32 21.34V136a8 8 0 0 0-2.16-5.47L40 56h176Z"/></svg>
			<span class="font-body text-body-sm font-medium"><?php esc_html_e('Filters', 'boozed'); ?></span>
		</div>
		<nav class="vl-filters__nav flex items-center gap-5" aria-label="<?php esc_attr_e('Vacature filters', 'boozed'); ?>">
			<a href="<?php echo esc_url($current_url); ?>" class="font-body text-body-sm font-medium no-underline transition-colors <?php echo !$filter_slug ? 'text-brand-black underline underline-offset-4' : 'text-brand-black/60 hover:text-brand-black'; ?>">
				<?php esc_html_e('Alles', 'boozed'); ?>
			</a>
			<?php if ($locatie_terms && !is_wp_error($locatie_terms)) : ?>
				<?php foreach ($locatie_terms as $term) : ?>
					<a href="<?php echo esc_url(add_query_arg('locatie', $term->slug, $current_url)); ?>" class="font-body text-body-sm font-medium no-underline transition-colors <?php echo $filter_slug === $term->slug ? 'text-brand-black underline underline-offset-4' : 'text-brand-black/60 hover:text-brand-black'; ?>">
						<?php echo esc_html($term->name); ?>
					</a>
				<?php endforeach; ?>
			<?php endif; ?>
		</nav>
	</div>

	<!-- Table with column headers -->
	<div class="vl-table vl-table--constrained vl-table__grid border-t border-brand-black/10">
		<!-- Column headings with icons -->
		<div class="vl-table__head vl-table__subgrid items-center border-b border-brand-black/10 text-brand-black/60 font-body text-body-sm font-medium text-left">
			<span class="vl-table__head-cell flex items-center gap-2 justify-start">
				<svg class="vl-icon vl-icon--head" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M12 6C11.2583 6 10.5333 6.21993 9.91661 6.63199C9.29993 7.04404 8.81928 7.62971 8.53545 8.31494C8.25162 9.00016 8.17736 9.75416 8.32205 10.4816C8.46675 11.209 8.8239 11.8772 9.34835 12.4017C9.8728 12.9261 10.541 13.2833 11.2684 13.4279C11.9958 13.5726 12.7498 13.4984 13.4351 13.2145C14.1203 12.9307 14.706 12.4501 15.118 11.8334C15.5301 11.2167 15.75 10.4917 15.75 9.75C15.75 8.75544 15.3549 7.80161 14.6517 7.09835C13.9484 6.39509 12.9946 6 12 6ZM12 12C11.555 12 11.12 11.868 10.75 11.6208C10.38 11.3736 10.0916 11.0222 9.92127 10.611C9.75097 10.1999 9.70642 9.7475 9.79323 9.31105C9.88005 8.87459 10.0943 8.47368 10.409 8.15901C10.7237 7.84434 11.1246 7.63005 11.561 7.54323C11.9975 7.45642 12.4499 7.50097 12.861 7.67127C13.2722 7.84157 13.6236 8.12996 13.8708 8.49997C14.118 8.86998 14.25 9.30499 14.25 9.75C14.25 10.3467 14.0129 10.919 13.591 11.341C13.169 11.7629 12.5967 12 12 12ZM12 1.5C9.81273 1.50248 7.71575 2.37247 6.16911 3.91911C4.62247 5.46575 3.75248 7.56273 3.75 9.75C3.75 12.6938 5.11031 15.8138 7.6875 18.7734C8.84552 20.1108 10.1489 21.3151 11.5734 22.3641C11.6995 22.4524 11.8498 22.4998 12.0037 22.4998C12.1577 22.4998 12.308 22.4524 12.4341 22.3641C13.856 21.3147 15.1568 20.1104 16.3125 18.7734C18.8859 15.8138 20.25 12.6938 20.25 9.75C20.2475 7.56273 19.3775 5.46575 17.8309 3.91911C16.2843 2.37247 14.1873 1.50248 12 1.5ZM12 20.8125C10.4503 19.5938 5.25 15.1172 5.25 9.75C5.25 7.95979 5.96116 6.2429 7.22703 4.97703C8.4929 3.71116 10.2098 3 12 3C13.7902 3 15.5071 3.71116 16.773 4.97703C18.0388 6.2429 18.75 7.95979 18.75 9.75C18.75 15.1153 13.5497 19.5938 12 20.8125Z" fill="currentColor"/></svg>
				<?php esc_html_e('Locatie', 'boozed'); ?>
			</span>
			<span class="vl-table__head-cell flex items-center gap-2 justify-start">
				<svg class="vl-icon vl-icon--head" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M7.04906 18.6C7.12789 18.6592 7.21761 18.7024 7.31312 18.7269C7.40862 18.7514 7.50802 18.7569 7.60564 18.7429C7.70325 18.729 7.79716 18.696 7.88198 18.6457C7.96681 18.5954 8.0409 18.5289 8.1 18.45C8.55409 17.8445 9.14291 17.3531 9.81983 17.0147C10.4968 16.6762 11.2432 16.5 12 16.5C12.7568 16.5 13.5032 16.6762 14.1802 17.0147C14.8571 17.3531 15.4459 17.8445 15.9 18.45C15.9591 18.5288 16.0331 18.5952 16.1179 18.6454C16.2026 18.6955 16.2964 18.7285 16.3939 18.7425C16.4914 18.7564 16.5907 18.751 16.6861 18.7265C16.7815 18.7021 16.8712 18.6591 16.95 18.6C17.0288 18.5409 17.0952 18.4669 17.1454 18.3821C17.1955 18.2974 17.2285 18.2036 17.2425 18.1061C17.2564 18.0086 17.251 17.9093 17.2265 17.8139C17.2021 17.7185 17.1591 17.6288 17.1 17.55C16.4359 16.6596 15.5503 15.9586 14.5312 15.5166C15.0897 15.0067 15.481 14.3399 15.6537 13.6037C15.8264 12.8675 15.7725 12.0962 15.4991 11.3912C15.2256 10.6862 14.7454 10.0803 14.1215 9.65304C13.4976 9.2258 12.759 8.99718 12.0028 8.99718C11.2466 8.99718 10.5081 9.2258 9.88413 9.65304C9.26019 10.0803 8.77999 10.6862 8.50655 11.3912C8.23311 12.0962 8.17922 12.8675 8.35194 13.6037C8.52466 14.3399 8.91592 15.0067 9.47437 15.5166C8.45328 15.9577 7.56567 16.6588 6.9 17.55C6.78055 17.709 6.72915 17.909 6.75711 18.1059C6.78506 18.3028 6.89008 18.4805 7.04906 18.6ZM12 10.5C12.445 10.5 12.88 10.632 13.25 10.8792C13.62 11.1264 13.9084 11.4778 14.0787 11.889C14.249 12.3001 14.2936 12.7525 14.2068 13.189C14.12 13.6254 13.9057 14.0263 13.591 14.341C13.2763 14.6557 12.8754 14.87 12.439 14.9568C12.0025 15.0436 11.5501 14.999 11.139 14.8287C10.7278 14.6584 10.3764 14.37 10.1292 14C9.88196 13.63 9.75 13.195 9.75 12.75C9.75 12.1533 9.98705 11.581 10.409 11.159C10.831 10.7371 11.4033 10.5 12 10.5ZM18.75 2.25H5.25C4.85218 2.25 4.47064 2.40804 4.18934 2.68934C3.90804 2.97064 3.75 3.35218 3.75 3.75V20.25C3.75 20.6478 3.90804 21.0294 4.18934 21.3107C4.47064 21.592 4.85218 21.75 5.25 21.75H18.75C19.1478 21.75 19.5294 21.592 19.8107 21.3107C20.092 21.0294 20.25 20.6478 20.25 20.25V3.75C20.25 3.35218 20.092 2.97064 19.8107 2.68934C19.5294 2.40804 19.1478 2.25 18.75 2.25ZM18.75 20.25H5.25V3.75H18.75V20.25ZM8.25 6C8.25 5.80109 8.32902 5.61032 8.46967 5.46967C8.61032 5.32902 8.80109 5.25 9 5.25H15C15.1989 5.25 15.3897 5.32902 15.5303 5.46967C15.671 5.61032 15.75 5.80109 15.75 6C15.75 6.19891 15.671 6.38968 15.5303 6.53033C15.3897 6.67098 15.1989 6.75 15 6.75H9C8.80109 6.75 8.61032 6.67098 8.46967 6.53033C8.32902 6.38968 8.25 6.19891 8.25 6Z" fill="currentColor"/></svg>
				<?php esc_html_e('Functie', 'boozed'); ?>
			</span>
			<span class="vl-table__head-cell flex items-center gap-2 justify-start">
				<svg class="vl-icon vl-icon--head" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M23.6023 8.33812L12.3523 2.33812C12.2438 2.28034 12.1227 2.25012 11.9998 2.25012C11.8768 2.25012 11.7558 2.28034 11.6473 2.33812L0.397266 8.33812C0.27727 8.40207 0.176916 8.49744 0.106941 8.61403C0.036965 8.73061 0 8.86402 0 9C0 9.13597 0.036965 9.26938 0.106941 9.38597C0.176916 9.50255 0.27727 9.59792 0.397266 9.66187L2.99977 11.0503V15.5897C2.99899 15.9581 3.13457 16.3137 3.38039 16.5881C4.60852 17.9559 7.36008 20.25 11.9998 20.25C13.5382 20.2627 15.065 19.9841 16.4998 19.4287V22.5C16.4998 22.6989 16.5788 22.8897 16.7194 23.0303C16.8601 23.171 17.0509 23.25 17.2498 23.25C17.4487 23.25 17.6394 23.171 17.7801 23.0303C17.9207 22.8897 17.9998 22.6989 17.9998 22.5V18.7041C18.9778 18.1395 19.8616 17.4256 20.6191 16.5881C20.865 16.3137 21.0005 15.9581 20.9998 15.5897V11.0503L23.6023 9.66187C23.7223 9.59792 23.8226 9.50255 23.8926 9.38597C23.9626 9.26938 23.9995 9.13597 23.9995 9C23.9995 8.86402 23.9626 8.73061 23.8926 8.61403C23.8226 8.49744 23.7223 8.40207 23.6023 8.33812ZM11.9998 18.75C7.9432 18.75 5.55727 16.7681 4.49977 15.5897V11.85L11.6473 15.6619C11.7558 15.7197 11.8768 15.7499 11.9998 15.7499C12.1227 15.7499 12.2438 15.7197 12.3523 15.6619L16.4998 13.4503V17.7947C15.3185 18.3459 13.8298 18.75 11.9998 18.75ZM19.4998 15.5859C19.0502 16.0848 18.5472 16.5328 17.9998 16.9219V12.6497L19.4998 11.85V15.5859ZM17.6248 11.1506L17.6041 11.1384L12.3541 8.33812C12.179 8.24866 11.9757 8.23158 11.7881 8.29056C11.6004 8.34955 11.4435 8.47989 11.351 8.65349C11.2586 8.82709 11.238 9.03006 11.2938 9.21868C11.3495 9.40729 11.4771 9.56645 11.6491 9.66187L16.031 12L11.9998 14.1497L2.34352 9L11.9998 3.85031L21.656 9L17.6248 11.1506Z" fill="currentColor"/></svg>
				<?php esc_html_e('Opleidingsniveau', 'boozed'); ?>
			</span>
			<span class="vl-table__head-cell flex items-center gap-2 justify-start">
				<svg class="vl-icon vl-icon--head" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M20.0306 6.21938L16.2806 2.46938C16.2109 2.39975 16.1282 2.34454 16.0371 2.3069C15.9461 2.26926 15.8485 2.24992 15.75 2.25H8.25C7.85218 2.25 7.47064 2.40804 7.18934 2.68934C6.90804 2.97064 6.75 3.35218 6.75 3.75V5.25H5.25C4.85218 5.25 4.47064 5.40804 4.18934 5.68934C3.90804 5.97064 3.75 6.35218 3.75 6.75V20.25C3.75 20.6478 3.90804 21.0294 4.18934 21.3107C4.47064 21.592 4.85218 21.75 5.25 21.75H15.75C16.1478 21.75 16.5294 21.592 16.8107 21.3107C17.092 21.0294 17.25 20.6478 17.25 20.25V18.75H18.75C19.1478 18.75 19.5294 18.592 19.8107 18.3107C20.092 18.0294 20.25 17.6478 20.25 17.25V6.75C20.2501 6.65148 20.2307 6.55391 20.1931 6.46286C20.1555 6.37182 20.1003 6.28908 20.0306 6.21938ZM15.75 20.25H5.25V6.75H12.4397L15.75 10.0603V17.985C15.75 17.9906 15.75 17.9953 15.75 18C15.75 18.0047 15.75 18.0094 15.75 18.015V20.25ZM18.75 17.25H17.25V9.75C17.2501 9.65148 17.2307 9.55391 17.1931 9.46286C17.1555 9.37182 17.1003 9.28908 17.0306 9.21937L13.2806 5.46938C13.2109 5.39975 13.1282 5.34454 13.0371 5.3069C12.9461 5.26926 12.8485 5.24992 12.75 5.25H8.25V3.75H15.4397L18.75 7.06031V17.25ZM13.5 14.25C13.5 14.4489 13.421 14.6397 13.2803 14.7803C13.1397 14.921 12.9489 15 12.75 15H8.25C8.05109 15 7.86032 14.921 7.71967 14.7803C7.57902 14.6397 7.5 14.4489 7.5 14.25C7.5 14.0511 7.57902 13.8603 7.71967 13.7197C7.86032 13.579 8.05109 13.5 8.25 13.5H12.75C12.9489 13.5 13.1397 13.579 13.2803 13.7197C13.421 13.8603 13.5 14.0511 13.5 14.25ZM13.5 17.25C13.5 17.4489 13.421 17.6397 13.2803 17.7803C13.1397 17.921 12.9489 18 12.75 18H8.25C8.05109 18 7.86032 17.921 7.71967 17.7803C7.57902 17.6397 7.5 17.4489 7.5 17.25C7.5 17.0511 7.57902 16.8603 7.71967 16.7197C7.86032 16.579 8.05109 16.5 8.25 16.5H12.75C12.9489 16.5 13.1397 16.579 13.2803 16.7197C13.421 16.8603 13.5 17.0511 13.5 17.25Z" fill="currentColor"/></svg>
				<?php esc_html_e('Contract type', 'boozed'); ?>
			</span>
		</div>

		<?php
		if ($has_vacatures) :
			while ($vacature_query->have_posts()) :
				$vacature_query->the_post();
				$permalink = get_permalink();
				$title     = get_the_title();
				$locatie   = get_the_terms(get_the_ID(), 'locatie');
				$niveau    = get_the_terms(get_the_ID(), 'niveau');
				$dienst    = get_the_terms(get_the_ID(), 'dienstverband');
				$locatie_name = ($locatie && !is_wp_error($locatie) && !empty($locatie)) ? $locatie[0]->name : '';
				$niveau_name = ($niveau && !is_wp_error($niveau) && !empty($niveau)) ? $niveau[0]->name : '';
				$dienst_name = ($dienst && !is_wp_error($dienst) && !empty($dienst)) ? $dienst[0]->name : '';
				$thumb_id = get_post_thumbnail_id();
				$img_url  = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'large') : '';
		?>
		<a href="<?php echo esc_url($permalink); ?>"
		   class="vl-table__row vl-table__subgrid relative border-b border-brand-black/10 no-underline transition-colors duration-200 group"
		   data-featured-url="<?php echo $img_url ? esc_url($img_url) : ''; ?>">

			<span class="vl-table__cell font-body text-body-sm text-brand-black/50 group-hover:text-brand-white/70 transition-colors"><?php echo esc_html($locatie_name); ?></span>
			<h3 class="vl-table__cell vl-table__cell--title font-heading font-bold text-h4 md:text-h3 text-brand-black group-hover:text-brand-white transition-colors m-0 pr-4 min-w-0"><span class="vl-table__title-inner block min-w-0 truncate"><?php echo esc_html($title); ?></span></h3>
			<span class="vl-table__cell font-body text-body-sm text-brand-black/50 group-hover:text-brand-white/70 transition-colors"><?php echo esc_html($niveau_name); ?></span>
			<span class="vl-table__cell font-body text-body-sm font-medium text-brand-black group-hover:text-brand-white transition-colors whitespace-nowrap"><?php echo esc_html($dienst_name); ?></span>

			<?php if ($img_url) : ?>
			<div class="vl-table__image absolute top-1/2 -translate-y-1/2 overflow-visible opacity-0 scale-95 transition-all duration-300 z-10 shadow-2xl group-hover:opacity-100 group-hover:scale-100">
				<img src="<?php echo esc_url($img_url); ?>" alt="" class="w-full h-full object-cover" loading="lazy">
			</div>
			<?php endif; ?>
		</a>
		<?php
			endwhile;
			wp_reset_postdata();
		endif;
		?>
	</div>

	<?php
	if ($has_vacatures && $vacature_query->max_num_pages > 1) {
		$query      = $vacature_query;
		$query_args = $filter_query_args;
		include get_template_directory() . '/resources/views/partials/pagination.php';
	}
	?>

	<!-- Custom cursor (blob shape + label). Moved to body by JS to avoid parent clipping. -->
	<div id="vl-custom-cursor" class="pointer-events-none fixed z-[9999]" style="display:none;width:75px;height:75px;left:0;top:0;" aria-hidden="true">
		<svg class="absolute inset-0 w-full h-full" width="75" height="75" viewBox="0 0 75 75" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M74.8375 36.6221C74.8375 62.8972 56.1594 75 36.849 75C17.5387 75 -1.70639 58.5075 0.120568 36.6221C0.120568 17.4006 13.3504 3.82746e-06 40.503 0C59.8133 0 76.8534 10.347 74.8375 36.6221Z" fill="#0C0A21"/>
		</svg>
		<span class="absolute inset-0 flex items-center justify-center text-brand-white font-body text-body-xs font-medium leading-tight text-center px-2"><?php echo esc_html($cursor_text); ?></span>
	</div>
</section>

<?php if ($has_vacatures && empty($GLOBALS['boozed_vacature_lister_script_printed'])) : $GLOBALS['boozed_vacature_lister_script_printed'] = true;
	$vl_script = <<<'JSEOF'
(function(){
function init(){
if(typeof gsap==="undefined")return;
var table=document.querySelector(".vacature-lister .vl-table");
var rows=document.querySelectorAll(".vacature-lister .vl-table__row");
var images=document.querySelectorAll(".vacature-lister .vl-table__image");
var cursor=document.getElementById("vl-custom-cursor");
if(!table||!cursor)return;
if(cursor.parentNode!==document.body){document.body.appendChild(cursor);}
var cursorActive=false;
var hideCursorTimeout=null;
var HIDE_DELAY_MS=120;
function cancelHideCursor(){if(hideCursorTimeout){clearTimeout(hideCursorTimeout);hideCursorTimeout=null;}}
function showCursor(){
if(!cursor||cursorActive)return;
cursorActive=true;
cursor.style.display="block";
gsap.fromTo(cursor,{scale:0.4,opacity:0},{scale:1,opacity:1,duration:0.3,ease:"back.out(1.7)"});
}
function hideCursor(){if(!cursor)return;cancelHideCursor();cursorActive=false;gsap.to(cursor,{scale:0.4,opacity:0,duration:0.2,ease:"power2.in",onComplete:function(){cursor.style.display="none";}});}
function moveCursor(e){if(!cursor)return;gsap.to(cursor,{x:e.clientX-37,y:e.clientY-37,duration:0.15,ease:"power2.out"});}
table.addEventListener("mouseenter",function(ev){cancelHideCursor();showCursor();moveCursor(ev);});
table.addEventListener("mouseleave",function(){hideCursorTimeout=setTimeout(hideCursor,HIDE_DELAY_MS);});
table.addEventListener("mousemove",moveCursor);
rows.forEach(function(row){row.addEventListener("mouseenter",function(ev){cancelHideCursor();showCursor();moveCursor(ev);});var url=row.getAttribute("data-featured-url");if(url){row.addEventListener("mouseenter",function(){var img=new Image();img.src=url;},{once:true});}});
images.forEach(function(img){img.addEventListener("mouseenter",function(ev){cancelHideCursor();showCursor();moveCursor(ev);});img.addEventListener("mousemove",moveCursor);});
}
if(document.readyState==="loading"){document.addEventListener("DOMContentLoaded",init);}else{init();}
})();
JSEOF;
	wp_add_inline_script('gsap', $vl_script, 'after');
?>
<style>
/* Vacature lister – icon size (match column heading text height) */
.vl-icon {
	flex-shrink: 0;
}
.vl-icon--filter,
.vl-icon--head {
	width: 24px;
	height: 24px;
	min-width: 24px;
	min-height: 24px;
}
/* Table layout; overflow visible so hover image can extend outside */
.vl-table--constrained {
	width: 100%;
	min-width: 0;
	overflow: visible;
}
/* Single grid container: head and rows share the same column definitions so title stays constrained.
   Title gets 2fr, last two columns get 1fr each so they aren’t tight. */
.vl-table__grid {
	display: grid;
	grid-template-columns: 180px minmax(0, 2fr) minmax(110px, 1fr) minmax(110px, 1fr);
	grid-auto-rows: auto;
}
.vl-table__subgrid {
	display: grid;
	grid-column: 1 / -1;
	grid-template-columns: subgrid;
	min-width: 0;
}
@supports not (grid-template-columns: subgrid) {
	.vl-table__grid {
		display: block;
	}
	.vl-table__subgrid {
		display: grid;
		grid-column: unset;
		grid-template-columns: 180px minmax(0, 2fr) minmax(110px, 1fr) minmax(110px, 1fr);
	}
	@media (max-width: 767px) {
		.vl-table__subgrid {
			grid-template-columns: 1fr;
		}
	}
}
/* Vacature lister – header row (content aligned to left of each column) */
.vl-table__head {
	min-height: 56px;
	padding: 0.75rem 1rem;
	text-align: left;
}
.vl-table__head > * {
	justify-self: start;
	text-align: left;
}
@media (min-width: 768px) {
	.vl-table__head {
		height: 64px;
		padding: 0.75rem 70px;
	}
}
.vl-table__head-cell {
	text-align: left;
}
/* Vacature lister – row layout (each cell content aligned to left edge of column) */
.vl-table__row {
	align-items: center;
	min-height: 80px;
	padding: 1.25rem 1rem;
	text-align: left;
	width: 100%;
}
.vl-table__cell {
	justify-self: start;
	text-align: left;
	min-width: 0;
}
/* Title cell: stay within column and truncate with ellipsis */
.vl-table__cell--title {
	justify-self: stretch;
	min-width: 0;
	max-width: 100%;
	overflow: hidden;
}
.vl-table__title-inner {
	display: block;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
}
@media (max-width: 767px) {
	.vl-table__title-inner {
		overflow: visible;
		text-overflow: unset;
		white-space: normal;
	}
}
@media (min-width: 768px) {
	.vl-table__row {
		height: 128px;
		padding: 0 70px;
	}
}
.vl-table__row:hover {
	background-color: #312783;
}
.vl-table,
.vl-table *,
.vl-table__image,
.vl-table__image * {
	cursor: none;
}
.vl-table__image {
	right: -20px;
	width: 240px;
	height: 300px;
}
@media (min-width: 768px) {
	.vl-table__image {
		right: -40px;
		width: 388px;
		height: 484px;
	}
}
@media (max-width: 767px) {
	.vl-table__grid {
		grid-template-columns: 1fr;
	}
	.vl-table__head {
		display: none;
	}
	.vl-table__row {
		gap: 0.25rem;
	}
	.vl-table__row span:last-of-type {
		text-align: left;
	}
	.vl-table__image {
		display: none;
	}
	.vl-table,
	.vl-table * {
		cursor: pointer !important;
	}
}
</style>
<?php endif; ?>
