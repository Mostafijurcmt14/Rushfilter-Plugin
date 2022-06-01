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
               <!-- Select post type for filter -->
               <select name="rushfilter_post_type" id="select-posttype">
                  <option value="" disabled selected>Choose post type</option>
                  <?php
                     $args       = array( 
                     'public' => true,
                     );
                     $post_types = get_post_types( $args, 'objects' );
                     unset($post_types['attachment']);
                     unset($post_types['e-landing-page']);
                     unset($post_types['elementor_library']);
                      //print_r($post_type);
                     foreach ($post_types as $post_type) {
                     	$labels = get_post_type_labels( $post_type );
                     
                     	echo '<option value="'.esc_attr($post_type->name).'">'.esc_attr($labels->name).'</option>';
                     }
                      ?> 
               </select>
               <br>
               <br>
               <div id="rushfilter-edit-post-type-tax"></div>
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