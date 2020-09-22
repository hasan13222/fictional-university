<?php 
function universityRegisterSearch(){
	register_rest_route('university/v1', 'search', array(
		'method' => WP_REST_SERVER::READABLE,
		'callback' => 'universitySearchResults'
	));
}
add_action('rest_api_init', 'universityRegisterSearch');

function universitySearchResults($data){
	$mainQuery = new WP_Query(array(
		'post_type' => array('mainQuery', 'page', 'post', 'event', 'program', 'campus', 'professor'),
		's' => sanitize_text_field($data['term'])
	));

	$mainQueryResults = array(
		'generalInfo' => array(),
		'professor' => array(),
		'event' => array(),
		'campus' => array(),
		'program' => arraY()
	);

	while ($mainQuery->have_posts() ) {
		$mainQuery->the_post();
		if (get_post_type() == 'post' OR get_post_type() == 'page') {
			array_push($mainQueryResults['generalInfo'], array(
			'title' => get_the_title(),
			'permalink' => get_the_permalink(),
			'postType' => get_post_type(),
			'authorName' => get_the_author(),
			'excerpt' => (has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content() , 8))
		));
		
		}
		if (get_post_type() == 'program') {
			$relatedCampuses = get_field('related_campus');

			if (condition) {
				# code...
			}
			foreach ($relatedCampuses as $campus) {
				array_push($mainQueryResults['campus'], array(
					'title' => get_the_title($campus),
					'permalink' => get_the_permalink($campus)
				));
			}
			array_push($mainQueryResults['program'], array(
			'title' => get_the_title(),
			'permalink' => get_the_permalink(),
			'id' => get_the_ID(),
			'excerpt' => (has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content() , 8))
		));
		}
		if (get_post_type() == 'campus') {
			array_push($mainQueryResults['campus'], array(
			'title' => get_the_title(),
			'permalink' => get_the_permalink(),
			'excerpt' => (has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content() , 8))
		));
		}
		if (get_post_type() == 'event') {
			$eventDate = new DateTime(get_field('event_date'));
			$description = null;
			if (has_excerpt()) {
		          $description = get_the_excerpt();
		        } else{
		          $description = wp_trim_words(get_the_content() , 8);
		        }
			array_push($mainQueryResults['event'], array(
			'title' => get_the_title(),
			'permalink' => get_the_permalink(),
			'month' => $eventDate->format('M'),
			'day' => $eventDate->format('d'),
			'description' => $description,
		));
		}
		if (get_post_type() == 'professor') {
			array_push($mainQueryResults['professor'], array(
			'title' => get_the_title(),
			'permalink' => get_the_permalink(),
			'image' => get_the_post_thumbnail_url(0, 'lsp')
		));
		}
	}


		if ($mainQueryResults['program']) {

			$programMetaQuery = array('relation' => 'OR');

			foreach ($mainQueryResults['program'] as $item) {
				array_push($programMetaQuery, array(
			 		'key' => 'related_programs',
			 		'compare' => 'LIKE',
			 		'value' => '"'.$item['id'].'"'
			 	));
			}

			 $programRelatedProf = new WP_Query(array(
			 	'post_type' => array('professor','event'),
			 	'meta_query' => $programMetaQuery
			 )) ;

			 while($programRelatedProf->have_posts() ){
			 	$programRelatedProf->the_post();


			 	if (get_post_type() == 'professor') {
			 		array_push($mainQueryResults['professor'], array(
			 		'title' => get_the_title(),
			 		'permalink' => get_the_permalink(),
			 		'image' => get_the_post_thumbnail_url(0, 'lsp')
			 	));
			 	}

			 	if (get_post_type() == 'event') {
			 		$eventDate = new DateTime(get_field('event_date'));
			 		$description = null;
			 		if (has_excerpt()) {
			 	          $description = get_the_excerpt();
			 	        } else{
			 	          $description = wp_trim_words(get_the_content() , 8);
			 	        }
			 		array_push($mainQueryResults['event'], array(
			 		'title' => get_the_title(),
			 		'permalink' => get_the_permalink(),
			 		'month' => $eventDate->format('M'),
			 		'day' => $eventDate->format('d'),
			 		'description' => $description,
			 	));
			 	}
			 }
			 $mainQueryResults['professor'] = array_values(array_unique($mainQueryResults['professor'], SORT_REGULAR));

			 $mainQueryResults['event'] = array_values(array_unique($mainQueryResults['event'], SORT_REGULAR));
		}


	

	return $mainQueryResults;
}