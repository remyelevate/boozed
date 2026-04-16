<?php
/**
 * Button – design system component
 * Variants: primary (default), coral, outline, icon-only
 * Options: icon_left, icon_right, icon_left_html, icon_right_html, href (for link), type (for button), label, class
 */

// Ensure $args is available (passed via load_template third parameter)
$args = $args ?? [];

$variant = $args['variant'] ?? 'primary';
$label = $args['label'] ?? 'Button';
$href = $args['href'] ?? '';
$type = $args['type'] ?? 'button';
$name = $args['name'] ?? '';
$icon_left = $args['icon_left'] ?? '';
$icon_right = $args['icon_right'] ?? '';
$icon_left_html = $args['icon_left_html'] ?? '';
$icon_right_html = $args['icon_right_html'] ?? '';
$class = $args['class'] ?? '';
$vacature_sollicitatie_modal = !empty($args['vacature_sollicitatie_modal']);
$is_link = $href !== '';
$is_icon_only = $variant === 'icon-only';

// esc_url() strips or breaks fragment-only / query-only hrefs (e.g. #solliciteren)
$href_output = '';
if ($is_link) {
	$t = trim((string) $href);
	if ($t !== '' && ($t[0] === '#' || $t[0] === '?')) {
		$href_output = esc_attr($t);
	} else {
		$href_output = esc_url($href);
	}
	if ($href_output === '') {
		$is_link = false;
	}
}

$base = 'inline-flex items-center justify-center gap-2 font-body text-body font-medium rounded-none transition-colors focus:outline-none focus:ring-2 focus:ring-brand-purple focus:ring-offset-2 disabled:opacity-50';
$coral_styles   = '!bg-brand-coral text-brand-white hover:opacity-90';
$primary_styles = 'bg-brand-purple text-brand-white hover:opacity-90';
$outline_styles = 'bg-brand-white text-brand-purple border-2 border-brand-purple hover:bg-brand-nude';
$icon_only_classes = 'p-2.5 rounded-none';

$variant_classes = [
  'primary'   => $primary_styles,
  'coral'     => $coral_styles,
  'outline'   => $outline_styles,
  'icon-only' => $primary_styles . ' ' . $icon_only_classes,
];

$styles = $base . ' ' . ($variant_classes[$variant] ?? $primary_styles);
if ($variant !== 'icon-only') {
  $styles .= ' px-5 py-2.5';
}
$styles .= ' ' . $class;

$content = '';
if (($icon_left || $icon_left_html) && !$is_icon_only) {
  $content .= '<span class="shrink-0 inline-flex" aria-hidden="true">' . ($icon_left_html ? $icon_left_html : esc_html($icon_left)) . '</span>';
}
$content .= $is_icon_only ? '<span class="sr-only">' . esc_html($label) . '</span>' : esc_html($label);
if (($icon_right || $icon_right_html) && !$is_icon_only) {
	$content .= '<span class="shrink-0 inline-flex" aria-hidden="true">' . ($icon_right_html ? $icon_right_html : esc_html($icon_right)) . '</span>';
}
if ($is_icon_only) {
  $content = '<span aria-hidden="true">' . ($icon_right_html ? $icon_right_html : ($icon_left_html ? $icon_left_html : esc_html($icon_right ?: $icon_left))) . '</span>';
}
?>
<?php if ($is_link) : ?>
<a href="<?php echo $href_output; ?>" class="<?php echo esc_attr($styles); ?>"<?php echo $vacature_sollicitatie_modal ? ' data-vacature-sollicitatie-open' : ''; ?>>
  <?php echo $content; ?>
</a>
<?php else : ?>
<button type="<?php echo esc_attr($type); ?>" <?php echo $name !== '' ? ' name="' . esc_attr($name) . '"' : ''; ?> class="<?php echo esc_attr($styles); ?>">
  <?php echo $content; ?>
</button>
<?php endif; ?>
