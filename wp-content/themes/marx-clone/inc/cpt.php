<?php
if (!defined('ABSPATH')) exit;

add_action('init', function () {
    register_post_type('mc_project', [
        'label'        => 'Projects',
        'labels'       => [
            'name'          => 'Projects',
            'singular_name' => 'Project',
            'add_new_item'  => 'Add New Project',
            'edit_item'     => 'Edit Project',
        ],
        'public'       => true,
        'show_in_rest' => true,
        'has_archive'  => true,
        'menu_icon'    => 'dashicons-building',
        'menu_position'=> 20,
        'rewrite'      => ['slug' => 'project'],
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'],
    ]);

    register_post_type('mc_perspective', [
        'label'        => 'Perspectives',
        'labels'       => [
            'name'          => 'Perspectives',
            'singular_name' => 'Perspective',
            'add_new_item'  => 'Add New Perspective',
            'edit_item'     => 'Edit Perspective',
        ],
        'public'       => true,
        'show_in_rest' => true,
        'has_archive'  => true,
        'menu_icon'    => 'dashicons-format-aside',
        'menu_position'=> 21,
        'rewrite'      => ['slug' => 'perspective'],
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
    ]);

    register_post_type('mc_photo', [
        'label'        => 'Photo Album',
        'labels'       => [
            'name'          => 'Photo Album',
            'singular_name' => 'Photo',
            'add_new'       => 'Add Photo',
            'add_new_item'  => 'Add New Photo',
            'edit_item'     => 'Edit Photo',
            'menu_name'     => 'Photo Album',
            'all_items'     => 'All Photos',
        ],
        'public'       => true,
        'show_in_rest' => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-camera',
        'menu_position'=> 23,
        'rewrite'      => ['slug' => 'photo'],
        'supports'     => ['title', 'thumbnail', 'page-attributes'],
    ]);

    register_post_type('mc_service_cat', [
        'label'        => 'Service Categories',
        'labels'       => [
            'name'          => 'Service Categories',
            'singular_name' => 'Service Category',
            'add_new_item'  => 'Add Service Category',
            'edit_item'     => 'Edit Service Category',
        ],
        'public'       => true,
        'show_in_rest' => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-screenoptions',
        'menu_position'=> 22,
        'rewrite'      => ['slug' => 'service-category'],
        'supports'     => ['title', 'editor', 'page-attributes'],
    ]);
});

/**
 * Register meta boxes for project location + featured flag.
 */
add_action('add_meta_boxes', function () {
    add_meta_box('mc_project_meta', 'Project Details', 'mc_render_project_meta', 'mc_project', 'side');
    add_meta_box('mc_perspective_meta', 'Perspective Details', 'mc_render_perspective_meta', 'mc_perspective', 'side');
    add_meta_box('mc_photo_meta', 'Photo Settings', 'mc_render_photo_meta', 'mc_photo', 'side', 'high');
});

/**
 * Theme support for featured images is registered in functions.php; this just
 * ensures the Featured Image meta-box is prominent on photo edit screens.
 */
function mc_render_photo_meta($post) {
    wp_nonce_field('mc_photo_meta', 'mc_photo_meta_nonce');
    $masthead = get_post_meta($post->ID, '_mc_photo_masthead', true);
    $gallery  = get_post_meta($post->ID, '_mc_photo_gallery', true);
    echo '<p><strong>Use this photo in:</strong></p>';
    echo '<p><label><input type="checkbox" name="mc_photo_masthead" value="1" '.checked($masthead, '1', false).'> Masthead</label></p>';
    echo '<p><label><input type="checkbox" name="mc_photo_gallery" value="1" '.checked($gallery, '1', false).'> Gallery</label></p>';
    echo '<p style="color:#666;font-size:12px;margin-top:12px">Set the actual image with <strong>Featured Image</strong> in the sidebar.</p>';
}

