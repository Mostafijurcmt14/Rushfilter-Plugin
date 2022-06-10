<?php
global $post;
global $wpdb;
global $wp_query;

   $get_post_id = $_POST['post_id_hidden'];
   $table_name = $wpdb->prefix.'rush_filter';
   $getResult_tax =  $wpdb->get_results("SELECT * FROM $table_name WHERE id = $get_post_id ");
   $tax_array = array();
 
   foreach( $getResult_tax as $getResults ) {
       array_push($tax_array, $getResults->post_taxonomy);
   }

   $explode_taxs = explode(',', $tax_array[0]);
   // print_r($explode_taxs);





$get_post_type = $_POST['post_type_hidden'];
$get_all_author = $_POST['authorname'];
$args = json_decode( stripslashes( $_POST['query'] ), true );
$args = array(
'post_type' => 'post',
'orderby' => $_POST['date'],
'post_status' => 'publish',
'order' => 'ASC',
'posts_per_page' => -1,
'author_name' => $get_all_author,
);


foreach($explode_taxs as $explode_tax){
   if(isset($_POST[$explode_tax])){
      $args['tax_query'] = array(
         'relation' => 'OR',
         array(
             'taxonomy' => $explode_tax,
             'field' => 'id',
             'terms' => $_POST[$explode_tax],
             'operator' => 'IN',
         ),	
     );
   }
}


// if(isset($_POST['category']) || $_POST['post_tag'] ){
//     $args['tax_query'] = array(
//         'relation' => 'OR',
//         array(
//             'taxonomy' => 'category',
//             'field' => 'id',
//             'terms' => $_POST['category'],
//             'operator' => 'IN',
//         ),	
//         array(
//             'taxonomy' => 'post_tag',
//             'field' => 'id',
//             'terms' => $_POST['post_tag'],
//             'operator' => 'IN',
//         ),
//     );
// }



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