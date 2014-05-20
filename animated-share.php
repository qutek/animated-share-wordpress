<?php
/*
Plugin Name: Animated Share
Plugin URI: http://plugins.astahdziq.in/animatedshare
Description: Animated share for social network with count
Author: Qutek
Version: 0.1
Author URI: http://www.astahdziq.in/
*/
 
function animated_share($content)
{
    // Tampilkan share button
     
    $options["page"] = get_option("anim_share_page");
    $options["post"] = get_option("anim_share_post");
     
    if ( (is_single() && $options["post"]) || (is_page() && $options["page"]) )
    {
        $title = get_the_title($post->ID);
        $anim_share = '
            <div class="wpr">
                <div class="social" id="twitter" data-url="' . get_permalink() . '" data-text="' . $title . '"></div>
                <div class="social" id="facebook" data-url="' . get_permalink() . '" data-text="' . $title . '"></div>
                <div class="social" id="google" data-url="' . get_permalink() . '" data-text="' . $title . '"></div>
            </div>
        ';
         
        return $content . $anim_share;
    } else {
        return $content;
    }
}
 
// function animated_share_style()
// {
//     echo '
//     <script type="text/javascript">
//         var plugUrl = "' . plugins_url('sharrre.php', __FILE__) . '/";
//     </script>';
//     // css yang akan di push ke wp_head
//     // wp_register_script('animated_share_style', plugins_url('css/animated-share.css', __FILE__));
//     wp_register_style( 'animated_share_style', plugins_url('css/animated-share.css', __FILE__) );
//     wp_register_script('jquery', plugins_url('js/jquery-1.7.min.js', __FILE__), false, '1.7');
//     wp_register_script('jquery_easing', plugins_url('js/jquery.easing.min.js', __FILE__), array(),'', false);
//     wp_register_script('sharrre', plugins_url('js/jquery.sharrre.js', __FILE__), array(),'', false);
//     wp_register_script('initialize', plugins_url('js/initialize.js', __FILE__), array(),'', true);

//     wp_enqueue_style('animated_share_style');
//     wp_enqueue_script('jquery');
//     wp_enqueue_script('jquery_easing');
//     wp_enqueue_script('sharrre');
//     wp_enqueue_script('initialize');
// }
 

function animated_share_settings()
{
    // tampilkan setting di halaman admin
    if ($_POST["action"] == "update")
    {
        $_POST["show_pages"] == "on" ? update_option("anim_share_page", "checked") : update_option("bio_on_page", "");
        $_POST["show_posts"] == "on" ? update_option("anim_share_post", "checked") : update_option("bio_on_post", "");
        $message = '<div id="message" class="updated fade"><p><strong>Options Saved</strong></p></div>';
    }
     
    $options["page"] = get_option("anim_share_page");
    $options["post"] = get_option("anim_share_post");
     
    echo '
    <div class="wrap">
        '.$message.'
        <h2>Animated Share</h2>
         
        <form method="post" action="">
        <input type="hidden" name="action" value="update" />
         
        <h3>Display Animated Share on : </h3>
        <input name="show_pages" type="checkbox" id="show_pages" '.$options["page"].' /> Pages<br />
        <input name="show_posts" type="checkbox" id="show_posts" '.$options["post"].' /> Posts<br />
        <br />
        <input type="submit" class="button-primary" value="Save Changes" />
        </form>
         
    </div>';
}
 
function animated_share_admin_menu()
{
    // Tampilkan option di menu
    add_options_page('Animated Share', 'Animated Share', 'manage_options', plugin_dir_path( __FILE__ ) . 'setting.php');
}
 
add_action("the_content", "animated_share");
add_action("admin_menu", "animated_share_admin_menu");
// add_action("wp_head", "animated_share_style");


function admin_assets() {
    
    wp_register_style( 'demo', plugins_url('assets/css/demo.css', __FILE__) );
    wp_register_style( 'component', plugins_url('assets/css/component.css', __FILE__) );

    wp_register_script('cbpFWTabs', plugins_url('assets/js/cbpFWTabs.js', __FILE__), array(),'', false);

    wp_enqueue_style('demo');
    wp_enqueue_style('component');
    wp_enqueue_script('jquery');
    wp_enqueue_script('cbpFWTabs');
}

add_action("admin_enqueue_scripts", "admin_assets");


function admin_inline_js(){
    echo "<script type='text/javascript'>\n";
    echo "new CBPFWTabs( document.getElementById( 'tabs' ) );";
    echo "\n</script>";
}
add_action( 'admin_print_footer_scripts', 'admin_inline_js' );
?>