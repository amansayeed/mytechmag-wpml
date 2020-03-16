<?php
/**
 * Template Name: CXO Test Thoughts
 */
?>
<?php
/*  ----------------------------------------------------------------------------
    the search template
 */


get_header();
?>
<style>

.td_module_wrap {
    position: relative;
    padding-bottom: 0 !important;
}

.td-main-content-wrap {
    padding-bottom:3.5rem !important;
}
</style>

<?php


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
                            <h1 class="entry-title td-page-title">CXO Thoughts</h1>
<?php



$all_blog = wp_get_sites();

foreach ($all_blog as $key=>$current_blog) {

switch_to_blog($current_blog['blog_id']);

$blog_posts = get_posts(array( 
    
    'posts_per_page' => 3,
    'post_type'  => 'post',
    'meta_key'   => 'cxo_thoughts',
    'meta_value' => 'yes',
    'orderby'     => 'post_date',
    'order'       => 'DESC',
   
 ));
 
 


foreach ($blog_posts as $post) {

$title = $post->post_title;

$link = $post->guid;

$link = explode('?', $link);

$url = $link[0]. $post->post_name ;

$discription = $post->post_content;

$discription = strip_tags($discription);

$discription = substr($discription,0,100);

?>

<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
<div class="td_module_16 td_module_wrap td-animation-stack">
            <div class="td-module-thumb"><a href="<?php the_permalink(); ?>" rel="bookmark" class="td-image-wrap" title="<?php the_title(); ?>"><img class="entry-thumb td-animation-stack-type0-2" src="<?php echo $image[0]; ?>" title="<?php the_title(); ?>" width="537" height="386"></a></div>
            <div class="item-details">
                <h3 class="entry-title td-module-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
                <div class="td-module-meta-info">
                    <?php 
                    $category_detail=get_the_category($post->ID);//$post->ID
foreach($category_detail as $cd){
   // echo '<a class="td-post-category" href="' . esc_url( get_category_link( $cd->term_id ) ) . '">' . esc_html( $cd->cat_name ) . '</a>';
//echo $cd->cat_name;
}?><span class="td-post-date"><time class="entry-date updated td-module-date" datetime="<?php echo get_the_date(); ?>"><?php echo get_the_date(); ?></time></span>                </div>

                <div class="td-excerpt">
                    <?php the_excerpt(); ?>                </div>
            </div>

        </div>












<?php

}

restore_current_blog();

}


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