add_action('save_post_mc_photo', function ($post_id) {
    if (!isset($_POST['mc_photo_meta_nonce']) || !wp_verify_nonce($_POST['mc_photo_meta_nonce'], 'mc_photo_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    update_post_meta($post_id, '_mc_photo_masthead', !empty($_POST['mc_photo_masthead']) ? '1' : '');
    update_post_meta($post_id, '_mc_photo_gallery',  !empty($_POST['mc_photo_gallery'])  ? '1' : '');
});

/* ---------- Admin list columns for Photo Album ---------- */
add_filter('manage_mc_photo_posts_columns', function ($cols) {
    $new = [];
    foreach ($cols as $k => $v) {
        if ($k === 'title') {
            $new['mc_thumb'] = 'Image';
        }
        $new[$k] = $v;
        if ($k === 'title') {
            $new['mc_masthead'] = 'Masthead';
            $new['mc_gallery']  = 'Gallery';
        }
    }
    return $new;
});

add_action('manage_mc_photo_posts_custom_column', function ($col, $post_id) {
    if ($col === 'mc_thumb') {
        if (has_post_thumbnail($post_id)) {
            echo get_the_post_thumbnail($post_id, [60, 60], ['style' => 'border-radius:4px']);
        } else {
            echo '<span style="color:#999">— no image —</span>';
        }
    } elseif ($col === 'mc_masthead') {
        echo get_post_meta($post_id, '_mc_photo_masthead', true) === '1' ? '<span style="color:#0a7;font-weight:700">✓</span>' : '<span style="color:#ccc">—</span>';
    } elseif ($col === 'mc_gallery') {
        echo get_post_meta($post_id, '_mc_photo_gallery', true) === '1' ? '<span style="color:#0a7;font-weight:700">✓</span>' : '<span style="color:#ccc">—</span>';
    }
}, 10, 2);

/* ---------- Helpers for templates ---------- */
function mc_get_photos($flag = null, $limit = -1) {
    $args = [
        'post_type'      => 'mc_photo',
        'posts_per_page' => $limit,
        'orderby'        => 'menu_order date',
        'order'          => 'ASC',
    ];
    if (in_array($flag, ['masthead', 'gallery'], true)) {
        $args['meta_query'] = [
            ['key' => '_mc_photo_' . $flag, 'value' => '1'],
        ];
    }
    return get_posts($args);
}

function mc_render_project_meta($post) {
    wp_nonce_field('mc_project_meta', 'mc_project_meta_nonce');
    $location = get_post_meta($post->ID, '_mc_location', true);
    $services = get_post_meta($post->ID, '_mc_services', true);
    $featured = get_post_meta($post->ID, '_mc_featured', true);
    echo '<p><label>Location<br><input type="text" name="mc_location" value="'.esc_attr($location).'" style="width:100%"></label></p>';
    echo '<p><label>Services Used (comma-separated)<br><textarea name="mc_services" style="width:100%" rows="3">'.esc_textarea($services).'</textarea></label></p>';
    echo '<p><label><input type="checkbox" name="mc_featured" value="1" '.checked($featured, '1', false).'> Featured on home page</label></p>';
}

function mc_render_perspective_meta($post) {
    wp_nonce_field('mc_perspective_meta', 'mc_perspective_meta_nonce');
    $external = get_post_meta($post->ID, '_mc_external_url', true);
    echo '<p><label>External link (optional)<br><input type="url" name="mc_external_url" value="'.esc_attr($external).'" style="width:100%"></label></p>';
}

add_action('save_post_mc_project', function ($post_id) {
    if (!isset($_POST['mc_project_meta_nonce']) || !wp_verify_nonce($_POST['mc_project_meta_nonce'], 'mc_project_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    update_post_meta($post_id, '_mc_location', sanitize_text_field($_POST['mc_location'] ?? ''));
    update_post_meta($post_id, '_mc_services', sanitize_textarea_field($_POST['mc_services'] ?? ''));
    update_post_meta($post_id, '_mc_featured', !empty($_POST['mc_featured']) ? '1' : '');
});

add_action('save_post_mc_perspective', function ($post_id) {
    if (!isset($_POST['mc_perspective_meta_nonce']) || !wp_verify_nonce($_POST['mc_perspective_meta_nonce'], 'mc_perspective_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    update_post_meta($post_id, '_mc_external_url', esc_url_raw($_POST['mc_external_url'] ?? ''));
});
