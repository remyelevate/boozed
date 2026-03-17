<?php

namespace App;

/**
 * Load design system component with args.
 * Usage: App\Components::render('button', [ 'label' => 'Submit', 'variant' => 'primary' ]);
 */
class Components {

    public static function render(string $name, array $args = []): void {
        $path = get_template_directory() . '/resources/views/components/' . $name . '.php';
        if (!is_file($path)) {
            return;
        }
        $args = array_merge(['class' => ''], $args);
        // Use include directly so $args is in scope for the component
        include $path;
    }

    public static function get(string $name, array $args = []): string {
        ob_start();
        self::render($name, $args);
        return (string) ob_get_clean();
    }
}
