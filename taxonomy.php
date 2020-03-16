<?php
/**
 * the custom taxonomy template
 * This file is loaded by WordPress on custom taxonomies. You can further customize this template
 * for specific taxonomies by copying this file to taxonomy-yourTaxonomyName.php
 */

get_header();

global $loop_module_id, $loop_sidebar_position;

// get the current taxonomy object - note that it's note complete
$current_term_obj = get_queried_object();

//read the loop variables for this specific taxonomy
$loop_module_id = td_util::get_taxonomy_option($current_term_obj->taxonomy, 'tds_taxonomy_page_layout');
$loop_sidebar_position = td_util::get_taxonomy_option($current_term_obj->taxonomy, 'tds_taxonomy_sidebar_pos');

if (empty($loop_module_id)) {
    $loop_module_id = 1; // module_1 is the default
}

// sidebar position used to align the breadcrumb on sidebar left + sidebar first on mobile issue
$td_sidebar_position = '';
if($loop_sidebar_position == 'sidebar_left') {
    $td_sidebar_position = 'td-sidebar-left';
}

?>

    <div class="td-main-content-wrap td-container-wrap">
        <div class="td-container <?php echo $td_sidebar_position; ?>">
            <div class="td-crumb-container">
                <?php echo td_page_generator::get_taxonomy_breadcrumbs($current_term_obj); // get the breadcrumbs - /includes/wp_booster/td_page_generator.php ?>
            </div>

            <!-- content -->
            <div class="td-pb-row">
                <?php
                switch ($loop_sidebar_position) {

                    default: //default: sidebar right
                        ?>
                        <div class="td-pb-span8 td-main-content">
                            <div class="td-ss-main-content">
                                <div class="td-page-header">
                                    <h1 class="entry-title td-page-title">
                                        <span><?php echo $current_term_obj->name ?></span>
                                    </h1>
                                </div>
                                <div class="td-block-row">
                                    <?php
                                    if (have_posts()) {
                                    $count = 1;    
                                    while ( have_posts() ) : the_post();
                                    $fimage = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
                                    $fimageurl = $fimage[0];
                                    $companyname = get_field( "company_name" );
                                    ?>
                                       <div class="td-block-span6">
                                            <div class="td_module_1 td_module_wrap td-animation-stack">
                                                <div class="td-module-image">
                                                    <div class="td-module-thumb"><a href="<?php the_permalink();?>" rel="bookmark" class="td-image-wrap" title="<?php the_title();?>"><img class="entry-thumb" src="<?php echo $fimageurl;?>" alt="" title="" width="200" height="160"></a></div>                
                                                </div>
                                                <h3 class="entry-title td-module-title"><a href="<?php the_permalink();?>" rel="bookmark" title="<?php the_title();?>"><?php if(!empty($companyname)){ echo $companyname;}else{the_title();}?></a></h3>
                                                <div class="td-module-meta-info">
                                                          <span class="td-post-date"><time class="entry-date updated td-module-date" datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time></span>                
                                                </div>
                                            </div>
                                            </div> <!-- ./td-block-span6 -->
                                            <?php if( $count % 2 == 0 ) echo "\n".'<div style="clear:both"></div>'; ?>
                                    <?php $count++;  endwhile; //end loop
                                    }
                                    ?>
                                </div>    
                                <?php //locate_template('loop.php', true);?>
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


                    case 'sidebar_left':
                        ?>
                        <div class="td-pb-span8 td-main-content <?php echo $td_sidebar_position ?>-content">
                            <div class="td-ss-main-content">
                                <div class="td-page-header">
                                    <h1 class="entry-title td-page-title">
                                        <span><?php echo $current_term_obj->name ?></span>
                                    </h1>
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
                                    <h1 class="entry-title td-page-title">
                                        <span><?php echo $current_term_obj->name ?></span>
                                    </h1>
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