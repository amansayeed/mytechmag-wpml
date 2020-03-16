<?php
// - Important!
// - For overwriting sidebar functionality in child-theme, copy the file 'sidebar.php' from the
// following theme path (../themes/THEME_NAME/includes/wp_booster/sidebar.php) to the child-theme root path,
// and NOT this sidebar.php file you are reading from.
// Do something
//require_once('includes/wp_booster/sidebar.php');

switch_to_blog(1);
require_once('includes/wp_booster/sidebar.php');
restore_current_blog();
