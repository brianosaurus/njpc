<?php
if (!defined('ABSPATH')) exit;

define('MC_THEME_VERSION', '1.0.0');
define('MC_THEME_DIR', get_template_directory());
define('MC_THEME_URI', get_template_directory_uri());

require_once MC_THEME_DIR . '/inc/cpt.php';
require_once MC_THEME_DIR . '/inc/customizer.php';

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('custom-logo', [
        'height'      => 80,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    register_nav_menus([
        'primary'   => __('Primary Navigation', 'marx-clone'),
        'secondary' => __('Secondary Navigation', 'marx-clone'),
        'footer_property_lifecycle' => __('Footer: Property Lifecycle', 'marx-clone'),
        'footer_services'           => __('Footer: Services', 'marx-clone'),
        'footer_asset_types'        => __('Footer: Asset Types', 'marx-clone'),
        'footer_projects'           => __('Footer: Projects', 'marx-clone'),
        'footer_about'              => __('Footer: About', 'marx-clone'),
    ]);
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'marx-clone-main',
        MC_THEME_URI . '/assets/css/main.css',
        [],
        MC_THEME_VERSION
    );
    wp_enqueue_script(
        'marx-clone-main',
        MC_THEME_URI . '/assets/js/main.js',
        [],
        MC_THEME_VERSION,
        true
    );
});

/**
 * Helper: get a Customizer string with default fallback.
 */
function mc_text($key, $default = '') {
    $val = get_theme_mod($key, $default);
    return is_string($val) ? $val : $default;
}

/**
 * Helper: get a Customizer rich-text (allows basic HTML).
 */
function mc_html($key, $default = '') {
    $val = get_theme_mod($key, $default);
    return wp_kses_post(is_string($val) ? $val : $default);
}

/**
 * Inline SVG placeholder for image cards (avoids external requests).
 */
function mc_placeholder_svg($label = '', $w = 800, $h = 600, $hue = 210) {
    $label = esc_html($label);
    $bg1 = "hsl({$hue}, 30%, 75%)";
    $bg2 = "hsl({$hue}, 35%, 50%)";
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.$w.' '.$h.'" preserveAspectRatio="xMidYMid slice" class="mc-ph">'
         . '<defs><linearGradient id="g'.$hue.'" x1="0" y1="0" x2="1" y2="1">'
         . '<stop offset="0%" stop-color="'.$bg1.'"/><stop offset="100%" stop-color="'.$bg2.'"/>'
         . '</linearGradient></defs>'
         . '<rect width="'.$w.'" height="'.$h.'" fill="url(#g'.$hue.')"/>'
         . '<text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" '
         . 'font-family="system-ui, sans-serif" font-size="28" fill="rgba(255,255,255,0.85)">'.$label.'</text>'
         . '</svg>';
    return $svg;
}

/**
 * Featured image markup or placeholder.
 */
function mc_thumb_or_placeholder($post_id, $size = 'large', $hue = 210, $label_fallback = 'Image') {
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail($post_id, $size, ['class' => 'mc-card-img']);
    }
    $label = get_the_title($post_id) ?: $label_fallback;
    return mc_placeholder_svg($label, 800, 600, $hue);
}
