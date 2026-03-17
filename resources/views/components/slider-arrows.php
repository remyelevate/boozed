<?php
/**
 * Slider arrows – design system component
 * Options: prev_label, next_label, class
 * Use as prev/next controls for a carousel or slider; hook up with your JS.
 */
$prev_label = $args['prev_label'] ?? __('Previous', 'boozed');
$next_label = $args['next_label'] ?? __('Next', 'boozed');
$class = $args['class'] ?? '';

$btn = 'inline-flex h-10 w-10 items-center justify-center rounded-full border border-brand-border bg-brand-white text-brand-indigo transition-colors hover:bg-brand-nude hover:border-brand-purple/30 focus:outline-none focus:ring-2 focus:ring-brand-purple focus:ring-offset-2';
?>
<div class="inline-flex gap-2 <?php echo esc_attr($class); ?>">
  <button type="button" class="slider-prev <?php echo esc_attr($btn); ?>" aria-label="<?php echo esc_attr($prev_label); ?>">
    <span aria-hidden="true">←</span>
  </button>
  <button type="button" class="slider-next <?php echo esc_attr($btn); ?>" aria-label="<?php echo esc_attr($next_label); ?>">
    <span aria-hidden="true">→</span>
  </button>
</div>
