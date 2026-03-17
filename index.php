<?php get_header(); ?>

<?php
if (have_posts()) {
    while (have_posts()) {
        the_post();
        the_title('<h1>', '</h1>');
        the_content();
    }
} else {
    echo '<p>' . esc_html__('No content found.', 'boozed') . '</p>';
}
?>

<?php get_footer(); ?>
