<?php if (!defined('ABSPATH')) exit; ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="mc-skip" href="#mc-main">Skip to content</a>

<header class="mc-header">
  <div class="mc-header-inner mc-container">
    <div class="mc-logo">
      <?php
      if (has_custom_logo()) {
          the_custom_logo();
      } else {
          echo '<a class="mc-logo-link" href="' . esc_url(home_url('/')) . '">'
             . '<span class="mc-logo-text">' . esc_html(get_bloginfo('name')) . '</span>'
             . '</a>';
      }
      ?>
    </div>

    <nav class="mc-nav-primary" aria-label="Primary">
      <?php
      if (has_nav_menu('primary')) {
          wp_nav_menu([
              'theme_location' => 'primary',
              'container'      => false,
              'menu_class'     => 'mc-menu',
              'depth'          => 2,
              'fallback_cb'    => false,
          ]);
      } else {
          // Fallback nav reflecting NJPC's actual offering.
          $services = get_posts(['post_type' => 'mc_service_cat', 'numberposts' => -1, 'orderby' => 'menu_order', 'order' => 'ASC']);
          echo '<ul class="mc-menu">';
          echo '<li><a href="' . esc_url(home_url('/about/')) . '">About</a></li>';
          echo '<li class="menu-item-has-children"><a href="#">Services</a>';
          if ($services) {
              echo '<ul class="sub-menu">';
              foreach ($services as $svc) {
                  echo '<li><a href="' . esc_url(get_permalink($svc)) . '">' . esc_html($svc->post_title) . '</a></li>';
              }
              echo '</ul>';
          }
          echo '</li>';
          echo '<li><a href="' . esc_url(get_post_type_archive_link('mc_project') ?: '#') . '">Projects</a></li>';
          echo '<li><a href="' . esc_url(mc_text('ft_contact_url', '/contact/')) . '">Contact</a></li>';
          echo '</ul>';
      }
      ?>
    </nav>

    <button class="mc-burger" aria-label="Toggle menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>

  <?php if (has_nav_menu('secondary')): ?>
  <div class="mc-subnav">
    <div class="mc-container">
      <?php
      wp_nav_menu([
          'theme_location' => 'secondary',
          'container'      => false,
          'menu_class'     => 'mc-submenu',
          'depth'          => 1,
          'fallback_cb'    => false,
      ]);
      ?>
    </div>
  </div>
  <?php endif; ?>
</header>

<main id="mc-main" class="mc-main">
