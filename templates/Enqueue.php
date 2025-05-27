<?php
namespace MyFramework\Core;

class Enqueue {
    private static $styles = [];
    private static $scripts = [];

    public static function load_default_frameworks() {
        // Estilos predeterminados: Bootstrap CSS
        self::enqueue_style('bootstrap', '/framework-php/frontend/assets/bootstrap/css/bootstrap.min.css');

        // Scripts predeterminados: jQuery y Bootstrap JS
        self::enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', [], true);
        self::enqueue_script('bootstrap', '/framework-php/frontend/assets/bootstrap/js/bootstrap.bundle.min.js', ['jquery'], true);        
        self::enqueue_script('example', '/framework-php/frontend/assets/js/example.js', ['jquery'], true);
    }

    public static function enqueue_style($handle, $src) {
        self::$styles[$handle] = $src;
    }

    public static function enqueue_script($handle, $src, $deps = [], $in_footer = false) {
        self::$scripts[$handle] = ['src' => $src, 'deps' => $deps, 'in_footer' => $in_footer];
    }

    public static function render_styles() {
        $output = '';
        foreach (self::$styles as $handle => $src) {
            $output .= "<link rel='stylesheet' href='$src' type='text/css' />\n";
        }
        return $output;
    }

    public static function render_scripts() {
        $output = '';
        foreach (self::$scripts as $handle => $script) {
            $deps = implode(' ', $script['deps']);
            $footer = $script['in_footer'] ? '' : 'defer'; // Scripts en footer sin defer
            $output .= "<script src='{$script['src']}' type='text/javascript' $footer></script>\n";
        }
        return $output;
    }
}