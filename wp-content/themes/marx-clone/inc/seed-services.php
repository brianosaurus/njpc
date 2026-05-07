<?php
/**
 * Replace placeholder service categories with NJPC's actual services.
 * Run via: wp eval-file inc/seed-services.php
 */
if (!defined('ABSPATH')) exit;

// Remove any existing service categories so this runs cleanly.
$existing = get_posts([
    'post_type'   => 'mc_service_cat',
    'numberposts' => -1,
    'post_status' => 'any',
]);
foreach ($existing as $p) {
    wp_delete_post($p->ID, true);
    WP_CLI::log("- removed: {$p->post_name} (id={$p->ID})");
}

$services = [
    [
        'slug'    => 'construction-management',
        'title'   => 'Construction Management',
        'content' => '<p>Description placeholder for Construction Management. Edit in <strong>Service Categories</strong> in the admin.</p>',
    ],
    [
        'slug'    => 'expert-witness',
        'title'   => 'Expert Witness',
        'content' => '<p>Description placeholder for Expert Witness services. Edit in <strong>Service Categories</strong> in the admin.</p>',
    ],
    [
        'slug'    => 'design-build',
        'title'   => 'Design Build',
        'content' => '<p>Description placeholder for Design Build delivery. Edit in <strong>Service Categories</strong> in the admin.</p>',
    ],
    [
        'slug'    => 'general-contractor',
        'title'   => 'General Contractor',
        'content' => '<p>Description placeholder for General Contractor services. Edit in <strong>Service Categories</strong> in the admin.</p>',
    ],
];

$order = 1;
foreach ($services as $s) {
    $id = wp_insert_post([
        'post_type'    => 'mc_service_cat',
        'post_status'  => 'publish',
        'post_name'    => $s['slug'],
        'post_title'   => $s['title'],
        'post_content' => $s['content'],
        'menu_order'   => $order++,
    ]);
    WP_CLI::log("+ created: {$s['slug']} (id=$id)");
}

WP_CLI::success('NJPC services seeded.');
