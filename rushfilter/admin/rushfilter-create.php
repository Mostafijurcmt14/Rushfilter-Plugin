<?php
   defined( 'ABSPATH' ) || exit;
   ?>
<?php 
   include( plugin_dir_path(__FILE__) . 'rushfilter-header.php' );
    ?>
<div class="rushfilter-admin-wrap">
   <div class="main-container">
      <div class="main-menu-column">
         <div class="menu-box block">
            <!-- MENU BOX (LEFT-CONTAINER) -->
            <h2 class="titular">MENU BOX</h2>
            <ul class="menu-box-menu">
               <li>
                  <a class="menu-box-tab" href="#6"><i class="fa-regular fa-filter"></i> Filter Generate</a>
               </li>
               <li>
                  <a class="menu-box-tab" href="#8"><i class="fa-solid fa-bars"></i> All Filters</a>
               </li>
               <li>
                  <a class="menu-box-tab" href="#10"><i class="fa-solid fa-calendar-days"></i> Events</a>
               </li>
               <li>
                  <a class="menu-box-tab" href="#12"><i class="fa-regular fa-gear"></i> Account Settings</a>
               </li>
            </ul>
         </div>
      </div>
      <!-- end right-container -->
      <div class="right-side-column">
         <div class="rushfilter-create-filter">
            <h2 class="rushfitler-heading">
               Create a New Ajax Post Filter
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
                  <label for="fname">Filter Name</label><br>
                  <input type="text" id="#" name="rushfilter_name"><br>
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
                  <!-- Select taxonomy for post type -->
                  <br>
                  <div id="rushfilter-post-type-tax"></div>
                  <!-- <label for="rushfilter_postperpage">Posts Per Page</label><br>
                     <input type="range" id="#" name="rushfilter_postperpage" value="6"><br> -->
                  <?php submit_button( 'Add Filter', 'rushfilter-submit' ); ?>
                  <input type="reset" class="reset-button">
               </form>
               </a>
            </div>
         </div>
      </div>
   </div>
</div>