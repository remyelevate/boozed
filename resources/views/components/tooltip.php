<?php
/**
 * Tooltip – design system component
 * Options: content (html or text), position (top-left|top-right|bottom-left|bottom-right), trigger (html for the trigger element, e.g. info icon), id, class
 * Use with JS to show/hide on focus or hover; here we output trigger + tooltip panel. Position classes set arrow.
 */
$content = $args['content'] ?? 'Lorem ipsum tooltip text.';
$position = $args['position'] ?? 'top';
$trigger = $args['trigger'] ?? '<span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-brand-indigo text-brand-white font-body text-body-xs font-medium" aria-hidden="true">i</span>';
$id = $args['id'] ?? 'tooltip-' . wp_rand(1000, 9999);
$class = $args['class'] ?? '';

$panel = 'absolute z-50 max-w-xs rounded bg-brand-purple px-3 py-2 font-body text-body-sm text-brand-white shadow-lg';
$positions = [
  'top-left'     => 'bottom-full left-0 mb-2',
  'top-right'    => 'bottom-full right-0 mb-2',
  'bottom-left'  => 'top-full left-0 mt-2',
  'bottom-right' => 'top-full right-0 mt-2',
];
$pos_class = $positions[$position] ?? $positions['top-left'];
?>
<span class="group relative inline-flex <?php echo esc_attr($class); ?>">
  <span class="cursor-help" aria-describedby="<?php echo esc_attr($id); ?>"><?php echo $trigger; ?></span>
  <span id="<?php echo esc_attr($id); ?>" role="tooltip" class="<?php echo esc_attr($panel . ' ' . $pos_class); ?> hidden group-hover:block group-focus-within:block">
    <?php echo wp_kses_post($content); ?>
  </span>
</span>
