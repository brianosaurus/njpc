<?php
/**
 * Build the primary nav menu for NJPC.
 * Run via: wp eval-file inc/seed-menu.php
 */
if (!defined('ABSPATH')) exit;

$menu_name = 'Primary';
$existing = wp_get_nav_menu_object($menu_name);
if ($existing) {
    wp_delete_nav_menu($existing->term_id);
    WP_CLI::log("- removed existing menu: $menu_name");
}

$menu_id = wp_create_nav_menu($menu_name);
WP_CLI::log("+ created menu: $menu_name (id=$menu_id)");

function mc_add_item($menu_id, $title, $url, $parent = 0) {
    return wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title'     => $title,
        'menu-item-url'       => $url,
        'menu-item-status'    => 'publish',
        'menu-item-type'      => 'custom',
        'menu-item-parent-id' => $parent,
    ]);
}

mc_add_item($menu_id, 'About', home_url('/about/'));

$services_id = mc_add_item($menu_id, 'Services', '#');
$services = get_posts(['post_type' => 'mc_service_cat', 'numberposts' => -1, 'orderby' => 'menu_order', 'order' => 'ASC']);
foreach ($services as $svc) {
    wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title'     => $svc->post_title,
        'menu-item-object'    => 'mc_service_cat',
        'menu-item-object-id' => $svc->ID,
        'menu-item-type'      => 'post_type',
        'menu-item-status'    => 'publish',
        'menu-item-parent-id' => $services_id,
    ]);
}

mc_add_item($menu_id, 'Projects', get_post_type_archive_link('mc_project') ?: home_url('/project/'));
mc_add_item($menu_id, 'Contact', home_url('/contact/'));

$locations = get_theme_mod('nav_menu_locations', []);
$locations['primary'] = $menu_id;
set_theme_mod('nav_menu_locations', $locations);
WP_CLI::log("+ assigned menu to 'primary' location");

WP_CLI::success('Primary menu built.');
