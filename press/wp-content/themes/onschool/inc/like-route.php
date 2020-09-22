<?php

function universityLikeRoutes(){

	register_rest_route('university/v1', 'manageLike', array(
		'methods' => 'POST',
		'callback' => 'createLike'
	));

	register_rest_route('university/v1', 'manageLike', array(
		'methods' => 'DELETE',
		'callback' => 'deleteLike'
	));
}
add_action('rest_api_init', 'universityLikeRoutes');


function createLike($data){
	if (is_user_logged_in() ) {
		$professor = sanitize_text_field($data['professorId']);

		$existQuery = new Wp_Query(array(
		  'author' => get_current_user_id(),
		  'post_type' => 'like',
		  'meta_query' => array(
		    array(
		      'key' => 'liked_professor_id',
		      'compare' => '=',
		      'value' => $professor
		    )
		  )
		));

		if ($existQuery->found_posts == 0 AND get_post_type($professor) == 'professor') {
			return wp_insert_post(array(
				'post_type' => 'like',
				'post_status' => 'publish',
				'post_title' => 'like post title',
				'meta_input' => array(
					'liked_professor_id' => $professor
				)
			));
		} else{
			die("invalid professor id");
		}
		
	} else{
		die("only logged in user can like");
	}
	
}

function deleteLike($data){
	$likeId = sanitize_text_field($data['like']);
	if (get_current_user_id() == get_post_field('post_author', $likeId) AND get_post_type($likeId) == 'like') {
		wp_delete_post($likeId, true);
		return 'like deleted';
	} else{
		die("you don't have permission to delete that");
	}
}