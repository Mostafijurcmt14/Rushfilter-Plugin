<?php
   defined( 'ABSPATH' ) || exit;
    // Rush Filter Header File Include
    include( plugin_dir_path(__FILE__) . 'rushfilter-header.php' ); 
?>

<?php 
    // Include Rush Filter Settings Fields
    include( plugin_dir_path(__FILE__) . 'rushfilter-post-settings-fields.php' );
    
    // Rush Filter Category Label Text Change Input Field
    function rushfilter_cat_settings_input_field_callback() {
        $get_opt_cat = get_option( 'rushfilter_tax_name_settings_options' );
        ?>
        <input type="text" name="rushfilter_tax_name_settings_options" value="<?php echo isset( $get_opt_cat ) ? esc_attr( $get_opt_cat ) : ''; ?>">
        <?php
    }

    // Rush Filter Category Label Text Change Input Field
    function rushfilter_tag_settings_input_field_callback() {
        $get_opt_tag = get_option( 'rushfilter_tag_name_settings_options' );
        ?>
        <input type="text" name="rushfilter_tag_name_settings_options" value="<?php echo isset( $get_opt_tag ) ? esc_attr( $get_opt_tag ) : ''; ?>">
        <?php
    }

    // Rush Filter Category Label Text Change Input Field
    function rushfilter_post_view_input_field_callback() {
        $get_opt_post_view = get_option( 'rushfilter_post_view_settings_options' );
        ?>
        <input type="text" name="rushfilter_post_view_settings_options" value="<?php echo isset( $get_opt_post_view ) ? esc_attr( $get_opt_post_view ) : ''; ?>">
        <?php
    }

    // Rush Filter Category Label Text Change Input Field
    function rushfilter_post_author_input_field_callback() {
        $get_opt_post_author = get_option( 'rushfilter_post_author_settings_options' );
        ?>
        <input type="text" name="rushfilter_post_author_settings_options" value="<?php echo isset( $get_opt_post_author ) ? esc_attr( $get_opt_post_author ) : ''; ?>">
        <?php
    }





      // Rush Filter Product Category Label Text Change Input Field
      function rushfilter_product_cat_settings_input_field_callback() {
        $get_opt_product_cat = get_option( 'rushfilter_product_cat_name_settings_options' );
        ?>
        <input type="text" name="rushfilter_product_cat_name_settings_options" value="<?php echo isset( $get_opt_product_cat ) ? esc_attr( $get_opt_product_cat ) : ''; ?>">
        <?php
    }
     
?>

<?php
// Show Update messages
   if ( isset( $_GET['settings-updated'] ) ) {
    // add settings saved message with the class of "updated"
    add_settings_error( 'rushfilter_messages', 'rushfilter_message', __( 'Settings Saved', 'rush-filter' ), 'updated' );
}
// show error/update messages
settings_errors( 'rushfilter_messages' );
?>

<!-- Start Settings Template Page -->
<div class="wrap rushfilter-settings-wrap">
    <form action="options.php" method="post">
        <?php
              // Security Field
              settings_fields( 'rushfilter-settings' );

              // Output Settings Section Here
              do_settings_sections('rushfilter-settings');

              // Save Settings Button
              submit_button( 'Save Change' );
        ?>
    </form>
</div>




