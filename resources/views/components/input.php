<?php
/**
 * Text input – design system component
 * Options: name, id, value, placeholder, type (text|search), prefix, suffix, icon_left, icon_right, class, required, disabled
 */
$name = $args['name'] ?? '';
$id = $args['id'] ?? $name;
$value = $args['value'] ?? '';
$placeholder = $args['placeholder'] ?? 'Placeholder';
$type = $args['type'] ?? 'text';
$prefix = $args['prefix'] ?? '';
$suffix = $args['suffix'] ?? '';
$icon_left = $args['icon_left'] ?? '';
$icon_right = $args['icon_right'] ?? '';
$class = $args['class'] ?? '';
$required = !empty($args['required']);
$disabled = !empty($args['disabled']);

$wrapper = 'flex rounded border border-brand-border bg-brand-white text-brand-indigo font-body text-body focus-within:ring-2 focus-within:ring-brand-border-focus focus-within:border-brand-border-focus overflow-hidden';
$input_class = 'w-full min-w-0 px-3 py-2.5 bg-transparent border-0 focus:outline-0 focus:ring-0 placeholder:text-gray-400 disabled:opacity-50 ' . $class;
?>
<div class="<?php echo esc_attr($wrapper); ?>">
  <?php if ($prefix) : ?>
    <span class="inline-flex items-center px-3 py-2.5 bg-brand-nude border-r border-brand-border text-body text-gray-500 font-medium"><?php echo esc_html($prefix); ?></span>
  <?php endif; ?>
  <?php if ($icon_left) : ?>
    <span class="inline-flex items-center pl-3 text-gray-400" aria-hidden="true"><?php echo esc_html($icon_left); ?></span>
  <?php endif; ?>
  <input
    type="<?php echo esc_attr($type); ?>"
    <?php if ($name) : ?>name="<?php echo esc_attr($name); ?>"<?php endif; ?>
    <?php if ($id) : ?>id="<?php echo esc_attr($id); ?>"<?php endif; ?>
    value="<?php echo esc_attr($value); ?>"
    placeholder="<?php echo esc_attr($placeholder); ?>"
    <?php if ($required) : ?>required<?php endif; ?>
    <?php if ($disabled) : ?>disabled<?php endif; ?>
    class="<?php echo esc_attr($input_class); ?>"
  />
  <?php if ($icon_right) : ?>
    <span class="inline-flex items-center pr-3 text-gray-400" aria-hidden="true"><?php echo esc_html($icon_right); ?></span>
  <?php endif; ?>
  <?php if ($suffix) : ?>
    <span class="inline-flex items-center px-3 py-2.5 bg-brand-nude border-l border-brand-border text-body text-gray-500 font-medium"><?php echo esc_html($suffix); ?></span>
  <?php endif; ?>
</div>
