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
        $base_url = $user_data->instagram ? "https://instagram.com/" . $user_data->instagram : null;
        break;
  }
	if (! isset($base_url)) {
		return "";
	}
	
	$url = $base_url . "?rel=me";
	return <<<HTML
	<a href="{$url}">
		<i class="fa fa-${site}${icon_type}" aria-hidden="true"></i>
	</a>
HTML;
}

?>