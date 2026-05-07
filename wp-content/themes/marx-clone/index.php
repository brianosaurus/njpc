<?php
if (!defined('ABSPATH')) exit;
get_header(); ?>

<section class="mc-section">
  <div class="mc-container">
    <?php if (have_posts()): ?>
      <h1 class="mc-h2"><?php echo is_archive() ? get_the_archive_title() : esc_html__('Latest', 'marx-clone'); ?></h1>
      <div class="mc-cards-3">
        <?php while (have_posts()): the_post(); ?>
          <article class="mc-card">
            <a class="mc-card-media" href="<?php the_permalink(); ?>">
              <?php echo mc_thumb_or_placeholder(get_the_ID(), 'large', 220); ?>
            </a>
            <div class="mc-card-body">
              <time class="mc-card-date"><?php echo esc_html(get_the_date()); ?></time>
              <h2 class="mc-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
              <p class="mc-card-excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
            </div>
          </article>
        <?php endwhile; ?>
      </div>
      <?php the_posts_pagination(); ?>
    <?php else: ?>
      <p>Nothing here yet.</p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer();
