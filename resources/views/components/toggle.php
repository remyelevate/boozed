<?php
/**
 * Toggle – design system component
 * Variants: default, card
 * Options: name, id, label, description (for card), checked, class
 * Off state uses a red/coral tint; on state uses brand-purple.
 */
$variant = $args['variant'] ?? 'default';
$name = $args['name'] ?? '';
$id = $args['id'] ?? $name;
$label = $args['label'] ?? 'Option one';
$description = $args['description'] ?? '';
$checked = !empty($args['checked']);
$class = $args['class'] ?? '';
?>
<?php if ($variant === 'card') : ?>
<label class="flex items-center justify-between gap-4 p-4 rounded border border-brand-border bg-brand-white cursor-pointer hover:border-brand-purple/30 <?php echo esc_attr($class); ?>">
  <span>
    <span class="font-body text-body font-medium text-brand-indigo"><?php echo esc_html($label); ?></span>
    <?php if ($description) : ?>
      <span class="block mt-1 font-body text-body-sm text-gray-500"><?php echo esc_html($description); ?></span>
    <?php endif; ?>
  </span>
  <span class="relative inline-flex h-6 w-11 shrink-0 rounded-full transition-colors focus-within:ring-2 focus-within:ring-brand-purple focus-within:ring-offset-2 <?php echo $checked ? 'bg-brand-purple' : 'bg-red-400'; ?>">
    <input type="checkbox" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" role="switch" <?php checked($checked); ?> class="sr-only peer" />
    <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-brand-white shadow ring-0 transition translate-x-0.5 mt-0.5 peer-checked:translate-x-6"></span>
  </span>
</label>
<?php else : ?>
<label class="inline-flex items-center gap-3 cursor-pointer <?php echo esc_attr($class); ?>">
  <span class="relative inline-flex h-6 w-11 shrink-0 rounded-full transition-colors focus-within:ring-2 focus-within:ring-brand-purple focus-within:ring-offset-2 <?php echo $checked ? 'bg-brand-purple' : 'bg-red-400'; ?>">
    <input type="checkbox" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" role="switch" <?php checked($checked); ?> class="sr-only peer" />
    <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-brand-white shadow ring-0 transition translate-x-0.5 mt-0.5 peer-checked:translate-x-6"></span>
  </span>
  <span class="font-body text-body text-brand-indigo"><?php echo esc_html($label); ?></span>
</label>
<?php endif; ?>
