<?php
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function enqueue_parent_styles() {
  wp_enqueue_style('parent-style', get_template_directory_uri().'/style.css');
}

function social_link($user_data, $site) {
	$icon_type = "";
  switch ($site) {
    case 'github':
        $base_url = $user_data->github ? "https://github.com/" . $user_data->github : null;
        break;
    case 'twitter':
        $base_url = $user_data->twitter ? "https://twitter.com/" . $user_data->twitter : null;
        break;
    case 'facebook':
        $base_url = $user_data->facebook ? "https://facebook.com/" . $user_data->facebook : null;
		$icon_type = "-square";
        break;
    case 'flickr':
        $base_url = $user_data->flickr ? "https://flickr.com/" . $user_data->flickr : null;
        break;
    case 'instagram':
        $base_url = $user_data->instagram ? "https://www.instagram.com/" . $user_data->instagram : null;
        break;
  }
	if (! isset($base_url)) {
		return "";
	}
	
	$url = $base_url;
	return <<<HTML
	<a href="{$url}" rel="me" class="u-url"><i class="fa fa-${site}${icon_type}" aria-hidden="true"></i></a>
HTML;
}

// Filters some IndieWeb Post Kinds out of most views
add_action( 'pre_get_posts', 'kind_filter_config' );
function kind_filter_config( $query ) {
	if ( is_admin() ) {
		return;
	}
	if ( ! is_home() ) {
		// Lets do this only for home for now
		return;
	}
	// TODO: Get this from config
	$exclude = 'like';
	if ( ! $exclude ) {
		return;
	}
	$operator = 'NOT IN';
	$filter = explode( ',', $exclude );
	$query->set(
		'tax_query',
		array(
			array(
				'taxonomy' => 'kind',
				'field' => 'slug',
				'terms' => $filter,
				'operator' => $operator,
			),
		)
	);
}

add_filter( 'post_limits', 'author_page_unlimited', 10, 2 );
function author_page_unlimited($existing_query) {
	if (is_author()) {
		return 'LIMIT 0, 9999';
	}
	return $existing_query;
}

// Allow html in author bios
remove_filter('pre_user_description', 'wp_filter_kses');
								
?>
