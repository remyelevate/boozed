<?php
/**
 * Tag – design system component
 * Variants: solid (default), outline
 * Options: label, removable (bool), on_remove (optional url or false), class
 */
$variant = $args['variant'] ?? 'solid';
$label = $args['label'] ?? 'category';
$removable = !empty($args['removable']);
$on_remove = $args['on_remove'] ?? null;
$class = $args['class'] ?? '';

$base = 'inline-flex items-center gap-1.5 font-body text-body-sm font-medium rounded px-2.5 py-1';
$solid = 'bg-brand-purple text-brand-white';
$outline = 'bg-brand-white text-brand-purple border border-brand-border';

$variant_classes = [ 'solid' => $solid, 'outline' => $outline ];
$styles = $base . ' ' . ($variant_classes[$variant] ?? $solid) . ' ' . $class;
?>
<span class="<?php echo esc_attr($styles); ?>">
  <?php echo esc_html($label); ?>
  <?php if ($removable) : ?>
    <?php if ($on_remove) : ?>
      <a href="<?php echo esc_url($on_remove); ?>" class="ml-0.5 rounded p-0.5 hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-brand-white/50" aria-label="<?php esc_attr_e('Remove', 'boozed'); ?>">×</a>
    <?php else : ?>
      <button type="button" class="ml-0.5 rounded p-0.5 hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-brand-white/50 tag-remove" aria-label="<?php esc_attr_e('Remove', 'boozed'); ?>">×</button>
    <?php endif; ?>
  <?php endif; ?>
</span>
