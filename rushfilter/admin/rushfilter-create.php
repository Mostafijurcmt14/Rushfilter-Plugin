<?php
   defined( 'ABSPATH' ) || exit;
?>
<?php
   // Include Rush Filter Global Header File
   include( plugin_dir_path(__FILE__) . 'rushfilter-header.php' );
?>
<!-- Rush Filter Create New Post Filter Html Markup -->
<div class="rushfilter-admin-wrap">
   <div class="main-container">
      <!-- End Right Container -->
      <div class="right-side-column">
         <div class="rushfilter-create-filter">
            <h2 class="rushfitler-heading">
               Create a New Ajax Post, Product & Custom Post Type Filter
            </h2>
            <div class="rushfilter-create-button">
               <a href="javascript:void(0)" class="rushfilter-common-button" id="rushfilterCreate"><i class="fa-solid fa-plus"></i> Create Filter</a>
            </div>
            <div class="rushfilter-create-form" id="rushfilterCreateForm">
               <form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="POST">
                  <?php 
                     wp_nonce_field('rushfilter_create_nonce', 'create_nonce');
                  ?>

                  <input type="hidden" name="action" value="rushfilter_create_action">

                  <!-- Rush Filter Name Input -->
                  <label for="rushfilter_name">Filter Name</label><br>
                  <input type="text" id="#" name="rushfilter_name"><br>

                  <!-- Select Post Type For Filter -->
                  <label for="rushfilter_post_type">Select Post Type</label><br>
                  <select name="rushfilter_post_type" id="select-posttype">
                     <option value="" disabled selected>Choose post type</option>
                     <?php
                        $args       = array( 
                        'public' => true,
                        );
                        $post_types = get_post_types( $args, 'objects' );
                        if( isset($post_types) ){
                           unset($post_types['attachment']);
                           unset($post_types['e-landing-page']);
                           unset($post_types['elementor_library']);
                            //print_r($post_type);
                           foreach ($post_types as $post_type) {
                              $labels = get_post_type_labels( $post_type );
                              echo '<option value="'.esc_attr($post_type->name).'">'.esc_attr($labels->name).'</option>';
                           }
                        }
                         ?> 
                  </select>
                  <br>

                  <!-- Select Taxonomy For Post Type -->
                  <br>
                  <div id="rushfilter-post-type-tax"></div>

                  <!-- Set Post Per Page For Filter -->
                  <div class="rushfilter-item">
                     <label for="rushfilter_postperpage">Set Post Per-Page</label><br>
                     <div class="range-input">
                        <input type="range" id="rushfilter-create-range" name="rushfilter_postperpage" min="0" max="100" value="0" step="1">
                        <div class="create-value-hidden">0</div>
                        <input type="text" id="create-rushfilter-rangevalue" value="0">
                     </div>
                  </div>
                  
                  <!-- Rush Filter Create Submit Button -->
                  <?php submit_button( 'Add Filter', 'rushfilter-submit' ); ?>

                  <!-- Rush Filter Reset Button -->
                  <input type="reset" class="reset-button">

               </form>
               </a>
            </div>
         </div>
      </div>
   </div>
</div>