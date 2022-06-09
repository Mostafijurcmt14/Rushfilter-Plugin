<?php
/*
Plugin Name: Rush Filter
Plugin URI: https://mizu.mes
Description: Rush Filter for Post, Custom Post, Product Filter etc.
Version: 1.0.0
Author: Mostafijur Rahman
Author URI: https://mizu.me
License: GPLv2 or later
Text Domain: rush-filter
Domain Path: /languages
*/


defined( 'ABSPATH' ) || exit;
define ( "RUSHFILTER_DB_VERSION","1.0.0" );


/*
*
* Rush Filter Main Class
*/

class rushFilterMainClass {
	
	private $version;
	
/*
*
* Rush Filter Constructor Function
*/
    public function __construct() {
		
		
		// Rush Filter Activation Hook
		register_activation_hook(__FILE__, [ $this, 'rushfilter_activation_hook'] );
		
		// Rush Filter Version
		$this->version = time();
		
		// Rush Filter Plugin Loaded
		add_action( 'plugins_loaded', [ $this, 'rushfilter_delete_action' ] ); 
		
		// Rush Filter Main Admin Menu
		add_action( 'admin_menu', [ $this, 'rush_filter_admin_menu' ] ); 
		
		add_action( 'admin_post_rushfilter_create_action', [ $this, 'rushfilter_create_action' ] ); 
		add_action( 'admin_post_rushfilter_update_action', [ $this, 'rushfilter_update_action' ] ); 
		
		
		// Admin Head Action Hook
		add_action( 'admin_head', [ $this, 'rushfilter_admin_head' ] );

		
		// Rush Filter Admin Script
		add_action( 'admin_enqueue_scripts', [ $this, 'rush_filter_admin_enqueue_script' ] ); 

		// Rush Filter FrontEnd Script
		add_action( 'wp_enqueue_scripts', [ $this, 'rush_filter_frontend_enqueue_script' ] ); 

		// Rush Filter Post Type Tax Name Ajax Action
		add_action( 'wp_ajax_get_post_tax_name_action', [ $this, 'get_post_type_tax_name' ] );

		// Rush Filter Main Post Filter Ajax Action
		add_action('wp_ajax_global_post_type_action', [ $this, 'global_get_ajax_request_post_filter'] ); 
		add_action('wp_ajax_nopriv_global_post_type_action', [ $this, 'global_get_ajax_request_post_filter'] );

		// Includes
		require_once('includes/rushfilter-template.php');

		// Rush Filter Deactivation Hook
		register_deactivation_hook(__FILE__, [ $this, 'rushfilter_deactivation_hook'] );
    }
	
/*
*
* Rush Filter Activation Hook
*/
	function rushfilter_activation_hook(){
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix.'rush_filter';
		$sql = "CREATE TABLE {$table_name} (
			id INT NOT NULL AUTO_INCREMENT,
			post_name VARCHAR(200),
			post_type VARCHAR(200),
			Post_per_page INT(200),
			post_taxonomy VARCHAR(200),
			PRIMARY KEY(id)
		) $charset_collate;";
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);	

		$wpdb->insert($table_name,[
			'post_name' => 'Post Filter',
			'post_type' => 'post',
			'Post_per_page' => '6',
			'post_taxonomy' => 'category'
		]);
	}
	
/*
*
* Rush Filter Deactivation Hook
*/
	function rushfilter_deactivation_hook(){
		global $wpdb;
		$table_name = $wpdb->prefix.'rush_filter';
		$query = "TRUNCATE TABLE {$table_name}";
		$wpdb->query($query);
	}

/*
* Rush filter admin enqueue scripts
*
*/
	public function rush_filter_admin_enqueue_script(){
		
		wp_enqueue_style( 'rush-filter-fontawesome', plugin_dir_url( __FILE__ ) . 'admin/css/rushfilter-fontawesome.css', false, $this->version );

		wp_enqueue_style( 'rush-filter-admin', plugin_dir_url( __FILE__ ) . 'admin/css/rushfilter-admin.css',  false, $this->version );
		
		wp_enqueue_script( 'rush-filter-admin-js', plugin_dir_url( __FILE__ ) . 'admin/js/rushfilter-admin.js',['jquery'], $this->version, true );
	
		//Localize for admin page
		wp_localize_script('rush-filter-admin-js','url_ajax_global',[
			'ajax_url'    => admin_url( 'admin-ajax.php' ), // Ajax URL
		  ]
		); 
	}

