<?php
/**
 * Idempotent seed of placeholder CPT content for the home page.
 * Run via: wp eval-file inc/seed.php
 */
if (!defined('ABSPATH')) exit;

function mc_seed_post($post_type, $slug, $args) {
    $existing = get_posts([
        'post_type'   => $post_type,
        'name'        => $slug,
        'post_status' => 'any',
        'numberposts' => 1,
    ]);
    if ($existing) {
        WP_CLI::log("- exists: $post_type/$slug (id={$existing[0]->ID})");
        return $existing[0]->ID;
    }
    $defaults = [
        'post_type'   => $post_type,
        'post_status' => 'publish',
        'post_name'   => $slug,
    ];
    $id = wp_insert_post(array_merge($defaults, $args));
    WP_CLI::log("+ created: $post_type/$slug (id=$id)");
    return $id;
}

// ---------- Service Categories ----------
$service_cats = [
    [
        'slug'  => 'advisory-leadership',
        'title' => 'Advisory & Project Leadership',
        'list'  => ['Owner representation', 'Project management', 'Constructability reviews', 'Sustainability advisory', 'Accessibility consulting'],
    ],
    [
        'slug'  => 'assessments',
        'title' => 'Property & Portfolio Assessments',
        'list'  => ['Condition assessments', 'Facility assessments', 'Life-safety review', 'MEP review', 'Structural review'],
    ],
    [
        'slug'  => 'design-reviews',
        'title' => 'Design & Technical Reviews',
        'list'  => ['Constructability reviews', 'Code reviews', 'Building enclosure', 'MEP design review', 'Accessibility review'],
    ],
    [
        'slug'  => 'construction-oversight',
        'title' => 'Construction Oversight & QA',
        'list'  => ['Construction management', 'Loan monitoring', 'Building enclosure QA', 'Life-safety QA', 'Structural QA'],
    ],
    [
        'slug'  => 'forensics-repair',
        'title' => 'Forensics, Repair & Performance',
        'list'  => ['Repair & reconstruction', 'Forensic investigations', 'Building enclosure repair', 'Performance testing'],
    ],
];
$order = 1;
foreach ($service_cats as $sc) {
    $items = '';
    foreach ($sc['list'] as $li) {
        $items .= '<li>' . esc_html($li) . '</li>';
    }
    $content = '<ul>' . $items . '</ul>';
    $id = mc_seed_post('mc_service_cat', $sc['slug'], [
        'post_title'   => $sc['title'],
        'post_content' => $content,
        'menu_order'   => $order++,
    ]);
}

// ---------- Perspectives ----------
$perspectives = [
    [
        'slug'    => 'sample-perspective-one',
        'title'   => 'Sample perspective: industry trend overview',
        'excerpt' => 'A short summary of the article goes here. Replace this placeholder text in the Perspectives admin section.',
        'content' => '<p>Full article body placeholder. Edit in the admin under <strong>Perspectives</strong>.</p>',
        'date'    => '-7 days',
    ],
    [
        'slug'    => 'sample-perspective-two',
        'title'   => 'Sample perspective: regulatory change explainer',
        'excerpt' => 'Another short summary placeholder. Replace this text in the Perspectives admin section.',
        'content' => '<p>Full article body placeholder. Edit in the admin under <strong>Perspectives</strong>.</p>',
        'date'    => '-30 days',
    ],
    [
        'slug'    => 'sample-perspective-three',
        'title'   => 'Sample perspective: case study summary',
        'excerpt' => 'A third short summary placeholder. Replace this text in the Perspectives admin section.',
        'content' => '<p>Full article body placeholder. Edit in the admin under <strong>Perspectives</strong>.</p>',
        'date'    => '-90 days',
    ],
];
foreach ($perspectives as $p) {
    mc_seed_post('mc_perspective', $p['slug'], [
        'post_title'   => $p['title'],
        'post_excerpt' => $p['excerpt'],
        'post_content' => $p['content'],
        'post_date'    => date('Y-m-d H:i:s', strtotime($p['date'])),
    ]);
}

// ---------- Projects ----------
$projects = [
    ['slug' => 'sample-project-one',   'title' => 'Sample Project One',   'loc' => 'City, State', 'svc' => 'Owner representation, Project management'],
    ['slug' => 'sample-project-two',   'title' => 'Sample Project Two',   'loc' => 'City, State', 'svc' => 'Constructability reviews, Project management'],
    ['slug' => 'sample-project-three', 'title' => 'Sample Project Three', 'loc' => 'City, State', 'svc' => 'Facility condition assessment'],
    ['slug' => 'sample-project-four',  'title' => 'Sample Project Four',  'loc' => 'City, State', 'svc' => 'MEP, Life-safety, Facility assessment'],
];
foreach ($projects as $p) {
    $id = mc_seed_post('mc_project', $p['slug'], [
        'post_title'   => $p['title'],
        'post_content' => '<p>Project description placeholder. Edit in the admin under <strong>Projects</strong>.</p>',
    ]);
    if ($id) {
        update_post_meta($id, '_mc_location', $p['loc']);
        update_post_meta($id, '_mc_services', $p['svc']);
        update_post_meta($id, '_mc_featured', '1');
    }
}

// flush rewrites so CPT permalinks resolve
flush_rewrite_rules(false);

WP_CLI::success('Seed complete.');
