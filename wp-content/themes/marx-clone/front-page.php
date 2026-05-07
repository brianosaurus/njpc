<?php
if (!defined('ABSPATH')) exit;
get_header();
?>

<!-- HERO -->
<section class="mc-hero">
  <div class="mc-container mc-hero-grid">
    <div class="mc-hero-text">
      <span class="mc-eyebrow"><?php echo esc_html(mc_text('hero_eyebrow', 'Eyebrow text')); ?></span>
      <h1 class="mc-h1"><?php echo esc_html(mc_text('hero_headline', 'Hero headline placeholder.')); ?></h1>
      <p class="mc-lede"><?php echo esc_html(mc_text('hero_subheading', 'Supporting subheading placeholder.')); ?></p>
      <p class="mc-body"><?php echo esc_html(mc_text('hero_body', '')); ?></p>
      <a class="mc-btn mc-btn-primary" href="<?php echo esc_url(mc_text('hero_cta_url', '#lifecycle')); ?>">
        <?php echo esc_html(mc_text('hero_cta_label', 'Primary call to action')); ?>
      </a>
    </div>
    <div class="mc-hero-visual" aria-hidden="true">
      <div class="mc-gear">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
          <g fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="100" cy="100" r="80" />
            <circle cx="100" cy="100" r="55" />
            <circle cx="100" cy="100" r="30" />
            <?php for ($i = 0; $i < 12; $i++) {
              $a = $i * 30;
              echo '<line x1="100" y1="20" x2="100" y2="35" transform="rotate(' . $a . ' 100 100)" />';
            } ?>
          </g>
        </svg>
      </div>
    </div>
  </div>
</section>

<!-- LIFECYCLE PICKER -->
<section id="lifecycle" class="mc-section mc-lifecycle">
  <div class="mc-container">
    <div class="mc-lifecycle-grid">
      <div class="mc-lifecycle-images">
        <div class="mc-lc-img mc-lc-img-1"><?php echo mc_placeholder_svg('Asset image 1', 600, 700, 200); ?></div>
        <div class="mc-lc-img mc-lc-img-2"><?php echo mc_placeholder_svg('Asset image 2', 600, 700, 30); ?></div>
      </div>
      <div class="mc-lifecycle-text">
        <h2 class="mc-h2"><?php echo esc_html(mc_text('lc_heading', 'Scenario picker section heading.')); ?></h2>
        <label class="mc-lc-prompt" for="mc-lc-select"><?php echo esc_html(mc_text('lc_prompt', 'Choose the option that fits you.')); ?></label>
        <div class="mc-lc-picker">
          <select id="mc-lc-select" class="mc-select">
            <option value="">Choose a scenario…</option>
            <?php for ($i = 1; $i <= 5; $i++):
              $label = mc_text("lc_opt{$i}_label", '');
              $url   = mc_text("lc_opt{$i}_url", '#');
              if (!$label) continue; ?>
              <option value="<?php echo esc_attr($url); ?>"><?php echo esc_html($label); ?></option>
            <?php endfor; ?>
          </select>
          <button type="button" class="mc-btn mc-btn-primary" id="mc-lc-go">Go</button>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SERVICES -->
<section class="mc-section mc-services">
  <div class="mc-container">
    <div class="mc-services-intro">
      <span class="mc-eyebrow"><?php echo esc_html(mc_text('svc_eyebrow', 'Eyebrow text')); ?></span>
      <h2 class="mc-h2"><?php echo esc_html(mc_text('svc_heading', 'Services section heading placeholder.')); ?></h2>
      <p class="mc-body"><?php echo esc_html(mc_text('svc_blurb', '')); ?></p>
    </div>
    <div class="mc-services-grid">
      <?php
      $cats = new WP_Query([
          'post_type'      => 'mc_service_cat',
          'posts_per_page' => 5,
          'orderby'        => 'menu_order title',
          'order'          => 'ASC',
      ]);
      $hue = 200;
      while ($cats->have_posts()) : $cats->the_post(); ?>
        <article class="mc-service-card">
          <h3><?php the_title(); ?></h3>
          <div class="mc-service-body"><?php the_content(); ?></div>
        </article>
      <?php $hue += 30; endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>

