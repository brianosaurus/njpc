<?php
if (!defined('ABSPATH')) exit;
get_header(); ?>

<section class="mc-section">
  <div class="mc-container mc-prose">
    <?php while (have_posts()): the_post(); ?>
      <h1 class="mc-h1"><?php the_title(); ?></h1>
      <div><?php the_content(); ?></div>
    <?php endwhile; ?>
  </div>
</section>

<?php get_footer();
