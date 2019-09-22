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
					<?php echo social_link($curauth, "facebook"); ?>
					<?php echo social_link($curauth, "microblog"); ?>
					<?php echo social_link($curauth, "instagram"); ?>
					<?php echo social_link($curauth, "flickr"); ?>
				</div>
			</div>
			<div class="p-note author-bio"><?php echo $curauth->user_description; ?></div>
		</div>
	</div>
	
    <h2>All posts:</h2>

    <ul>
<!-- The Loop -->

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<?php 
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
        <li class="h-entry">
            <a class="u-url p-name" 
			   href="<?php the_permalink() ?>" 
			   rel="bookmark" 
			   title="Permanent Link: <?php the_title_attribute(); ?>">
				<?php echo Kind_Taxonomy::get_icon(get_post_kind_slug()); ?>
				<?php echo $title; ?>
			</a> |
            <span class="dt-published"><?php the_time('d M Y'); ?></span>
			<span class="p-category"><?php echo $category_string; ?></span>
        </li>

    <?php endwhile; else: ?>
        <p><?php _e('No posts by this author.'); ?></p>

    <?php endif; ?>

<!-- End Loop -->

    </ul>
</div>
<?php get_footer(); ?>
