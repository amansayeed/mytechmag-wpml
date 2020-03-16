<?php
class td_category_top_posts_style_4 extends td_category_top_posts_style {
	function show_top_posts() {

		parent::render_posts_to_buffer();


		if (parent::get_rendered_post_count() == 0) {

			return; // die here
		}
		?>

		<!-- big grid -->
		<div class="td-category-grid td-container-wrap">
			<div class="td-container">
				<div class="td-pb-row">
					<div class="td-pb-span12">
					    
<div class="td_block_wrap td_block_big_grid_4 td_uid_9_5c52caa83ca7a_rand td-grid-style-1 td-hover-1 td-big-grids td-pb-border-top td_block_template_1" data-td-block-uid="td_uid_9_5c52caa83ca7a"><div id="td_uid_9_5c52caa83ca7a" class="td_block_inner"><div class="td-big-grid-wrapper">
       
					    <?php
$category = get_queried_object();
$category_id = $category->term_id;
					    ?>
	<?php
global $post;

$args = array( 'posts_per_page' => 10, 'offset'=> 0, 'category' => $category_id,'category__not_in' => array(67,106)  );
$myposts = get_posts( $args );
$max_loop=2; //This is the desired value of Looping
$count = 0; //First we set the count to be zeo
foreach( $myposts as $post ) : setup_postdata($post); 
$display_in_category_top_2_box = get_field('display_in_category_top_2_box_');
if( $display_in_category_top_2_box != "No" ){
?>
<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array("532","399") ); ?>
 <div class="td_module_mx5 td-animation-stack td-big-grid-post-0 td-big-grid-post td-big-thumb">
            <div class="td-module-thumb">
                <a href="<?php the_permalink(); ?>" rel="bookmark" class="td-image-wrap" title="<?php the_title(); ?>">
                    <img class="entry-thumb td-animation-stack-type0-2" src="<?php echo $image[0]; ?>" alt="" title="<?php the_title(); ?>">
                </a>
            </div>            
            <div class="td-meta-info-container">
                <div class="td-meta-align">
                    <div class="td-big-grid-meta">
                    <h3 class="entry-title td-module-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>                    </div>
                    <div class="td-module-meta-info">
                        <span class="td-post-date"><time class="entry-date updated td-module-date" datetime="<?php echo get_the_date(); ?>"><?php echo get_the_date(); ?></time></span>                    </div>
                </div>
            </div>

        </div>
<?php 
$count++; //Increase the value of the count by 1
    if($count==$max_loop) break; //Break the loop is count is equal to the max_loop
    } 
endforeach; ?>			    
	    

        </div><div class="clearfix"></div></div></div>
        
        
						<?php
						//echo get_the_ID().'<br>';
						//echo parent::get_buffer();
						?>
					</div>
				</div>
			</div>
		</div>
	<?php
	}
}