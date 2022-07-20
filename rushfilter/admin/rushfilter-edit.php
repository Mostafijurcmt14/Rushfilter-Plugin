<?php
   defined( 'ABSPATH' ) || exit;
?>

<?php 
   // Include Rush Filter Global Header File
   include( plugin_dir_path(__FILE__) . 'rushfilter-header.php' ); 
?>

<!-- Rush Filter Edit Html Markup -->
<div class="rush-filter-edit-wrap">
   <div class="right-side-column">
      <div class="rushfilter-create-filter">
         <h2 class="rushfitler-heading">
            Edit Filter
         </h2>
         <?php 
               //Edit Rush Filter Single Item
            	global $wpdb;  
            	$pid = absint($_GET['eid']);
               if( isset($pid) ){
                  $table_name = $wpdb->prefix.'rush_filter';
                  $getresult = $wpdb->get_results( "select * from {$wpdb->prefix}rush_filter WHERE id='{$pid}'" );
               }
            ?>
         <div class="rushfilter-create-form">
            <form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="POST">
               <?php 
                  wp_nonce_field('rushfilter_edit_nonce', 'edit_nonce');
                  ?>
               <?php 			 
               foreach($getresult as $result){
                  if( isset($result) ){
                     //print_r($result);
               ?>
               <input type="hidden" name="action" value="rushfilter_update_action">

               <!-- Rush Filter Name Input -->
               <label for="rushfilter_name">Filter Name</label><br>
               <input type="text" id="#" name="rushfilter_name" value="<?php echo esc_attr($result->post_name) ?>"><br>

               <!-- Select Post Type For Filter -->
               <label for="rushfilter_post_type">Select Post Type</label><br>
               <select name="rushfilter_post_type" id="select-posttype">
                  <option value="" disabled selected>Choose post type</option>
                  <?php
                           echo '<option value="'.esc_attr(strtolower($result->post_type)).'" selected>'.esc_attr(strtolower($result->post_type)).'</option>';
                      ?> 
               </select>

               <br>

               <!-- Select Taxonomy For Post Type -->
               <br>
               <label for="rushfilter_post_taxonomy">Select Taxonomy</label><br>
               <?php
                  $taxonomies = get_object_taxonomies( array( 'post_type' => strtolower($result->post_type) ) );   
                  if (is_array($taxonomies)) {
					  $unset = array('product_shipping_class','product_type','product_visibility','post_format');
                     $taxonomies = array_diff($taxonomies, $unset);
                     foreach( $taxonomies as $taxonomy ) :
                        $explode_taxs = explode(',', $result->post_taxonomy);
                        //print_r($explode_taxs);
                        if(array_search( $taxonomy, $explode_taxs)){
                        ?>
                           <div class="rush-tax-checkbox">
                              <input type="checkbox" checked id="<?php echo $taxonomy; ?>" name="rushfilter_post_taxonomy[]" class="pinToggles"  value="<?php echo $taxonomy; ?>">
                              <label for="tagfilter"><?php echo $taxonomy; ?></label><br>
                           </div>
                        <?php
                        }else{
                           ?>
                           <div class="rush-tax-checkbox">
                              <input type="checkbox" id="<?php echo $taxonomy; ?>" name="rushfilter_post_taxonomy[]" class="pinToggles"  value="<?php echo $taxonomy; ?>">
                              <label for="tagfilter"><?php echo $taxonomy; ?></label><br>
                           </div>
                        <?php
                        }
                     endforeach;
                  }
		         ?>


               <br>
               <!-- <div id="rushfilter-edit-post-type-tax"></div> -->

                <!-- Set Post Per Page For Filter -->
               <label for="rushfilter_postperpage">Posts Per Page</label><br>
               <input type="range" id="#" name="rushfilter_postperpage" value="<?php echo esc_attr($result->Post_per_page) ?>"><br>
               <input type="hidden" name="update_id" value="<?php echo $pid ?>">
               <?php } } ?>

               <!-- Rush Filter Create Submit Button -->
               <?php submit_button( 'Update Filter', 'rushfilter-submit' ); ?>

            </form>
            </a>
         </div>
      </div>
   </div>
</div>