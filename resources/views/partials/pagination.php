<?php
/**
 * Reusable pagination partial.
 * Expects: $query (WP_Query). Optionally: $paged (int), $query_args (array of key=>value to preserve in URLs).
 * Outputs prev/next arrows and page numbers, centered.
 */

if (empty($query) || !($query instanceof \WP_Query) || $query->max_num_pages <= 1) {
	return;
}

$paged = isset($paged) ? max(1, (int) $paged) : max(1, (int) get_query_var('paged', 1));
$total = (int) $query->max_num_pages;
$query_args = isset($query_args) && is_array($query_args) ? array_filter($query_args) : [];

$base = str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999)));

// On a static page with sections, use permalink + paged=%#% and any preserved query args
if (is_singular()) {
	$base = add_query_arg(array_merge($query_args, [ 'paged' => '%#%' ]), get_permalink());
	$base = str_replace('%#%', '999999999', $base);
	$base = str_replace('999999999', '%#%', esc_url($base));
}

$links = paginate_links([
	'base'      => $base,
	'format'    => '',
	'current'   => $paged,
	'total'     => $total,
	'prev_text' => '←',
	'next_text' => '→',
	'type'      => 'array',
]);

if (empty($links)) {
	return;
}

// On singular page (section on a page), build prev/next from permalink; otherwise use WP helpers
if (is_singular()) {
	$url_base = get_permalink();
	if (!empty($query_args)) {
		$url_base = add_query_arg($query_args, $url_base);
	}
	$prev = $paged > 1 ? add_query_arg('paged', $paged - 1, $url_base) : '';
	$next = $paged < $total ? add_query_arg('paged', $paged + 1, $url_base) : '';
} else {
	$prev = get_previous_posts_page_link();
	$next = get_next_posts_page_link();
}
?>
<nav class="pagination flex items-center justify-center gap-2 py-8 md:py-12" aria-label="<?php esc_attr_e('Pagination', 'boozed'); ?>">
	<?php if ($prev) : ?>
		<a href="<?php echo esc_url($prev); ?>" class="pagination__prev flex h-10 w-10 items-center justify-center rounded-full text-brand-black hover:bg-brand-border focus:outline-none focus:ring-2 focus:ring-brand-purple focus:ring-offset-2" aria-label="<?php esc_attr_e('Previous page', 'boozed'); ?>">←</a>
	<?php else : ?>
		<span class="pagination__prev-placeholder flex h-10 w-10 items-center justify-center rounded-full text-brand-border" aria-hidden="true">←</span>
	<?php endif; ?>

	<ul class="pagination__list flex items-center gap-1">
		<?php foreach ($links as $link) : ?>
			<li class="pagination__item"><?php echo $link; ?></li>
		<?php endforeach; ?>
	</ul>

	<?php if ($next) : ?>
		<a href="<?php echo esc_url($next); ?>" class="pagination__next flex h-10 w-10 items-center justify-center rounded-full text-brand-black hover:bg-brand-border focus:outline-none focus:ring-2 focus:ring-brand-purple focus:ring-offset-2" aria-label="<?php esc_attr_e('Next page', 'boozed'); ?>">→</a>
	<?php else : ?>
		<span class="pagination__next-placeholder flex h-10 w-10 items-center justify-center rounded-full text-brand-border" aria-hidden="true">→</span>
	<?php endif; ?>
</nav>

<style>
.pagination__list { list-style: none; margin: 0; padding: 0; }
.pagination__list a,
.pagination__list span {
	display: flex;
	align-items: center;
	justify-content: center;
	min-width: 2.5rem;
	height: 2.5rem;
	border-radius: 9999px;
	font-size: 0.875rem;
	font-weight: 500;
	text-decoration: none;
	color: inherit;
}
.pagination__list a:hover { background: var(--color-brand-border, #e5e7eb); }
.pagination__list .current {
	background: var(--color-brand-purple, #312783);
	color: #fff;
}
</style>
