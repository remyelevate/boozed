<?php
/**
 * 404 template: only a page header with title, content and CTAs (Contact + Over boozed).
 */

get_header();

$title           = __( 'Oeps… verkeerde afslag.', 'boozed' );
$content         = __( 'Deze pagina is even spoorloos, maar jouw event hoeft dat niet te zijn. Check het menu of neem contact op—dan helpen we je zo verder.', 'boozed' );
$primary_label   = __( 'Contact', 'boozed' );
$primary_url     = home_url( '/contact/' );
$secondary_label = __( 'Over boozed', 'boozed' );
$secondary_url   = home_url( '/over-boozed/' );

include get_template_directory() . '/resources/views/404_page_header.php';
?>
<div class="section section-spacer" style="height: 68px;" aria-hidden="true"></div>
<?php
get_footer();
