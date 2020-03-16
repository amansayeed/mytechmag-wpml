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
        <?php

        switch ($loop_sidebar_position) {
            default:
                $query = new WP_Query( array( 'meta_key' => '_is_ns_featured_post', 'meta_value' => 'yes' ) );
                ?>
                    <div class="td-pb-span8 td-main-content">
                        <div class="td-ss-main-content">
                            <div class="td-page-header">
                                <?php locate_template('parts/page-search-box.php', true); ?>
                            </div>
                            <style>
                                .search-results .td_module_16 .item-details{min-height:107px !important;}
                                .search-results .td_module_16{padding-bottom: 10px !important;}
                            </style>
                            <?php
                            $searchfor = get_search_query(); // Get the search query for display in a headline
                            ?>
                            
                            <?php $query_string=esc_attr($query_string); // Escaping search queries to eliminate potential MySQL-injections 
                            $blogs = get_blog_list( 0,'all' ); 
                            foreach ( $blogs as $blog ): switch_to_blog($blog['blog_id']); 
                                $search = new WP_Query(array('s' => $_GET['s'],'post_type' => 'post')); 
                            	if ($search->found_posts>0) {
                            		foreach ( $search->posts as $post ) {
                            			setup_postdata($post);
                            			$fimage = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
                                        
                                        if(!empty($fimage[0])){
                                          $fimageurl = $fimage[0];  
                                        }else{
                                          $fimageurl = "https://www.mytechmag.com/wp-content/themes/Newspaper/images/no-image-png-4.png";
                                        }
                            			$author_data = get_userdata(get_the_author_meta('ID'));
                            			$returnsearch = '<div class="td_module_16 td_module_wrap td-animation-stack">
                                            <div class="td-module-thumb"><a href="'.get_the_permalink($post->ID).'" rel="bookmark" class="td-image-wrap" title="'.$post->post_title.'">
                                            <img st class="entry-thumb" src="'.$fimageurl.'" alt="'.$post->post_title.'" title="'.$post->post_title.'" width="537" height="386">
                                            </a></div>
                                            <div class="item-details">
                                                <h3 class="entry-title td-module-title"><a href="'.get_the_permalink($post->ID).'" rel="bookmark" title="">'.$post->post_title.'</a></h3>
                                                <div class="td-module-meta-info">
                                                   <span class="td-post-date"><time class="entry-date updated td-module-date" datetime="'.get_the_date().'T08:43:02+00:00">'.get_the_date().'</time></span>
                                                </div>
                                
                                                <div class="td-excerpt">
                                                    '.substr(strip_tags($post->post_content),0 , 160).'...              
                                                </div>
                                            </div>
                                
                                        </div>';
                                        echo $returnsearch;
                            		}
                            	}
                            endforeach;
                            restore_current_blog(); // Reset settings to the current blog
                            ?>
                            <?php //locate_template('loop.php', true);?>

                            <?php //echo td_page_generator::get_pagination(); ?>
                        </div>
                    </div>
                    <div class="td-pb-span4 td-main-sidebar">
                        <div class="td-ss-main-sidebar">
                            <?php get_sidebar(); ?>
                        </div>
                    </div>
                <?php
                break;

            case 'sidebar_left':
                ?>
                <div class="td-pb-span8 td-main-content <?php echo $td_sidebar_position; ?>-content">
                    <div class="td-ss-main-content">
                        <div class="td-page-header">
                            <?php locate_template('parts/page-search-box.php', true); ?>
                        </div>
                        <?php locate_template('loop.php', true);?>

                        <?php echo td_page_generator::get_pagination(); ?>
                    </div>
                </div>
	            <div class="td-pb-span4 td-main-sidebar">
		            <div class="td-ss-main-sidebar">
			            <?php get_sidebar(); ?>
		            </div>
	            </div>
                <?php
                break;

            case 'no_sidebar':
                ?>
                <div class="td-pb-span12 td-main-content">
                    <div class="td-ss-main-content">
                        <div class="td-page-header">
                            <?php locate_template('parts/page-search-box.php', true); ?>
                        </div>
                        <?php locate_template('loop.php', true);?>

                        <?php echo td_page_generator::get_pagination(); ?>
                    </div>
                </div>
                <?php
                break;
        }
        ?>
    </div> <!-- /.td-pb-row -->
</div> <!-- /.td-container -->
</div> <!-- /.td-main-content-wrap -->


<?php
get_footer();
?>