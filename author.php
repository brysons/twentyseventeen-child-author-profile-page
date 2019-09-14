<?php get_header(); ?>

<?php 
define( 'WP_DEBUG', true ); 
define( 'WP_DEBUG_DISPLAY', true);
?>

<div class="wrap">

<!-- This sets the $curauth variable -->

    <?php
    $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
    ?>

    <h2>About: <?php echo $curauth->display_name; ?></h2>
    <div class="UserInfo">
		<div class="avatar">
			<?php echo get_avatar($curauth->id); ?>
		</div>
		<div class="info">
			<h4 class="job_title"><?php echo $curauth->job_title; ?></h4>
			<div class="social">
				<?php echo social_link($curauth, "github"); ?>
				<?php echo social_link($curauth, "twitter"); ?>
				<?php echo social_link($curauth, "facebook"); ?>
				<?php echo social_link($curauth, "microblog"); ?>
				<?php echo social_link($curauth, "instagram"); ?>
				<?php echo social_link($curauth, "flickr"); ?>
			</div>
		</div>
        <div><?php echo $curauth->user_description; ?></div>
    </div>
	
	<div class="data" style="display: none">
		all data: <?php echo json_encode(get_user_meta($curauth->id)); ?>
	</div>
	
	<div class="preview" style="display: none">
		
	</div>

    <h2>Posts by <?php echo $curauth->display_name; ?>:</h2>

    <ul>
<!-- The Loop -->

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <li>
            <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>">
            <?php the_title(); ?></a>,
            <?php the_time('d M Y'); ?> in <?php the_category('&');?>
        </li>

    <?php endwhile; else: ?>
        <p><?php _e('No posts by this author.'); ?></p>

    <?php endif; ?>

<!-- End Loop -->

    </ul>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
