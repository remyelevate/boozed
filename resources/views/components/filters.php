<?php
/**
 * Filter chips – design system component
 * Options: filters (array of [ id, label, active ]), class
 */
$filters = $args['filters'] ?? [
  ['id' => 'all', 'label' => 'View all', 'active' => true],
  ['id' => 'c1', 'label' => 'Category one', 'active' => false],
  ['id' => 'c2', 'label' => 'Category two', 'active' => false],
];
$class = $args['class'] ?? '';

$base = 'inline-flex flex-wrap gap-2';
$pill_base = 'px-3 py-1.5 rounded font-body text-body-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-brand-purple focus:ring-offset-2';
$active_pill = 'border border-brand-border bg-brand-white text-brand-indigo';
$inactive_pill = 'border border-transparent text-brand-indigo hover:border-brand-border';
?>
<div class="<?php echo esc_attr($base . ' ' . $class); ?>">
  <?php foreach ($filters as $f) :
    $active = !empty($f['active']);
  ?>
    <button type="button" class="<?php echo esc_attr($pill_base . ' ' . ($active ? $active_pill : $inactive_pill)); ?>">
      <?php echo esc_html($f['label'] ?? ''); ?>
    </button>
  <?php endforeach; ?>
</div>
