<?php
defined( 'ABSPATH' ) || exit;
?>

<?php include( plugin_dir_path(__FILE__) . 'rushfilter-header.php' ); ?>



<div class="rush-filter-edit-wrap">
	<div class="right-side-column">
		<div class="rushfilter-create-filter">
		<h2 class="rushfitler-heading">
			Edit Filter
		</h2>
		
		
		
		<?php 
	// Edit Rush Filter Item

		global $wpdb;  
		 $pid = absint($_GET['eid']);
		 
		 $table_name = $wpdb->prefix.'rush_filter';
		 $getresult = $wpdb->get_results( "select * from {$wpdb->prefix}rush_filter WHERE id='{$pid}'" );
 
	?>

		
		<div class="rushfilter-create-form">
		<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="POST">
		
		<?php 
			 wp_nonce_field('rushfilter_edit_nonce', 'edit_nonce');
		?>
		
		<?php 			 
			 foreach($getresult as $result){
				 if($result){
		?>
			
		<input type="hidden" name="action" value="rushfilter_update_action">
		  <label for="fname">Filter Name</label><br>
		  <input type="text" id="#" name="rushfilter_name" value="<?php echo esc_attr($result->post_name) ?>"><br>
		  
		  <label for="rushfilter_post_type">Select Post Type</label><br>
		  
		  
		<select name="rushfilter_post_type">
		 <?php
				echo '<option value="'.esc_attr($result->post_name).'">'.esc_attr($result->post_type).'</option>';
		  ?> 
			</select><br>
			
			<label for="rushfilter_postperpage">Posts Per Page</label><br>
				 <input type="range" id="#" name="rushfilter_postperpage" value="<?php echo esc_attr($result->Post_per_page) ?>"><br>
		  
		 <input type="hidden" name="update_id" value="<?php echo $pid ?>">
		  
			 <?php } } ?>
					  
		  <?php submit_button( 'Update Filter', 'rushfilter-submit' ); ?>
		  
		  <input type="reset" class="reset-button">
		</form>
		</a>
		</div>
	</div>
	</div>
</div>



