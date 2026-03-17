<?php

/**
 * YouTube embed section
 * Fields: youtube_embed_url, youtube_embed_caption
 */

$url     = function_exists('get_sub_field') ? (string) get_sub_field('youtube_embed_url') : '';
$caption = function_exists('get_sub_field') ? (string) get_sub_field('youtube_embed_caption') : '';

// Parse video ID from common YouTube URL formats
$video_id = '';
if ($url !== '') {
    if (preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/)([a-zA-Z0-9_-]{11})#', $url, $m)) {
        $video_id = $m[1];
    }
}

if ($video_id === '') {
    return;
}

$embed_url = 'https://www.youtube.com/embed/' . $video_id . '?rel=0';
?>

<section class="section section-youtube-embed max-w-section mx-auto px-4 md:px-section-x py-10 md:py-section-y">
    <div class="section-youtube-embed__inner">
        <div class="section-youtube-embed__video relative w-full aspect-video rounded-lg overflow-hidden bg-brand-border">
            <iframe
                src="<?php echo esc_url($embed_url); ?>"
                title="<?php echo $caption ? esc_attr($caption) : esc_attr__('YouTube video', 'boozed'); ?>"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen
                class="absolute inset-0 w-full h-full"
           ></iframe>
        </div>
        <?php if ($caption !== '') : ?>
            <p class="section-youtube-embed__caption mt-3 text-body-sm text-brand-muted">
                <?php echo esc_html($caption); ?>
            </p>
        <?php endif; ?>
    </div>
</section>
