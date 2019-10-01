<?php get_header(); ?>

<div class="wrap">

<!-- This sets the $curauth variable -->

    <?php
    $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
	
	function is_categorized($cat) {
		return $cat->slug != 'uncategorized';
	}
	
	function name($cat) {
		return $cat->name;
	}
	
	$original_content_kinds = array(
		'article',
		'photo',
		'video',
		'audio',
		'note',
	);
    ?>

	<div class="h-card">
		<h2><span class="p-name"><?php echo $curauth->display_name; ?></span></h2>
		<div class="UserInfo">
			<div class="avatar u-photo">
				<?php echo get_avatar($curauth->id); ?>
			</div>
			<div class="info">
				<h4 class="job_title p-job-title"><?php echo $curauth->job_title; ?></h4>
				<div class="social">
					<?php echo social_link($curauth, "github"); ?>
					<?php echo social_link($curauth, "twitter"); ?>
					<?php echo social_link($curauth, "linkedin"); ?>
					<?php echo social_link($curauth, "facebook"); ?>
					<?php echo social_link($curauth, "microblog"); ?>
					<?php echo social_link($curauth, "instagram"); ?>
					<?php echo social_link($curauth, "flickr"); ?>
				</div>
			</div>
			<div class="p-note author-bio"><?php echo $curauth->user_description; ?></div>
		</div>
	</div>
	
	<div class="posts-by-user h-feed">
		<h2>All posts:</h2>
		
		<div class="PostsByType">
			
			<ul class="list original-content">
			<?php 
				$original_content = new WP_Query(array(
					'author' => $curauth->id,
					'post_type' => 'post',
					'tax_query' => array(
						array(
							'taxonomy' => 'kind',
							'field' => 'slug',
							'terms' => $original_content_kinds,
						),
					),
				));

				if ( $original_content->have_posts() ) : while ( $original_content->have_posts() ) : $original_content->the_post();
					$categories = get_the_category();
					$categories = array_filter($categories, 'is_categorized');
					$category_names = array_map('name', $categories);
					$category_string = $category_names ? ' | ' . join(' & ', $category_names) : '';

					$title = the_title_attribute( array( 'echo' => false ) ); 
					if (empty($title)) {
						$title = get_post_kind_slug();
					}

					$mf2_post = new MF2_Post( get_post() );
					$kind = $mf2_post->get( 'kind', true );
					$type = Kind_Taxonomy::get_kind_info( $kind, 'property' );
					$cite = $mf2_post->fetch( $type );

					if (get_post_kind_slug() == 'like') {
						if ($cite['publication'] == 'Twitter') {
							$title = 'Liked a post by ' . $cite['name'];
						} else {
							$title = 'Liked the post "' . $cite['name'] .'" on ' . $cite['publication'];
						}
					}
					?>
					<li class="h-entry <?php echo $kind ?>">
						<span class="kind">
							<?php echo Kind_Taxonomy::get_icon(get_post_kind_slug()); ?>
						</span>
						<span class="info">
							<a class="u-url p-name" 
							   href="<?php the_permalink() ?>" 
							   rel="bookmark" 
							   title="Permanent Link: <?php the_title_attribute(); ?>">
								<?php echo $title; ?>
							</a> |
							<span class="dt-published"><?php the_time('d M Y'); ?></span>
							<span class="p-category"><?php echo $category_string; ?></span>
						</span>
						<div class="thumbnail">
							<a href="<?php the_permalink() ?>" 
							   title="Permanent Link: <?php the_title_attribute(); ?>">
								<?php the_post_thumbnail() ?>
							</a>
						</div>
					</li>

				<?php endwhile; else: ?>

					<p><?php _e('No posts by this author.'); ?></p>

				<?php endif; ?>

			</ul>

			<?php wp_reset_postdata(); ?>
			
			<ul class="list social-interaction">
			<?php 
				$social_content = new WP_Query(array(
					'author' => $curauth->id,
					'post_type' => 'post',
					'tax_query' => array(
						array(
							'taxonomy' => 'kind',
							'field' => 'slug',
							'terms' => $original_content_kinds,
							'operator' => 'NOT IN',
						),
					),
				));
				if ($social_content->have_posts()) {
					while ($social_content->have_posts()) : $social_content->the_post();


					$categories = get_the_category();
					$categories = array_filter($categories, 'is_categorized');
					$category_names = array_map('name', $categories);
					$category_string = $category_names ? ' | ' . join(' & ', $category_names) : '';

					$title = the_title_attribute( array( 'echo' => false ) ); 
					if (empty($title)) {
						$title = get_post_kind_slug();
					}

					$mf2_post = new MF2_Post( get_post() );
					$kind = $mf2_post->get( 'kind', true );
					$type = Kind_Taxonomy::get_kind_info( $kind, 'property' );
					$cite = $mf2_post->fetch( $type );

					if (get_post_kind_slug() == 'like') {
						if ($cite['publication'] == 'Twitter') {
							$title = 'Liked a post by ' . $cite['name'];
						} else {
							$title = 'Liked the post "' . $cite['name'] .'" on ' . $cite['publication'];
						}
					}
					?>
					<li class="h-entry <?php echo $kind ?>">
						<span class="kind">
							<?php echo Kind_Taxonomy::get_icon(get_post_kind_slug()); ?>
						</span>
						<span class="info">
							<a class="u-url p-name" 
							   href="<?php the_permalink() ?>" 
							   rel="bookmark" 
							   title="Permanent Link: <?php the_title_attribute(); ?>">
								<?php echo $title; ?>
							</a> |
							<span class="dt-published"><?php the_time('d M Y'); ?></span>
							<span class="p-category"><?php echo $category_string; ?></span>
						</span>
						<div class="thumbnail">
							<a href="<?php the_permalink() ?>" 
							   title="Permanent Link: <?php the_title_attribute(); ?>">
								<?php the_post_thumbnail() ?>
							</a>
						</div>
					</li>
					<?php
					endwhile;
				}

				?>

			</ul>
		</div>
	</div>
</div>
<?php get_footer(); ?>
