<?php
/*
    Our portfolio:  http://themeforest.net/user/tagDiv/portfolio
    Thanks for using our theme!
    tagDiv - 2017
*/


/**
 * Load the speed booster framework + theme specific files
 */
@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );
// load the deploy mode
require_once('td_deploy_mode.php');

// load the config
require_once('includes/td_config.php');
require_once('includes/td_config_helper.php');
add_action('td_global_after', array('td_config', 'on_td_global_after_config'), 9); //we run on 9 priority to allow plugins to updage_key our apis while using the default priority of 10


// load the wp booster
require_once('includes/wp_booster/td_wp_booster_functions.php');


require_once('includes/td_css_generator.php');
require_once('includes/shortcodes/td_misc_shortcodes.php');
require_once('includes/widgets/td_page_builder_widgets.php'); // widgets


/*
 * mobile theme css generator
 * in wp-admin the main theme is loaded and the mobile theme functions are not included
 * required in td_panel_data_source
 * @todo - look for a more elegant solution(ex. generate the css on request)
 */
require_once('mobile/includes/td_css_generator_mob.php');


/* ----------------------------------------------------------------------------
 * Woo Commerce
 */

// breadcrumb
add_filter('woocommerce_breadcrumb_defaults', 'td_woocommerce_breadcrumbs');
function td_woocommerce_breadcrumbs() {
	return array(
		'delimiter' => ' <i class="td-icon-right td-bread-sep"></i> ',
		'wrap_before' => '<div class="entry-crumbs" itemprop="breadcrumb">',
		'wrap_after' => '</div>',
		'before' => '',
		'after' => '',
		'home' => _x('Home', 'breadcrumb', 'woocommerce'),
	);
}

// use own pagination
if (!function_exists('woocommerce_pagination')) {
	// pagination
	function woocommerce_pagination() {
		echo td_page_generator::get_pagination();
	}
}

// Number of product per page 4
add_filter('loop_shop_per_page', 'td_wc_loop_shop_per_page' );
function td_wc_loop_shop_per_page($cols) {
    return 4;
}

if (!function_exists('woocommerce_output_related_products')) {
	// Number of related products
	function woocommerce_output_related_products() {
		woocommerce_related_products(array(
			'posts_per_page' => 4,
			'columns' => 4,
			'orderby' => 'rand',
		)); // Display 4 products in rows of 1
	}
}




/* ----------------------------------------------------------------------------
 * bbPress
 */
// change avatar size to 40px
function td_bbp_change_avatar_size($author_avatar, $topic_id, $size) {
	$author_avatar = '';
	if ($size == 14) {
		$size = 40;
	}
	$topic_id = bbp_get_topic_id( $topic_id );
	if ( !empty( $topic_id ) ) {
		if ( !bbp_is_topic_anonymous( $topic_id ) ) {
			$author_avatar = get_avatar( bbp_get_topic_author_id( $topic_id ), $size );
		} else {
			$author_avatar = get_avatar( get_post_meta( $topic_id, '_bbp_anonymous_email', true ), $size );
		}
	}
	return $author_avatar;
}
add_filter('bbp_get_topic_author_avatar', 'td_bbp_change_avatar_size', 20, 3);
add_filter('bbp_get_reply_author_avatar', 'td_bbp_change_avatar_size', 20, 3);
add_filter('bbp_get_current_user_avatar', 'td_bbp_change_avatar_size', 20, 3);



//add_action('shutdown', 'test_td');

function test_td () {
    if (!is_admin()){
        td_api_base::_debug_get_used_on_page_components();
    }

}


/**
 * tdStyleCustomizer.js is required
 */
if (TD_DEBUG_LIVE_THEME_STYLE) {
    add_action('wp_footer', 'td_theme_style_footer');
		// new live theme demos
	    function td_theme_style_footer() {
			    ?>
			    <div id="td-theme-settings" class="td-live-theme-demos td-theme-settings-small">
				    <div class="td-skin-body">
					    <div class="td-skin-wrap">
						    <div class="td-skin-container td-skin-buy"><a target="_blank" href="http://themeforest.net/item/newspaper/5489609?ref=tagdiv">BUY NEWSPAPER NOW!</a></div>
						    <div class="td-skin-container td-skin-header">GET AN AWESOME START!</div>
						    <div class="td-skin-container td-skin-desc">With easy <span>ONE CLICK INSTALL</span> and fully customizable options, our demos are the best start you'll ever get!!</div>
						    <div class="td-skin-container td-skin-content">
							    <div class="td-demos-list">
								    <?php
								    $td_demo_names = array();

								    foreach (td_global::$demo_list as $demo_id => $stack_params) {
									    $td_demo_names[$stack_params['text']] = $demo_id;
									    ?>
									    <div class="td-set-theme-style"><a href="<?php echo td_global::$demo_list[$demo_id]['demo_url'] ?>" class="td-set-theme-style-link td-popup td-popup-<?php echo $td_demo_names[$stack_params['text']] ?>" data-img-url="<?php echo td_global::$get_template_directory_uri ?>/demos_popup/large/<?php echo $demo_id; ?>.jpg"><span></span></a></div>
								    <?php } ?>
									<div class="td-set-theme-style-empty"><a href="#" class="td-popup td-popup-empty1"></a></div>
									<div class="td-set-theme-style-empty"><a href="#" class="td-popup td-popup-empty2"></a></div>
								    <div class="clearfix"></div>
							    </div>
						    </div>
						    <div class="td-skin-scroll"><i class="td-icon-read-down"></i></div>
					    </div>
				    </div>
				    <div class="clearfix"></div>
				    <div class="td-set-hide-show"><a href="#" id="td-theme-set-hide"></a></div>
				    <div class="td-screen-demo" data-width-preview="380"></div>
			    </div>
			    <?php
	    }

}

