<?php
/**
 * Textarea – design system component
 * Options: name, id, value, placeholder, rows, class, required, disabled
 */
$name = $args['name'] ?? '';
$id = $args['id'] ?? $name;
$value = $args['value'] ?? '';
$placeholder = $args['placeholder'] ?? 'Type your message...';
$rows = $args['rows'] ?? 4;
$class = $args['class'] ?? '';
$required = !empty($args['required']);
$disabled = !empty($args['disabled']);

$input_class = 'w-full rounded border border-brand-border bg-brand-white px-3 py-2.5 text-brand-indigo font-body text-body placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-border-focus focus:border-brand-border-focus disabled:opacity-50 resize-y min-h-[100px] ' . $class;
?>
<textarea
  <?php if ($name) : ?>name="<?php echo esc_attr($name); ?>"<?php endif; ?>
  <?php if ($id) : ?>id="<?php echo esc_attr($id); ?>"<?php endif; ?>
  rows="<?php echo (int) $rows; ?>"
  placeholder="<?php echo esc_attr($placeholder); ?>"
  <?php if ($required) : ?>required<?php endif; ?>
  <?php if ($disabled) : ?>disabled<?php endif; ?>
  class="<?php echo esc_attr($input_class); ?>"
><?php echo esc_textarea($value); ?></textarea>
