<?php
defined( 'ABSPATH' ) || exit;
?>



<?php

function rushfilter_filter($atts){

	ob_start();

	global $wp_query;
	global $post;
    
	extract( shortcode_atts( array(
		'post_type' => '',
	), $atts ) );


    // database post type column check
    global $wpdb;
	$table_name = $wpdb->prefix.'rush_filter';
	$getResult =  $wpdb->get_results("SELECT * FROM $table_name");


    $get_post_type_db = array();
    foreach( $getResult as $getResults ) {
        array_push($get_post_type_db, $getResults->post_type);
    }


    $set_post_type = array();
    if (in_array($post_type, $get_post_type_db)) {
        array_push($set_post_type, $post_type);
    }
    


    //print_r($set_post_type);

    $tax_array = array();
    $taxonomies = get_object_taxonomies( $set_post_type ); 
    foreach( $taxonomies as $taxonomy ) {
        array_push($tax_array, $taxonomy);
    }

//print_r($set_post_type);

?>


<?php

// Deafult post/product/grid style tempalte
$args = array(
    'post_type' => $set_post_type,
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'ASC',
);
$query = new wp_query($args);
?>


<div id="rushfilter-main">
    <div class="rushfilter-wrap">
        <div class="content">
            <div class="items-row">
                <div id="filters-column">
                <form action="<?php echo admin_url('admin-ajax.php') ?>" method="POST" id="filter">
                <?php
                    echo '<div id="accordion" class="rushfilter-row">';
                    if ( in_array($post_type, $get_post_type_db) ) {
                            echo '<h2 class="filter-heading">Categories <span class="icon"><img src="'. plugin_dir_url( __FILE__ ) . '../assets/images/angle-down-solid.svg' .'"></span></h2>';
                            echo '<div class="rushfilter-items">';
                            if( $terms = get_terms( array( 'post_type' => $set_post_type, 'taxonomy' => $tax_array, 'hide_empty' => true ) ) ) :
                                foreach( $terms as $term ) :
                                        echo '<div class="rushfilter-item">';
                                        echo '<input type="checkbox" class="checkbox" id="' .$term->term_id. '" name="categoryfilter[]" value="' .$term->term_id. '">
                                    <label for="categoryfilter">' .$term->name. '</label><br>';
                                        echo '</div>';
                             
                    
                                endforeach;
                    
                            endif;
                            echo '</div>';
                        }


                        if ( in_array($post_type, $get_post_type_db) ) {
                            echo '<h2 class="filter-heading">Tags <span class="icon"><img src="'. plugin_dir_url( __FILE__ ) . '../assets/images/angle-down-solid.svg' .'"></span></h2>';
                    
                            echo '<div class="rushfilter-items">';
                            if( $terms = get_terms( array( 'post_type' => $set_post_type, 'taxonomy' => $tax_array, 'hide_empty' => true ) ) ) :
                                foreach( $terms as $term ) :
                                        echo '<div class="rushfilter-item">';
                                        echo '<input type="checkbox" class="checkbox" id="' .$term->term_id. '" name="categoryfilter[]" value="' .$term->term_id. '">
                                    <label for="categoryfilter">' .$term->name. '</label><br>';
                                        echo '</div>';
                                endforeach;
                            endif;
                            echo '</div>';
                        }
                            echo '</div>';
                    
                        ?>
                    <input type="hidden" name="action" value="myfilter">
                </form>
                </div>



                <?php
if ( in_array($post_type, $get_post_type_db) ) {

                global $post;

                    $args = array(
                        'post_type' => $set_post_type,
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                    );

                    $post_query = new wp_query($args);
                ?>
                
                <div id="rushfilter-posts-column">
                    <div class="rushfilter-posts-row">
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
                                        echo plugin_dir_url( __FILE__ ) . '../assets/images/author-image.jpg';
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
                            ?></h2></a>
                            <div class="rushfilter-excerpt">
                            <?php
                                $str = get_the_excerpt($post->ID);
                                $length = 50;
                                if($post_type == "post"){	
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
                            </span></div>
                            </div>
                        </div>

                        <?php
                            endwhile;
                        ?>

                    </div>
                </div>

                <?php } ?>

            </div>
        </div>
    </div>
</div>

<?php
// End post/product/grid style tempalte
    return ob_get_clean();
}




function rushfilter_filter_checking(){
    global $wpdb;

    $table_name = $wpdb->prefix.'rush_filter';

    $getResult =  $wpdb->get_results("SELECT * FROM $table_name");

    $get_post_type_db = array();

    foreach( $getResult as $getResults ) {

        array_push($get_post_type_db, $getResults->id);

    }
    if ( $get_post_type_db) {

        add_shortcode( "rushfilter_filter","rushfilter_filter" );

    }

}
rushfilter_filter_checking();




