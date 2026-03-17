<?php

/**
 * Spacer section – vertical gap. Height configurable via ACF, default 68px.
 */

$height = function_exists('get_sub_field') ? get_sub_field('spacer_height') : null;
$height = $height !== null && $height !== '' ? (int) $height : 68;
$height = max(0, $height);
?>
<div class="section section-spacer" style="height: <?php echo (int) $height; ?>px;" aria-hidden="true"></div>