<!-- VIDEO BAND -->
<section class="mc-section mc-video">
  <div class="mc-container mc-video-grid">
    <div class="mc-video-visual">
      <?php
      $vid_image = mc_text('vid_image_url', '');
      if ($vid_image) {
          echo '<img src="' . esc_url($vid_image) . '" alt="">';
      } else {
          echo mc_placeholder_svg('Video band visual', 1000, 700, 250);
      }
      ?>
    </div>
    <div class="mc-video-text">
      <h2 class="mc-h2"><?php echo esc_html(mc_text('vid_heading', 'Video band heading.')); ?></h2>
      <p class="mc-body"><?php echo esc_html(mc_text('vid_subheading', '')); ?></p>
      <a class="mc-btn mc-btn-primary" href="<?php echo esc_url(mc_text('vid_url', '#')); ?>" rel="noopener" target="_blank">
        <?php echo esc_html(mc_text('vid_button', 'Watch the video')); ?>
      </a>
    </div>
  </div>
</section>

<!-- PERSPECTIVES -->
<section class="mc-section mc-perspectives">
  <div class="mc-container">
    <div class="mc-section-head">
      <h2 class="mc-h2"><?php echo esc_html(mc_text('per_heading', 'Latest perspectives.')); ?></h2>
      <a class="mc-link" href="<?php echo esc_url(mc_text('per_cta_url', '/perspective/')); ?>">
        <?php echo esc_html(mc_text('per_cta_label', 'See all perspectives')); ?> →
      </a>
    </div>
    <div class="mc-cards-3">
      <?php
      $persp = new WP_Query([
          'post_type'      => 'mc_perspective',
          'posts_per_page' => 3,
      ]);
      $hue = 220;
      while ($persp->have_posts()) : $persp->the_post();
        $external = get_post_meta(get_the_ID(), '_mc_external_url', true);
        $url = $external ?: get_permalink();
      ?>
        <article class="mc-card">
          <a class="mc-card-media" href="<?php echo esc_url($url); ?>">
            <?php echo mc_thumb_or_placeholder(get_the_ID(), 'large', $hue, 'Perspective'); ?>
          </a>
          <div class="mc-card-body">
            <time class="mc-card-date"><?php echo esc_html(get_the_date()); ?></time>
            <h3 class="mc-card-title"><a href="<?php echo esc_url($url); ?>"><?php the_title(); ?></a></h3>
            <p class="mc-card-excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
          </div>
        </article>
      <?php $hue += 40; endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>

<!-- FEATURED PROJECTS -->
<section class="mc-section mc-projects">
  <div class="mc-container">
    <div class="mc-section-head">
      <h2 class="mc-h2"><?php echo esc_html(mc_text('prj_heading', 'Featured projects')); ?></h2>
      <a class="mc-link" href="<?php echo esc_url(mc_text('prj_cta_url', '/project/')); ?>">
        <?php echo esc_html(mc_text('prj_cta_label', 'View all projects')); ?> →
      </a>
    </div>
    <div class="mc-cards-4">
      <?php
      $projects = new WP_Query([
          'post_type'      => 'mc_project',
          'posts_per_page' => 4,
          'meta_query'     => [
              ['key' => '_mc_featured', 'value' => '1'],
          ],
      ]);
      if (!$projects->have_posts()) {
          // fallback: show most-recent if none flagged
          $projects = new WP_Query([
              'post_type'      => 'mc_project',
              'posts_per_page' => 4,
          ]);
      }
      $hue = 180;
      while ($projects->have_posts()) : $projects->the_post();
        $loc = get_post_meta(get_the_ID(), '_mc_location', true);
        $svcs = get_post_meta(get_the_ID(), '_mc_services', true);
      ?>
        <article class="mc-card mc-card-project">
          <a class="mc-card-media" href="<?php the_permalink(); ?>">
            <?php echo mc_thumb_or_placeholder(get_the_ID(), 'large', $hue, 'Project'); ?>
          </a>
          <div class="mc-card-body">
            <h3 class="mc-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <?php if ($loc): ?><p class="mc-card-meta"><?php echo esc_html($loc); ?></p><?php endif; ?>
            <?php if ($svcs): ?>
              <p class="mc-card-services">
                <span>Services:</span>
                <?php echo esc_html($svcs); ?>
              </p>
            <?php endif; ?>
          </div>
        </article>
      <?php $hue += 25; endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>

<?php get_footer();
