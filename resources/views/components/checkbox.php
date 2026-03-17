<?php
/**
 * Checkbox – design system component
 * Variants: default, card
 * Options: name, id, label, description (for card), checked, value, class, card_icon (html for card left icon)
 */
$variant = $args['variant'] ?? 'default';
$name = $args['name'] ?? '';
$id = $args['id'] ?? $name;
$label = $args['label'] ?? 'checkbox';
$description = $args['description'] ?? '';
$checked = !empty($args['checked']);
$value = $args['value'] ?? '1';
$class = $args['class'] ?? '';
$card_icon = $args['card_icon'] ?? '';

$input_class = 'w-5 h-5 rounded border-2 border-brand-border text-brand-purple focus:ring-2 focus:ring-brand-border-focus focus:ring-offset-0';
?>
<?php if ($variant === 'card') : ?>
<label class="flex items-start gap-4 p-4 rounded border border-brand-border bg-brand-white cursor-pointer hover:border-brand-purple/30 <?php echo esc_attr($class); ?>">
  <?php if ($card_icon) : ?>
    <span class="shrink-0 text-brand-purple" aria-hidden="true"><?php echo $card_icon; ?></span>
  <?php endif; ?>
  <span class="flex-1 min-w-0">
    <span class="font-body text-body font-medium text-brand-indigo"><?php echo esc_html($label); ?></span>
    <?php if ($description) : ?>
      <span class="block mt-1 font-body text-body-sm text-gray-500"><?php echo esc_html($description); ?></span>
    <?php endif; ?>
  </span>
  <input type="checkbox" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($value); ?>" <?php checked($checked); ?> class="<?php echo esc_attr($input_class); ?> mt-0.5 shrink-0" />
</label>
<?php else : ?>
<label class="inline-flex items-center gap-3 cursor-pointer <?php echo esc_attr($class); ?>">
  <input type="checkbox" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($value); ?>" <?php checked($checked); ?> class="<?php echo esc_attr($input_class); ?> shrink-0" />
  <span class="font-body text-body text-brand-indigo"><?php echo esc_html($label); ?></span>
</label>
<?php endif; ?>
