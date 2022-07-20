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

if ( !class_exists( 'rushFilterMainClass' ) ) {
class rushFilterMainClass {
	
	private $version;
	
/*
*
* Rush Filter Constructor Function
*/
    public function __construct() {
		
		
		// Rush Filter Activation Hook
		register_activation_hook(__FILE__, [ $this, 'rushfilter_activation_hook'] );
		
		// Rush Filter Version Time
		$this->version = time();
		
		// Rush Filter Plugin Loaded Action Hook
		add_action( 'plugins_loaded', [ $this, 'rushfilter_delete_action' ] ); 
		
		// Rush Filter Main Admin Menu Action Hook
		add_action( 'admin_menu', [ $this, 'rush_filter_admin_menu' ] ); 

		add_action( 'admin_init', [ $this, 'myplugin_settings_inits' ] );
		
		add_action( 'admin_post_rushfilter_create_action', [ $this, 'rushfilter_create_action' ] ); 
		add_action( 'admin_post_rushfilter_update_action', [ $this, 'rushfilter_update_action' ] ); 
		
		// Rush Filter Admin Head Action Hook
		add_action( 'admin_head', [ $this, 'rushfilter_admin_head' ] );

		// Rush Filter Admin Script Action Hook
		add_action( 'admin_enqueue_scripts', [ $this, 'rush_filter_admin_enqueue_script' ] ); 

		// Rush Filter FrontEnd Script Action Hook
		add_action( 'wp_enqueue_scripts', [ $this, 'rush_filter_frontend_enqueue_script' ] ); 

		// Rush Filter Post Type Tax Name Ajax Action Hook
		add_action( 'wp_ajax_get_post_tax_name_action', [ $this, 'get_post_type_tax_name' ] );

		// Rush Filter Main Post Filter Ajax Action Hook
		add_action('wp_ajax_global_post_type_action', [ $this, 'global_get_ajax_request_post_filter'] ); 
		add_action('wp_ajax_nopriv_global_post_type_action', [ $this, 'global_get_ajax_request_post_filter'] );

		// Includes Rush Filter Main View Post Template
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
* Rush Filter Admin Enqueue Scripts
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
* Rush Filter Frontend Enqueue Scripts
*
*/
	public function rush_filter_frontend_enqueue_script(){

		wp_enqueue_style( 'rush-filter-template-design', plugin_dir_url( __FILE__ ) . 'public/css/rushfilter-template-design.css',  false, $this->version );
		
		wp_enqueue_script( 'rush-filter-ui-js', plugin_dir_url( __FILE__ ) . 'public/js/jquery-ui.js', $this->version, true, 10 );

		wp_enqueue_script( 'rush-filter-frontend-js', plugin_dir_url( __FILE__ ) . 'public/js/frontend.js',['jquery'], $this->version, true, 12 );

		//Localize For Frontend Filter Post Page Global
		wp_localize_script(
			'rush-filter-frontend-js','rushfilter_frontend_global_url',[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			]
		);

		wp_enqueue_script('rush-filter-frontend-js');
	}

/*
* Rush Filter Create Admin Main Menu
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
	

// Get Post Type Tax Name From Ajax Request
public function get_post_type_tax_name(){
	?> 
	<label for="rushfilter_post_taxonomy">Select Taxonomy</label><br>
		<?php
			$itemId = $_POST['itemId'];
			$taxonomies = get_object_taxonomies( array( 'post_type' => $itemId ) );   
			if (is_array($taxonomies)) {
				
				$unset = array('product_shipping_class','product_type','product_visibility','post_format');
				$taxonomies = array_diff($taxonomies, $unset);

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


// Get Global Post Filter Data From Ajax Request
function global_get_ajax_request_post_filter(){
	require_once('includes/rushfilter-post.php');
	die();
}

/*
* Rush Filter All Admin Menu Callback Function
*
*/
	// Rush Filter Main Page Display
	function rushfilter_admin_page(){
		include( plugin_dir_path(__FILE__) . 'admin/rushfilter-create.php' );
	}
	
	// Rush Filter Insert Query For Create New Filter
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
		
		// Rush Filter Update Query 
		function rushfilter_update_action(){
			global $wpdb;
			$table_name = $wpdb->prefix.'rush_filter';
			$rushfilter_update_nonce = sanitize_text_field($_POST['edit_nonce']);
			$post_name = sanitize_text_field($_POST['rushfilter_name']);
			$post_type = sanitize_text_field($_POST['rushfilter_post_type']);
			$post_per_page = sanitize_text_field($_POST['rushfilter_postperpage']);
			if(isset($_POST['rushfilter_post_taxonomy'])){
				$post_taxonomy_implode= implode(',', $_POST['rushfilter_post_taxonomy']);
			}
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
	

	// Rush Filter Delete Item Query
	function rushfilter_delete_action(){ 
	 	if( isset($_GET['did']) ){
			global $wpdb;  
			$deleteFilter= absint($_GET['did']);
			$table_name = $wpdb->prefix.'rush_filter';
			$wpdb->delete( $table_name, array( 'id' => $deleteFilter ) );	 
	 	} 
	}
	
	// Rush Filter Include All Lists Diplay
	function all_rush_filter_lists(){
		include( plugin_dir_path(__FILE__) . 'admin/rushfilter-lists.php' );
	}

	// Rush Filter Include Edit Page
	function rush_filter_edit_page(){	
		include( plugin_dir_path(__FILE__) . 'admin/rushfilter-edit.php' );
	}

	// Rush Filter Include Settings Page
	function rush_filter_settings_page(){
		include( plugin_dir_path(__FILE__) . 'admin/rushfilter-settings.php' );
	}

	// Rush Filter Include Settings Fields
	function myplugin_settings_inits(){
		include( plugin_dir_path(__FILE__) . 'admin/rushfilter-post-settings-fields.php' );
	}
	
	// Rush Filter Remove Edit Menu From Admin Menu
	function rushfilter_admin_head(){
		remove_submenu_page( 'rushfilter-edit.php', 'rushfilter-edit' );
	}
	
}
}

$rushFilterMainClass = new rushFilterMainClass();
