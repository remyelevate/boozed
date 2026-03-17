    </main>

    <?php
    $footer_part = get_template_directory() . '/resources/views/partials/footer.php';
    if (is_file($footer_part)) {
        include $footer_part;
    } else {
        ?>
        <footer class="site-footer bg-brand-indigo text-brand-white py-section-y">
            <div class="max-w-section mx-auto px-section-x">
                <p class="font-body text-body-sm text-brand-white/80">&copy; <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?></p>
            </div>
        </footer>
        <?php
    }
    ?>
</div>

<?php wp_footer(); ?>
</body>
</html>
