<?php
/**
 * Tabs – design system component
 * Options: tabs (array of [ id, label ]), active_id (current tab id), class
 * Markup is unstyled list; use JS or server to switch content. Active tab gets primary style.
 */
$tabs = $args['tabs'] ?? [ ['id' => 'monthly', 'label' => 'Monthly'], ['id' => 'yearly', 'label' => 'Yearly'] ];
$active_id = $args['active_id'] ?? ($tabs[0]['id'] ?? '');
$class = $args['class'] ?? '';

$base = 'inline-flex rounded border border-brand-border overflow-hidden';
$tab_base = 'px-4 py-2.5 font-body text-body font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-brand-purple focus:ring-inset';
$active = 'bg-brand-purple text-brand-white border-brand-purple';
$inactive = 'bg-brand-white text-brand-indigo border-transparent hover:bg-brand-nude';
?>
<div class="<?php echo esc_attr($base . ' ' . $class); ?>" role="tablist">
  <?php foreach ($tabs as $tab) :
    $id = $tab['id'] ?? '';
    $label = $tab['label'] ?? '';
    $is_active = $id === $active_id;
  ?>
    <button type="button" role="tab" id="tab-<?php echo esc_attr($id); ?>" aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>" aria-controls="panel-<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($tab_base . ' ' . ($is_active ? $active : $inactive)); ?>">
      <?php echo esc_html($label); ?>
    </button>
  <?php endforeach; ?>
</div>