//td_demo_state::update_state("classy", 'full');

//print_r(td_global::$all_theme_panels_list);

/**
 * Show info message for logged users when API classes are not defined (maybe some TAGDIV plugins are not installed)
 */
add_action( 'get_footer', 'td_on_get_footer' );
function td_on_get_footer() {
	if ( is_user_logged_in() && ! td_util::tdc_is_live_editor_iframe() && td_util::get_check_installed_plugins() ) {

		ob_start();
		?>
		<script>

			setTimeout(function () {

				confirm( "Did you disable any TagDiv plugins? \nWe've got some errors at loading API files. It could happen because of a disabled TagDiv plugin!");

			}, 3000);

		</script>
		<?php

		echo ob_get_clean();
	}
}
function codex_custom_init() {
    $args = array(
      'label'  => 'News',
      'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'news' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,	
    );
    register_post_type( 'news', $args );
}
//add_action( 'init', 'codex_custom_init' );

$labels = array(
		'name'              => _x( 'News Category', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'News Category', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search News Category', 'textdomain' ),
		'all_items'         => __( 'All News Category', 'textdomain' ),
		'parent_item'       => __( 'Parent News Category', 'textdomain' ),
		'parent_item_colon' => __( 'Parent News Category:', 'textdomain' ),
		'edit_item'         => __( 'Edit News Category', 'textdomain' ),
		'update_item'       => __( 'Update News Category', 'textdomain' ),
		'add_new_item'      => __( 'Add New News Category', 'textdomain' ),
		'new_item_name'     => __( 'New News Category Name', 'textdomain' ),
		'menu_name'         => __( 'News Category', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'news_category' ),
	);

	//register_taxonomy( 'news_category', array( 'news' ), $args );

register_sidebar( array(
		'name'          => __( 'Retail Category Bottom Widget Area', 'twentyfifteen' ),
		'id'            => 'sidebar-category',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'twentyfifteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );	
register_sidebar( array(
		'name'          => __( 'Health Care Category Bottom Widget Area', 'twentyfifteen' ),
		'id'            => 'sidebar-category-2',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'twentyfifteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );	
	
function switch_main_blog_start_func( $atts ) {
	return switch_to_blog(1);
}
add_shortcode( 'switch_main_blog_start', 'switch_main_blog_start_func' );	
function switch_main_blog_end_func( $atts ) {
	return restore_current_blog();
}
add_shortcode( 'switch_main_blog_end', 'switch_main_blog_end_func' );

function get_subdomain_blog_func( $atts ) {
    $blogidsarray = explode(",", $atts['id']);
    if(!empty($atts['limit'])){
    $postlimit = $atts['limit'];
    }else{
    $postlimit = -1;    
    }
    $return = '<div id="td_uid_3_5ca99f9601f98" class="td_block_inner small-images">';
    foreach($blogidsarray as $siteid){
        switch_to_blog($siteid);
        global $post;
        $args = array( 'posts_per_page' => $postlimit,'orderby' => 'ID',	'order' => 'DESC',  );
        $myposts = get_posts( $args );
        foreach ( $myposts as $post ) : setup_postdata( $post ); 
        $fimage = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
        $fimageurl = $fimage[0];
        $return .= '<div class="td_module_16 td_module_wrap td-animation-stack">
            <div class="td-module-thumb"><a href="'.get_the_permalink($post->ID).'" rel="bookmark" class="td-image-wrap" title="'.$post->post_title.'"><img st class="entry-thumb" src="'.$fimageurl.'" alt="'.$post->post_title.'" title="'.$post->post_title.'" width="537" height="386"></a></div>
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
        endforeach; 
        wp_reset_postdata();
        restore_current_blog();
    }
    $return .= '</div>';
	return $return;
}
add_shortcode( 'get_subdomain_blogs', 'get_subdomain_blog_func' );





















