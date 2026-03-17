<?php
/**
 * Post content section – outputs the main post body (the_content()) with prose styling.
 * Used in blog single when building the page from flexible sections.
 */
?>
<section class="single-post-content page-content bg-brand-white">
    <div class="single-post-content__inner max-w-section mx-auto px-4 md:px-section-x py-10 md:py-section-y">
        <div class="entry-content prose prose-lg font-body text-body-md text-brand-black max-w-none">
            <?php the_content(); ?>
        </div>
    </div>
</section>
