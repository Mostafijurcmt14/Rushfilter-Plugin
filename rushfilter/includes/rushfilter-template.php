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
        'post_id' => '',
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


// Get post type db id

if (in_array($post_type, $get_post_type_db)) {
    $set_post_id = $post_id;
}

// print_r($set_post_id );

if ( in_array($post_type, $get_post_type_db) ) {
    $getResult_tax =  $wpdb->get_results("SELECT * FROM $table_name WHERE id = $set_post_id ");
    //print_r($getResult_tax );

    if($getResult_tax){
    $tax_array = array();
    //print_r($tax_array );
    
    foreach( $getResult_tax as $getResults ) {
        array_push($tax_array, $getResults->post_taxonomy);
    }
        $explode_taxs = explode(',', $tax_array[0]);
        //print_r($explode_taxs);
    
    }
}


// Get post per page from database 
$postPerPage =  $wpdb->get_results("SELECT * FROM $table_name WHERE id = $set_post_id ");
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



if(isset( $explode_taxs )){
?>

<div id="rushfilter-main">
    <div class="rushfilter-wrap">
        <div class="content">
            <div class="items-row">


                <div id="filters-column">
                <form action="" method="POST" id="rushpostfilter">


                <?php 
    
                    foreach( $explode_taxs as $explode_tax ) {
                        // print_r($explode_tax);
                ?>
                <div class="single-filter-item">
                <div id="accordion" class="rushfilter-row">
                <?php
                    if ( in_array($post_type, $get_post_type_db) ) {

                       echo '<h2 class="filter-heading">'. $explode_tax .' <span class="icon"><img src="'. plugin_dir_url( __FILE__ ) . '../assets/images/angle-down-solid.svg' .'"></span></h2>';
     
                           
                            echo '<div class="rushfilter-items">';
                            if( $terms = get_terms( array( 'post_type' => $set_post_type, 'taxonomy' => $explode_tax, 'hide_empty' => true ) ) ) :
                                foreach( $terms as $term ) :
                                    //print_r($term);
                                        echo '<div class="rushfilter-item">';
                                        echo '<div class ="input-type">';
                                        echo '<input type="checkbox" class="checkbox" id="' .$term->term_id. '" name="'. $term->taxonomy.'[]" value="' .$term->term_id. '">
                                    <label for="'. $term->taxonomy.'">' .$term->name. '</label><br>';
                                        echo '</div>';
                                        echo '<div class="post-count">';
                                        echo '<span>('.$term->count.')</span>';
                                        echo '</div>';
                                        echo '</div>';
                                endforeach;
                            endif;
                            echo '</div>';
                        }
                        ?>
                        </div>
                    </div>
                    <?php } ?>

                    
                        
            <div class="single-filter-item">
                <div id="accordion" class="rushfilter-row">
                <?php
                       echo '<h2 class="filter-heading">Post views <span class="icon"><img src="'. plugin_dir_url( __FILE__ ) . '../assets/images/angle-down-solid.svg' .'"></span></h2>';
                            echo '<div class="rushfilter-items">';
                                        echo '<div class="rushfilter-item range-input">';
                                        ?>
                                        <input type="range" id="rushfilter-range" name="rangeInput" min="0" max="100" value="0" step="1">
                                        <div class="value-hidden">6</div>
                                        <input type="text" id="rushfilter-rangevalue" value="<?php 
                                            foreach ($postPerPage as $postPer){
                                                echo $postPer->Post_per_page;
                                            }
                                        ?>">
                                       <?php
                                        echo '</div>';
                            echo '</div>';
                        ?>
                    </div>
            </div>



            <div class="single-filter-item author-items">
                <div id="accordion" class="rushfilter-row">
                <?php
                       echo '<h2 class="filter-heading">Author <span class="icon"><img src="'. plugin_dir_url( __FILE__ ) . '../assets/images/angle-down-solid.svg' .'"></span></h2>';
                            echo '<div class="rushfilter-items">';
                            $blogusers = get_users( array( 'role__in' => array( 'administrator', 'author', 'editor' ) ) );
                            foreach ( $blogusers as $user ) {
                                //print_r($user);
                                echo '<div class="rushfilter-item">';
                                echo '<input type="radio" id="rushfilter-author-radio" name="authorname" value="' . esc_html( $user->user_login ) . '"><label for="authorname">' . esc_html( $user->user_login ) . '</label><br>';
                                echo '</div>';
                                echo '<span></span>';
                            }
                            echo '</div>';
                        ?>
                    </div>
            </div>

            <input type="hidden" name="action" value="global_post_type_action">
            <input type = "hidden" id="post_type_hidden" name = "post_type_hidden" value = "<?php echo $post_type ?>">
            <input type = "hidden" id="post_id_hidden" name = "post_id_hidden" value = "<?php echo $post_id ?>">

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
                    <div class="post-filter-top-bar">
                    <h2 class="post-filter-heading">Blog posts</h2>
                    <form action="#" method="POST">
                        <select id="cars" name="cars">
                            <option value="volvo">Default sorting</option>
                            <option value="saab">ASC</option>
                            <option value="fiat">DES</option>
                        </select>
                    </form>
                    </div>
                    <div id ="filterResponse" class="rushfilter-posts-row">
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
                            ?></a></h2>
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
                            wp_reset_postdata();
                        ?>
                    
                    </div>
                    <div class="rushfilter-loadmore-row">
                                <div class="rushfilter-post-count">Showing <span class="current"></span> of <span class="total"></span></div>
                                <a href="javascript:void(0" class="rushfilter-loadmore">Load more</a>
                            </div>
                </div>

                <?php } ?>

            </div>
        </div>
    </div>
    <div id="preloader" class="lds-dual-ring hidden overlay">
        <div id="loader"></div>
    </div>
</div>

<?php
}
else{
    echo ' Filter not found! ';
}
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




