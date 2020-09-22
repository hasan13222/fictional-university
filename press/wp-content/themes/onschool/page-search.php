<?php get_header(); ?>
<?php while (have_posts() ) {
  the_post();
  pageBanner(array(
    'title' => 'hello',
    'subtitle' => 'sub',
    'photo' => get_the_post_thumbnail_url()
  ));
?>


  <div class="container container--narrow page-section">

    <?php
      $has_parent = wp_get_post_parent_id(get_the_ID());
     if ($has_parent) { ?>
      <div class="metabox metabox--position-up metabox--with-home-link">
          <p><a class="metabox__blog-home-link" href="<?php echo get_permalink($has_parent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($has_parent); ?></a> <span class="metabox__main"><?php the_title(); ?></span></p>
      </div>
      <?php
    } ?>

    <?php 
      $the_parent = get_pages(array(
        'child_of' => get_the_ID()
      ));
     ?>
    
    <?php if ($has_parent or $the_parent) { ?>
      <div class="page-links">
        <h2 class="page-links__title"><a href="<?php echo get_permalink($has_parent) ?>"><?php echo get_the_title($has_parent) ?></a></h2>
        <ul class="min-list">
          <?php
            if ($has_parent) {
               $findChildOf = $has_parent;
             } else {
                $findChildOf = get_the_ID();
             }
            wp_list_pages(array(
              'title_li' => Null,
              'child_of' => $findChildOf,
            ));
           ?>
        </ul>
      </div>
    <?php
    } ?>
    

    <div class="generic-content">
      <?php get_search_form(); ?>
    </div>

  </div>
<?php } ?>
  
<?php get_footer(); ?>