<?php
if (!defined('ABSPATH')) exit;
get_header(); ?>

<section class="mc-section">
  <div class="mc-container mc-prose">
    <?php while (have_posts()): the_post(); ?>
      <article>
        <h1 class="mc-h1"><?php the_title(); ?></h1>
        <p class="mc-card-date"><?php echo esc_html(get_the_date()); ?></p>
        <?php if (has_post_thumbnail()) the_post_thumbnail('large'); ?>
        <div><?php the_content(); ?></div>
      </article>
    <?php endwhile; ?>
  </div>
</section>

<?php get_footer();