/*
* Rush filter frontend enqueue scripts
*
*/
	public function rush_filter_frontend_enqueue_script(){

		wp_enqueue_style( 'rush-filter-template-design', plugin_dir_url( __FILE__ ) . 'assets/css/rushfilter-template-design.css',  false, $this->version );
		
		wp_enqueue_script( 'rush-filter-ui-js', plugin_dir_url( __FILE__ ) . 'assets/js/jquery-ui.js', $this->version, true, 10 );

		wp_enqueue_script( 'rush-filter-frontend-js', plugin_dir_url( __FILE__ ) . 'assets/js/frontend.js',['jquery'], $this->version, true, 12 );

		//Localize for frontend filter page global
		wp_localize_script(
			'rush-filter-frontend-js','rushfilter_frontend_global_url',[
				'ajax_url' => admin_url( 'admin-ajax.php' ), // Ajax URL
			]
		);

		wp_enqueue_script('rush-filter-frontend-js');
	}

/*
* Create admin main menu
*
*/
	function rush_filter_admin_menu() {
		// Rush Filter Main Menu
		add_menu_page( 
		__('Rush Filter', 'rush-filter'),
		__('Rush Filter', 'rush-filter'),
		'manage_options',
		'rushfilter',
		[ $this,'rushfilter_admin_page'],
		'',
		6,
		);
		
		// Rush Filter SubMenu Page
		add_submenu_page(
			'rushfilter',
			__('All Rush Filter', 'rush-filter'),
			__('All Rush Filter', 'rush-filter'),
			'manage_options', 
			'all-rushfilter',
			[ $this, 'all_rush_filter_lists'] 
		);
		add_submenu_page(
			'rushfilter',
			__('Filter Settings', 'rush-filter'),
			__('Filter Settings', 'rush-filter'),
			'manage_options',
			'rushfilter-settings',
			[ $this, 'rush_filter_settings_page' ]
		);
		add_submenu_page(
			'null',
			__('Filter Edit', 'rush-filter'),
			__('Filter Edit', 'rush-filter'),
			'manage_options',
			'rushfilter-edit',
			[ $this, 'rush_filter_edit_page' ]
		);
	}
	

// Get post type tax name from ajax request
public function get_post_type_tax_name(){
	?> 
	<label for="rushfilter_post_taxonomy">Select Taxonomy</label><br>
		<?php
			$itemId = $_POST['itemId'];
			$taxonomies = get_object_taxonomies( array( 'post_type' => $itemId ) );   
				if (is_array($taxonomies)) {
					foreach( $taxonomies as $taxonomy ) :
						?>
						<div class="rush-tax-checkbox">
						<input type="checkbox" id="<?php echo $taxonomy; ?>" name="rushfilter_post_taxonomy[]" class="pinToggles"  value="<?php echo $taxonomy; ?>">
						<label for="tagfilter"><?php echo $taxonomy; ?></label><br>
						</div>
						<?php
					endforeach;
				}
		?>
	<?php
	wp_die();  
}







// Get global post filter data from ajax request
function global_get_ajax_request_post_filter(){
	global $post;

	$get_post_type = $_POST['post_type_hidden'];

	$args = json_decode( stripslashes( $_POST['query'] ), true );
	$args = array(
	'post_type' => 'post',
	'orderby' => $_POST['date'],
	'post_status' => 'publish',
	'order' => 'ASC',
	'posts_per_page' => -1,
	);



	if(isset($_POST['categoryfilter'])){
		$args['tax_query'] = array(
			'relation' => 'OR',
			array(
				'taxonomy' => 'category',
				'field' => 'id',
				'terms' => $_POST['categoryfilter'],
				'operator' => 'IN',
			),	
		);
	}

	


	$post_query = new wp_query($args);

	?>
	<?php
	   while ( $post_query->have_posts() ) : $post_query->the_post();
	   $author_id = $post->post_author;	
	   $author_id = get_the_author_meta( 'ID' );
	   $author_image = the_author_meta( 'avatar' , $author_id );
	   ?>
	<div class="rushfilter-post-item">
	   <div class="author-head">
		  <div class="author-image">
			 <img src="<?php if( $author_image){
				the_author_meta( 'avatar' , $author_id );
				}else{
				echo plugin_dir_url( __FILE__ ) . '/assets/images/author-image.jpg';
				} ?>">
		  </div>
		  <div class="author-name">
			 <h3><?php 
				$get_author = get_the_author_meta( 'user_nicename', $author_id );
				echo $get_author;
				?> <span><?php
				$first_name = get_the_author_meta( 'first_name', $author_id );
				$last_name = get_the_author_meta( 'last_name', $author_id );
				$full_name = "{$first_name} {$last_name}";
				echo $full_name;
				?></span></h3>
		  </div>
	   </div>
	   <div class="feature-image">
		  <a href="<?php the_permalink(); ?>">
		  <?php
			 if( has_post_thumbnail() ){
				 the_post_thumbnail();   
			 }
			 ?>
		  </a>
	   </div>
	   <?php
		  $posttags = get_the_tags($post->ID);
		  if ($posttags) {
			foreach($posttags as $tag) {
				?>
	   <h3 class="rushfilter-subheading"><?php echo $tag->name . ' '; ?></h3>
	   <?php
		  }
		  }
		  ?>
	   <h2 class="rushfilter-heading"><a href="<?php the_permalink(); ?>"><?php
		  the_title();
		  ?></a></h2>
	   <div class="rushfilter-excerpt">
		<?php
			 global $post;
			$str = get_the_excerpt($post->ID);
			$length = 50;
			if($get_post_type == "post"){	
				if (strlen($str) > $length){
					$str = substr($str, 0, $length) . '...';
				}
			}
			echo $str;
			
		?>
	   </div>
	   <div class="rushfilter-meta-info">
		  <div class="date"><span>
			 <?php
				$post_date = get_the_date( 'l, j F Y', $post->ID ); 
				echo $post_date;
				?>
			 </span>
		  </div>
	   </div>
	</div>
	<?php
	   endwhile;
	   	wp_reset_postdata();
	   ?>
	<?php

	die();
	}







