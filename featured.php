<?php
/**
 * Template Name: Featured
 */
 ?>
 <?php
/*  ----------------------------------------------------------------------------
    the search template
 */


get_header();



//set the template id, used to get the template specific settings
$template_id = 'search';

//prepare the loop variables
global $loop_module_id, $loop_sidebar_position;

/* after */
$loop_module_id = td_util::get_option('tds_' . $template_id . '_page_layout', 16); //module 16 is default
$loop_sidebar_position = td_util::get_option('tds_' . $template_id . '_sidebar_pos'); //sidebar right is default (empty)

// sidebar position used to align the breadcrumb on sidebar left + sidebar first on mobile issue
$td_sidebar_position = '';
if($loop_sidebar_position == 'sidebar_left') {
	$td_sidebar_position = 'td-sidebar-left';
}

td_global::$custom_no_posts_message = __td('No results for your search', TD_THEME_NAME);


?>
<div class="td-main-content-wrap td-container-wrap">

<div class="td-container <?php echo $td_sidebar_position; ?>">
    <div class="td-crumb-container">
        <?php echo td_page_generator::get_search_breadcrumbs(); ?>
    </div>
    <div class="td-pb-row">
        <div class="td-pb-span8 td-main-content">
                        <div class="td-ss-main-content search-results">
                            <h1 class="entry-title td-page-title">Featured Article</h1>
<?php
//$all_blog = get_last_updated();
$all_blog = wp_get_sites();
                                $countpost = 1;
                                foreach ($all_blog as $key=>$current_blog) {
                                    if($current_blog['blog_id'] != 1){    
                                    switch_to_blog($current_blog['blog_id']);

                                    global $post;
$custom_query_args = array(
  'post_type'  => 'post',
  'meta_key'   => '_is_ns_featured_post',
  'meta_value' => 'yes',
   'order' => 'ASC',
  );
// Get current page and append to custom query parameters array
$custom_query_args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

$custom_query = new WP_Query( $custom_query_args ); ?>
<?php
// Pagination fix
global $wp_query;
$temp_query = $wp_query;
$wp_query   = NULL;
$wp_query   = $custom_query;
?>
<?php if ( $custom_query->have_posts() ) : ?>

  <!-- the loop -->
  <?php while ( $custom_query->have_posts() ) : $custom_query->the_post(); ?>
  <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
<div class="td_module_16 td_module_wrap td-animation-stack">
            <div class="td-module-thumb"><a href="<?php the_permalink(); ?>" rel="bookmark" class="td-image-wrap" title="<?php the_title(); ?>"><img class="entry-thumb td-animation-stack-type0-2" src="<?php echo $image[0]; ?>" title="<?php the_title(); ?>" width="537" height="386"></a></div>
            <div class="item-details">
                <h3 class="entry-title td-module-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title();  echo $current_blog['blog_id'];?></a></h3>
                <div class="td-module-meta-info">
                    <?php 
                    $category_detail=get_the_category($post->ID);//$post->ID
foreach($category_detail as $cd){
    echo '<a class="td-post-category" href="' . esc_url( get_category_link( $cd->term_id ) ) . '">' . esc_html( $cd->cat_name ) . '</a>';
//echo $cd->cat_name;
}?><span class="td-post-date"><time class="entry-date updated td-module-date" datetime="<?php echo get_the_date(); ?>"><?php echo get_the_date(); ?></time></span>                </div>

                <div class="td-excerpt">
                    <?php the_excerpt(); ?>                </div>
            </div>

        </div>

  <?php endwhile; ?>
  <!-- end of the loop -->

  <!-- pagination here -->
  <?php
  // Custom query loop pagination
  previous_posts_link( 'Older Posts' );
  next_posts_link( 'Newer Posts', $custom_query->max_num_pages );
  ?>

<?php endif; ?>

<?php
// Reset postdata
wp_reset_postdata();

}
}
?>

<?php
// Reset main query object
$wp_query = NULL;
$wp_query = $temp_query;
?>
</div>
                    </div>
                    <div class="td-pb-span4 td-main-sidebar">
                        <div class="td-ss-main-sidebar">
                            <?php get_sidebar(); ?>
                        </div>
                    </div>
    </div> <!-- /.td-pb-row -->
</div> <!-- /.td-container -->
</div> <!-- /.td-main-content-wrap -->


<?php
get_footer();
?>