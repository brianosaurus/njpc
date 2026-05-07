<?php
if (!defined('ABSPATH')) exit;

add_action('customize_register', function (WP_Customize_Manager $wp_customize) {

    $panel = 'mc_homepage';
    $wp_customize->add_panel($panel, [
        'title'    => __('Home Page Content', 'marx-clone'),
        'priority' => 30,
        'description' => 'All copy on the home page. Lists (services, projects, perspectives) live in their own admin sections.',
    ]);

    $sections = [
        'hero'         => 'Hero',
        'lifecycle'    => 'Lifecycle Scenario Picker',
        'services'     => 'Services Section',
        'video'        => 'Video Band',
        'perspectives' => 'Perspectives Section',
        'projects'     => 'Featured Projects Section',
        'footer'       => 'Footer',
    ];
    foreach ($sections as $slug => $title) {
        $wp_customize->add_section("mc_$slug", [
            'title' => $title,
            'panel' => $panel,
        ]);
    }

    $fields = [
        // Hero
        ['hero_eyebrow',    'mc_hero', 'text',     'Hero eyebrow',         'Eyebrow text'],
        ['hero_headline',   'mc_hero', 'textarea', 'Hero headline',        'Hero headline placeholder.'],
        ['hero_subheading', 'mc_hero', 'text',     'Hero subheading',      'Supporting subheading placeholder.'],
        ['hero_body',       'mc_hero', 'textarea', 'Hero body copy',       'Replace this paragraph with a description of your firm in Appearance → Customize → Home Page Content → Hero.'],
        ['hero_cta_label',  'mc_hero', 'text',     'Hero CTA label',       'Primary call to action'],
        ['hero_cta_url',    'mc_hero', 'url',      'Hero CTA URL',         '#lifecycle'],

        // Lifecycle picker
        ['lc_heading', 'mc_lifecycle', 'textarea', 'Section heading',  'Scenario picker section heading.'],
        ['lc_prompt',  'mc_lifecycle', 'text',     'Prompt label',     'Choose the option that fits you.'],
        ['lc_opt1_label', 'mc_lifecycle', 'text', 'Option 1 label', 'Scenario one'],
        ['lc_opt1_url',   'mc_lifecycle', 'url',  'Option 1 URL',   '#'],
        ['lc_opt2_label', 'mc_lifecycle', 'text', 'Option 2 label', 'Scenario two'],
        ['lc_opt2_url',   'mc_lifecycle', 'url',  'Option 2 URL',   '#'],
        ['lc_opt3_label', 'mc_lifecycle', 'text', 'Option 3 label', 'Scenario three'],
        ['lc_opt3_url',   'mc_lifecycle', 'url',  'Option 3 URL',   '#'],
        ['lc_opt4_label', 'mc_lifecycle', 'text', 'Option 4 label', 'Scenario four'],
        ['lc_opt4_url',   'mc_lifecycle', 'url',  'Option 4 URL',   '#'],
        ['lc_opt5_label', 'mc_lifecycle', 'text', 'Option 5 label', 'Scenario five'],
        ['lc_opt5_url',   'mc_lifecycle', 'url',  'Option 5 URL',   '#'],

        // Services
        ['svc_eyebrow', 'mc_services', 'text',     'Eyebrow',        'Eyebrow text'],
        ['svc_heading', 'mc_services', 'textarea', 'Section heading','Services section heading placeholder.'],
        ['svc_blurb',   'mc_services', 'textarea', 'Section blurb',  'A short paragraph describing how the categories below work together. Edit this in Appearance → Customize.'],

        // Video band
        ['vid_image_url',  'mc_video', 'url',      'Background image URL (optional)', ''],
        ['vid_heading',    'mc_video', 'text',     'Heading',        'Video band heading.'],
        ['vid_subheading', 'mc_video', 'textarea', 'Subheading',     'Short pitch for the linked video. Tell visitors what they will learn in under 30 seconds.'],
        ['vid_url',        'mc_video', 'url',      'Video URL',      'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
        ['vid_button',     'mc_video', 'text',     'Button label',   'Watch the video'],

        // Perspectives
        ['per_heading',  'mc_perspectives', 'text', 'Section heading', 'Latest perspectives.'],
        ['per_cta_label','mc_perspectives', 'text', 'CTA label',       'See all perspectives'],
        ['per_cta_url',  'mc_perspectives', 'url',  'CTA URL',         '/perspective/'],

        // Projects
        ['prj_heading',   'mc_projects', 'text', 'Section heading', 'Featured projects'],
        ['prj_cta_label', 'mc_projects', 'text', 'CTA label',       'View all projects'],
        ['prj_cta_url',   'mc_projects', 'url',  'CTA URL',         '/project/'],

        // Footer
        ['ft_tagline',     'mc_footer', 'text',     'Footer tagline',     'Your footer tagline goes here.'],
        ['ft_copyright',   'mc_footer', 'text',     'Copyright line',     '© ' . date('Y') . ' Your Company. All rights reserved.'],
        ['ft_newsletter_heading', 'mc_footer', 'text', 'Newsletter heading', 'Subscribe to our newsletter.'],
        ['ft_newsletter_button',  'mc_footer', 'text', 'Newsletter button label', 'Subscribe'],
        ['ft_contact_label', 'mc_footer', 'text', 'Contact CTA label', 'Get in touch'],
        ['ft_contact_url',   'mc_footer', 'url',  'Contact CTA URL',   '/contact/'],
        ['ft_social_linkedin','mc_footer','url',  'LinkedIn URL', ''],
        ['ft_social_instagram','mc_footer','url', 'Instagram URL',''],
        ['ft_social_youtube', 'mc_footer','url',  'YouTube URL',  ''],
        ['ft_social_facebook','mc_footer','url',  'Facebook URL', ''],
    ];

    foreach ($fields as $f) {
        [$id, $section, $type, $label, $default] = $f;
        $sanitize = 'sanitize_text_field';
        if ($type === 'url')      $sanitize = 'esc_url_raw';
        if ($type === 'textarea') $sanitize = 'sanitize_textarea_field';

        $wp_customize->add_setting($id, [
            'default'           => $default,
            'sanitize_callback' => $sanitize,
            'transport'         => 'refresh',
        ]);
        $args = [
            'label'   => $label,
            'section' => $section,
            'type'    => $type === 'textarea' ? 'textarea' : ($type === 'url' ? 'url' : 'text'),
        ];
        $wp_customize->add_control($id, $args);
    }
});
