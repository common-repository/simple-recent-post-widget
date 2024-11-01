<?php
/*
Plugin Name: Simple Recent Post Widget
Plugin URI: http://dev-jobayer.com/recent-post-widget
Description: You may create simple post widget.
Version: 1.0
Author: Jobayer
Author URI: http://dev-jobayer.com
License: GPLv2 or later
Text Domain: jobayer
*/

function jb_recent_post_style(){
	wp_enqueue_style('jb-recent-post', plugins_url('/css/style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'jb_recent_post_style');
class JobayerRecentPostWidget extends WP_Widget{
	
	function __construct(){
	
		parent::__construct('jobayer-recent-post-widget', 'Simple Recent Post Widget', 'Add any post types into widget');
	
	}
	
	// Show the content on the widget
	public function widget($args, $instance){

		echo $args['before_widget'];
		
		if(!empty($instance['title'])){
		
			echo $args['before_title'];
			
			echo apply_filters('widget_title', $instance['title']);
			 
			echo $args['after_title'];
		
		}
		// Get the posts 
		
		$g_post = new WP_Query(array(
			'post_type' => $instance['jb_post_type'], 
			'posts_per_page' => $instance['jb_post_per'],
			'orderby' => $instance['jb_order_by'],
		));
		
		// Post thumbnail Size 

		add_image_size('jb-recent-post-thumb', 120, 120, array('center', 'center'));
		
		
		
		echo '<div class="jb_recent_posts"><ul>';
		while($g_post->have_posts()) : $g_post->the_post(); 
			$date_new = get_the_time("l, d F");
		
		?>
				
			
					<li>
						<div class="thumbnail_left">
							<?php if(has_post_thumbnail()) : ?>
							<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('jb-recent-post-thumb'); ?></a>
							<?php else: echo '<a href="' . get_the_permalink(). '"><img src="http://placehold.it/120x72" alt="" /></a>';  endif; ?>
						</div>
						<div class="jb_post_content">
							<div class="jb_recent_post_title">
								<a href="<?php the_permalink(); ?>"><h4><?php the_title(); ?></h4></a>
							</div>
							<div class="jb_recent_post_meta">
								<p class="jb_post_time"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></p>
							</div>
						</div>
					</li>
				
		
		<?php endwhile;
		echo '</ul></div>';
		echo $args['after_widget'];
	
	}
	
	// Add the form in sidebar
	
	public function form($instance){
	
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Recent Posts', 'jobayer' );
		$jb_post_per = ! empty( $instance['jb_post_per'] ) ? $instance['jb_post_per'] : esc_html__( '5', 'jobayer' );
		$jb_post_type = ! empty( $instance['jb_post_type'] ) ? $instance['jb_post_type'] : esc_html__( 'posts', 'jobayer' );
		$jb_order_by = ! empty( $instance['jb_order_by'] ) ? $instance['jb_order_by'] : esc_html__( 'date (post_date)', 'jobayer' );
		
		
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'jobayer' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'jb_post_per' ) ); ?>"><?php esc_attr_e( 'Posts Per page:', 'jobayer' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'jb_post_per' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'jb_post_per' ) ); ?>" type="text" value="<?php echo esc_attr( $jb_post_per ); ?>">
		</p>
		
		<table>
			
		<tr>
		<td>
		<label>Select Post Type: </label></td>
		<td><select name="<?php echo esc_attr( $this->get_field_name( 'jb_post_type' ) ); ?>">
			
			<?php 
				$p_types = get_post_types(array('public'=> true), 'names', 'and' );
				foreach($p_types as $p_type){
					echo '<option value="';
					echo $p_type;
					echo '"';
					echo ($p_type==$jb_post_type)?'selected':'';
					echo '>';
					echo $p_type; 
					echo '</option>';
					
					
				}
			?>
		
		</select>
		</td>
		</tr>
		<tr>
		<td>
		<label>Order By: </label></td>
		<td><select name="<?php echo esc_attr( $this->get_field_name( 'jb_order_by' ) ); ?>">
			
			<option value="date (post_date)" <?php echo ('date (post_date)'==$jb_order_by)?'selected':''; ?>>Latest Post</option>
			<option value="ID" <?php echo ('ID'==$jb_order_by)?'selected':''; ?>>ID</option>
			<option value="author" <?php echo ('author'==$jb_order_by)?'selected':''; ?>>Author</option>
			<option value="title" <?php echo ('title'==$jb_order_by)?'selected':''; ?>>Title</option>
			<option value="name" <?php echo ('name'==$jb_order_by)?'selected':''; ?>>Post Slug</option>
			<option value="date" <?php echo ('date'==$jb_order_by)?'selected':''; ?>>Date</option>
			<option value="modified"<?php echo ('modified'==$jb_order_by)?'selected':''; ?>>Modified</option>
			<option value="rand" <?php echo ('rand'==$jb_order_by)?'selected':''; ?>>Randomly</option>
			<option value="comment_count" <?php echo ('comment_count'==$jb_order_by)?'selected':''; ?>>Popular Post</option>
		
		</select>
		
		</td>
		</tr>
		</table>
		<?php 
	
	}

	
	
	
	
}
add_action('widgets_init', function(){
	register_widget('JobayerRecentPostWidget');
});