/*
* Rush filter all admin menu calback function
*
*/

	// Rush Filter Main Page Display
	function rushfilter_admin_page(){
		include( plugin_dir_path(__FILE__) . 'admin/rushfilter-create.php' );
	}
	
	// Add New Filter
	function rushfilter_create_action(){
		global $wpdb;
			$table_name = $wpdb->prefix.'rush_filter';


			$rushfilter_create_nonce = sanitize_text_field($_POST['create_nonce']);
			$post_name = sanitize_text_field($_POST['rushfilter_name']);
			$post_type = sanitize_text_field($_POST['rushfilter_post_type']);
			$post_per_page = sanitize_text_field($_POST['rushfilter_postperpage']);

			$post_taxonomy_implode= implode(',', $_POST['rushfilter_post_taxonomy']);
			$post_taxonomy = sanitize_text_field($post_taxonomy_implode);


			if( wp_verify_nonce($rushfilter_create_nonce, 'rushfilter_create_nonce') ){
			$wpdb->insert($table_name,[
			'post_name' => $post_name,
			'post_type' => $post_type,
			'Post_per_page' => $post_per_page,
			'post_taxonomy' => $post_taxonomy,
			]);
			wp_redirect( admin_url( 'admin.php?page=all-rushfilter' ) );

			}
		}
		
		
		// Update Filter
		function rushfilter_update_action(){
		global $wpdb;
			$table_name = $wpdb->prefix.'rush_filter';


			$rushfilter_update_nonce = sanitize_text_field($_POST['edit_nonce']);
			$post_name = sanitize_text_field($_POST['rushfilter_name']);
			$post_type = sanitize_text_field($_POST['rushfilter_post_type']);
			$post_per_page = sanitize_text_field($_POST['rushfilter_postperpage']);

			$post_taxonomy_implode= implode(',', $_POST['rushfilter_post_taxonomy']);
			$post_taxonomy = sanitize_text_field($post_taxonomy_implode);
			
			$id = sanitize_text_field( $_POST['update_id'] );

			if( wp_verify_nonce($rushfilter_update_nonce, 'rushfilter_edit_nonce') ){
				
			$wpdb->update($table_name,[
			'post_name' => $post_name,
			'post_type' => $post_type,
			'Post_per_page' => $post_per_page,
			'post_taxonomy' => $post_taxonomy],
			[ 'id' => $id ]
			);
			wp_redirect( admin_url( 'admin.php?page=all-rushfilter' ) );

			}
		}
	

	// Delete Rush Filter Item
	 function rushfilter_delete_action(){ 
	 if( isset($_GET['did']) ){
		global $wpdb;  
		 $deleteFilter= absint($_GET['did']);
		 $table_name = $wpdb->prefix.'rush_filter';
		 $wpdb->delete( $table_name, array( 'id' => $deleteFilter ) );	 
	 } 
	}
	
	// Rush Filter All Lists Diplay
	function all_rush_filter_lists(){
		include( plugin_dir_path(__FILE__) . 'admin/rushfilter-lists.php' );
	}

	// Rush Filter Edit Page
	function rush_filter_edit_page(){	
		include( plugin_dir_path(__FILE__) . 'admin/rushfilter-edit.php' );
	}

	
	// Rush Filter Settings Page
	function rush_filter_settings_page(){
		include( plugin_dir_path(__FILE__) . 'admin/rushfilter-settings.php' );
	}
	
	
	// Rush Filter Remove Edit Menu From Admin Menu
	function rushfilter_admin_head(){
		remove_submenu_page( 'rushfilter-edit.php', 'rushfilter-edit' );
	}
	
}

$rushFilterMainClass = new rushFilterMainClass();
