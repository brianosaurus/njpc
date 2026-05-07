<?php if (!defined('ABSPATH')) exit; ?>
</main>

<footer class="mc-footer">
  <div class="mc-container">

    <div class="mc-footer-top">
      <div class="mc-footer-tagline">
        <span class="mc-logo-text mc-logo-text-light"><?php echo esc_html(get_bloginfo('name')); ?></span>
        <p><?php echo esc_html(mc_text('ft_tagline', 'Your footer tagline goes here.')); ?></p>
        <a class="mc-btn mc-btn-outline" href="<?php echo esc_url(mc_text('ft_contact_url', '/contact/')); ?>">
          <?php echo esc_html(mc_text('ft_contact_label', 'Get in touch')); ?>
        </a>
      </div>

      <div class="mc-newsletter">
        <h3><?php echo esc_html(mc_text('ft_newsletter_heading', 'Subscribe to our newsletter.')); ?></h3>
        <form class="mc-newsletter-form" onsubmit="event.preventDefault(); alert('Wire this up to your email service of choice.');">
          <label class="screen-reader-text" for="mc-nl-email">Email address</label>
          <input id="mc-nl-email" type="email" required placeholder="you@example.com">
          <button type="submit" class="mc-btn"><?php echo esc_html(mc_text('ft_newsletter_button', 'Subscribe')); ?></button>
        </form>
      </div>
    </div>

    <div class="mc-footer-cols">
      <?php
      $cols = [
          'footer_property_lifecycle' => 'Property Lifecycle',
          'footer_services'           => 'Services',
          'footer_asset_types'        => 'Asset Types',
          'footer_projects'           => 'Projects',
          'footer_about'              => 'About',
      ];
      foreach ($cols as $loc => $title) {
          echo '<div class="mc-footer-col">';
          echo '<h4>' . esc_html($title) . '</h4>';
          if (has_nav_menu($loc)) {
              wp_nav_menu([
                  'theme_location' => $loc,
                  'container'      => false,
                  'menu_class'     => 'mc-footer-menu',
                  'fallback_cb'    => false,
                  'depth'          => 1,
              ]);
          } else {
              echo '<ul class="mc-footer-menu mc-footer-menu-empty"><li>Add menu items in Appearance → Menus</li></ul>';
          }
          echo '</div>';
      }
      ?>
    </div>

    <div class="mc-footer-bottom">
      <ul class="mc-social">
        <?php
        $socials = [
            'ft_social_linkedin'  => ['LinkedIn',  'in'],
            'ft_social_instagram' => ['Instagram', 'ig'],
            'ft_social_youtube'   => ['YouTube',   'yt'],
            'ft_social_facebook'  => ['Facebook',  'fb'],
        ];
        foreach ($socials as $key => [$label, $abbr]) {
            $url = mc_text($key, '');
            if ($url) {
                echo '<li><a href="' . esc_url($url) . '" aria-label="' . esc_attr($label) . '" rel="noopener">' . esc_html($abbr) . '</a></li>';
            }
        }
        ?>
      </ul>
      <p class="mc-copy"><?php echo esc_html(mc_text('ft_copyright', '© ' . date('Y') . ' Your Company.')); ?></p>
    </div>

  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
