<?php

// Get global post filter data from ajax request
function global_get_ajax_request_post_filter(){
	$getFormData = $_POST['globalpostfilter'];

	global $post;
	$args = json_decode( stripslashes( $_POST['query'] ), true );
	$args = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => -1,
	);

	$post_query = new wp_query($args);

	?>

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

	<?php
		wp_reset_postdata();
	
		die();
}
