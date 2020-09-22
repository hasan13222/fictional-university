<?php 

require get_theme_file_path('/inc/like-route.php');
require get_theme_file_path('/inc/search-route.php');

function university_custom_rest(){
	/*
	register_rest_field('post', 'authorName', array(
		'get_callback' => function(){return get_the_author();}
	));*/
	register_rest_field('note', 'userNoteCount', array(
		'get_callback' => function(){return count_user_posts(get_current_user_id(), 'note');}
	));
}
add_action('rest_api_init', 'university_custom_rest');

//registering styles
function onschool_styles(){
	$version = wp_get_theme()->get('Version');
	wp_enqueue_style('main', get_stylesheet_uri(), array(), $version, 'all');
	wp_enqueue_style('roboto', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i', array(), '1.0', 'all');
	wp_enqueue_style('fotnawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.7.0', 'all');

}
add_action('wp_enqueue_scripts', 'onschool_styles');

//registering scripts
function onschool_scripts(){

	wp_enqueue_script('jqry', 'https://code.jquery.com/jquery-3.5.1.min.js', array(), microtime(), true);
	
	wp_enqueue_script('bundle-js', get_template_directory_uri().'/js/scripts-bundled.js', array(), microtime(), true);

	wp_enqueue_script('notes-js', get_template_directory_uri().'/js/MyNotes.js', array(), microtime(), true);

	wp_enqueue_script('search-js', get_template_directory_uri().'/js/search.js', array(), microtime(), true);

	wp_enqueue_script('like-js', get_template_directory_uri().'/js/Like.js', array(), microtime(), true);

	wp_localize_script('search-js', 'universityData', array(
		'root_url' => get_site_url()
	));	
	wp_localize_script('notes-js', 'universityNotesData', array(
		'nonce' => wp_create_nonce('wp_rest')
	));	

}
add_action('wp_enqueue_scripts', 'onschool_scripts');

//add theme support
function onschool_theme_support(){
	register_nav_menu('headerMenuLocation', 'Header Menu Location');
	register_nav_menu('footerMenuOne', 'Footer Menu first');
	register_nav_menu('footerMenuTwo', 'Footer Menu second');
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_image_size('ptr', 100, 150, array('left', 'top'));
	add_image_size('lsp', 200, 50, true);
	add_image_size('pageBanner', 1500, 350, true);
}
add_action('after_setup_theme', 'onschool_theme_support');

function university_adjust_queries($query){
		if (!is_admin() AND is_post_type_archive('event') AND $query->is_main_query() ) {
			$today = date('Ymd');
			$query->set('meta_key', 'event_date');
			$query->set('orderby', 'meta_value_num');
			$query->set('order', 'ASC');
			$query->set('meta_query', array(
                  array(
                      'key' => 'event_date',
                      'compare' => '>=',
                      'value' => $today,
                      'type' => 'numeric'
                  )
              ) );
		}

		if (!is_admin() AND is_post_type_archive('program') AND $query->is_main_query() ) {
			$query->set('posts_per_page', -1);
			$query->set('orderby', 'title');
			$query->set('order', 'ASC');
			
		}
		if (!is_admin() AND is_post_type_archive('campus') AND $query->is_main_query() ) {
			$query->set('posts_per_page', -1);
		}

}
add_action('pre_get_posts', 'university_adjust_queries');

//page banner
function pageBanner($args = NULL){
		if (!$args['title']) {
			$args['title'] = get_the_title();
		}
		if (!$args['subtitle']) {
			$args['subtitle'] = get_field('page_banner_subtitle');
		}
		if (!$args['photo']) {
			if (get_field('page_banner_background') ) {
				$args['photo'] = get_field('page_banner_background')['sizes']['pageBanner'];
			} else{
				$args['photo'] = get_theme_file_uri('/images/ocean.jpg');
			}
		}

	?>
	<div class="page-banner">
	  <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
	  <div class="page-banner__content container container--narrow">
	    <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
	    <div class="page-banner__intro">
	      <p><?php echo $args['subtitle']; ?></p>
	    </div>
	  </div>  
	</div>
	<?php
}

//google api key 

function my_acf_init() {
    acf_update_setting('google_api_key', 'AIzaSyBgZpZau5HdMDUAFkg2GlZF2vf0Env750w');
}
add_action('acf/init', 'my_acf_init');



//redirect subscriber accoutn out of admin and onto homepage

function redirectSubsToFrontend(){
	$ourCurrentUser = wp_get_current_user();
	if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles['0'] == 'subscriber') {
		wp_redirect(site_url('/'));
		exit;
	}
}
add_action('admin_init', 'redirectSubsToFrontend');


function noSubsAdminBar(){
	$ourCurrentUser = wp_get_current_user();
	if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles['0'] == 'subscriber') {
		show_admin_bar(false);
	}
}
add_action('wp_loaded', 'noSubsAdminBar');

//customize login screen

function ourHeaderUrl(){
	return esc_url(site_url('/'));
}
 
add_filter('login_headerurl', 'ourHeaderUrl');


//login page css load

function LoginCss(){
	wp_enqueue_style('main', get_stylesheet_uri(), array(), '1.0', 'all');
	wp_enqueue_style('roboto', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i', array(), '1.0', 'all');
}
add_action('login_enqueue_scripts', 'LoginCss');

//custom login header

function LoginTitle(){
	return get_bloginfo('name');
}
add_filter('login_headertitle', 'LoginTitle');

// Force note posts to be private
function makeNotePrivate($data, $postarr){
	//sanitize resist database data inserting
	if ($data['post_type'] == 'note') {
		//post limit per user
		if (count_user_posts(get_current_user_id(), 'note') > 4 AND !$postarr['ID']) {
			die("you have reached your limit");
		}
		$data['post_content'] = sanitize_textarea_field($data['post_content']);
		$data['post_title'] = sanitize_text_field($data['post_title']);
	}
	if ($data['post_type'] == 'note' AND $data['post_status'] != 'trash'){
		$data['post_status'] = "private";# code...
	}
	return $data;
}
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);