<?php
/**
 * Select – design system component
 * Options: name, id, options (array of value => label), selected, placeholder, icon_left, class, required, disabled
 */
$name = $args['name'] ?? '';
$id = $args['id'] ?? $name;
$options = $args['options'] ?? [];
$selected = $args['selected'] ?? '';
$placeholder = $args['placeholder'] ?? 'Select some...';
$icon_left = $args['icon_left'] ?? '';
$class = $args['class'] ?? '';
$required = !empty($args['required']);
$disabled = !empty($args['disabled']);

$wrapper = 'flex rounded border border-brand-border bg-brand-white text-brand-indigo font-body text-body focus-within:ring-2 focus-within:ring-brand-border-focus focus-within:border-brand-border-focus overflow-hidden';
$select_class = 'w-full min-w-0 pl-3 pr-10 py-2.5 bg-transparent border-0 focus:outline-none focus:ring-0 appearance-none bg-no-repeat bg-[length:1.25rem] bg-[right_0.5rem_center] disabled:opacity-50 ' . $class;
?>
<div class="<?php echo esc_attr($wrapper); ?>">
  <?php if ($icon_left) : ?>
    <span class="inline-flex items-center pl-3 text-gray-400" aria-hidden="true"><?php echo esc_html($icon_left); ?></span>
  <?php endif; ?>
  <select
    <?php if ($name) : ?>name="<?php echo esc_attr($name); ?>"<?php endif; ?>
    <?php if ($id) : ?>id="<?php echo esc_attr($id); ?>"<?php endif; ?>
    <?php if ($required) : ?>required<?php endif; ?>
    <?php if ($disabled) : ?>disabled<?php endif; ?>
    class="<?php echo esc_attr($select_class); ?>"
    style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%236B7280%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%222%22 d=%22M19 9l-7 7-7-7%22/%3E%3C/svg%3E');"
  >
    <option value="" <?php selected($selected, ''); ?>><?php echo esc_html($placeholder); ?></option>
    <?php foreach ($options as $value => $label) : ?>
      <option value="<?php echo esc_attr($value); ?>" <?php selected($selected, $value); ?>><?php echo esc_html($label); ?></option>
    <?php endforeach; ?>
  </select>
</div>
