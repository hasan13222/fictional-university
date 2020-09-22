<?php 
get_header(); 
pageBanner(array(
  'title' => 'All events',
  'subtitle' => 'See whtas happening around here'
));
?>



  <div class="container container--narrow page-section">

<?php while (have_posts() ) {
  the_post();
  get_template_part('template-parts/content', 'event');
 } ?>
<?php echo paginate_links(); ?>
<hr class="section-break">
<p>looking for past events? <a href="<?php echo site_url('/past-events'); ?>">check out here</a></p>
  </div>

  
<?php get_footer(); ?>