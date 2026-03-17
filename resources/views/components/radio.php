<?php
/**
 * Radio – design system component
 * Variants: default, button
 * Options: name, id, label, value, checked, class, button_icon (html for button style)
 */
$variant = $args['variant'] ?? 'default';
$name = $args['name'] ?? '';
$id = $args['id'] ?? $name;
$label = $args['label'] ?? 'radio';
$value = $args['value'] ?? '';
$checked = !empty($args['checked']);
$class = $args['class'] ?? '';
$button_icon = $args['button_icon'] ?? '';

$input_class = 'w-5 h-5 border-2 border-brand-border text-brand-purple focus:ring-2 focus:ring-brand-border-focus focus:ring-offset-0';
?>
<?php if ($variant === 'button') : ?>
<label class="flex items-center gap-3 px-4 py-3 rounded border-2 cursor-pointer transition-colors <?php echo $checked ? 'border-brand-purple bg-brand-purple text-brand-white' : 'border-brand-border bg-brand-white text-brand-indigo hover:border-brand-purple/30'; ?> <?php echo esc_attr($class); ?>">
  <?php if ($button_icon) : ?>
    <span class="shrink-0" aria-hidden="true"><?php echo $button_icon; ?></span>
  <?php endif; ?>
  <span class="font-body text-body font-medium"><?php echo esc_html($label); ?></span>
  <input type="radio" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($value); ?>" <?php checked($checked); ?> class="sr-only" />
</label>
<?php else : ?>
<label class="inline-flex items-center gap-3 cursor-pointer <?php echo esc_attr($class); ?>">
  <input type="radio" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($value); ?>" <?php checked($checked); ?> class="<?php echo esc_attr($input_class); ?> shrink-0" />
  <span class="font-body text-body text-brand-indigo"><?php echo esc_html($label); ?></span>
</label>
<?php endif; ?>
