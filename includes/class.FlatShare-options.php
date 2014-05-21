<?php
class FlatShareSettingsPage {
    /*
     * For easier overriding we declared the keys
     * here as well as our tabs array which is populated
     * when registering settings
     */
    private $fs_main_settings_key = 'fs_main_settings';
    private $fs_style_settings_key = 'fs_style_settings';
    private $plugin_options_key = 'fs_plugin_options';
    private $plugin_settings_tabs = array();
    
    /*
     * Fired during plugins_loaded (very very early),
     * so don't miss-use this, only actions and filters,
     * current ones speak for themselves.
     */
    function __construct() {
        add_action( 'init', array( $this, 'fs_load_settings' ) );
        add_action( 'admin_init', array( $this, 'fs_reg_main_settings' ) );
        add_action( 'admin_init', array( $this, 'fs_reg_style_settings' ) );
        add_action( 'admin_menu', array( $this, 'fs_add_admin_menus' ) );
    }
    
    /*
     * Loads both the general and advanced settings from
     * the database into their respective arrays. Uses
     * array_merge to merge with default values if they're
     * missing.
     */
    function fs_load_settings() {
        $this->fs_main_settings = (array) get_option( $this->fs_main_settings_key );
        $this->fs_style_settings = (array) get_option( $this->fs_style_settings_key );
        
        // Merge with defaults
        $this->fs_main_settings = array_merge( array(
            'main_option' => 'Main value'
        ), $this->fs_main_settings );
        
        $this->fs_style_settings = array_merge( array(
            'style_option' => 'Style value'
        ), $this->fs_style_settings );
    }
    
    /*
     * Registers the general settings via the Settings API,
     * appends the setting to the tabs array of the object.
     */
    function fs_reg_main_settings() {
        $this->plugin_settings_tabs[$this->fs_main_settings_key] = 'Main';
        
        register_setting( $this->fs_main_settings_key, $this->fs_main_settings_key );
        add_settings_section( 'section_general', 'Main Flat Share Settings', array( $this, 'section_general_desc' ), $this->fs_main_settings_key );
        add_settings_field( 'main_option', 'A Main Option', array( $this, 'field_main_option' ), $this->fs_main_settings_key, 'section_general' );
    }
    
    /*
     * Registers the advanced settings and appends the
     * key to the plugin settings tabs array.
     */
    function fs_reg_style_settings() {
        $this->plugin_settings_tabs[$this->fs_style_settings_key] = 'Style';
        
        register_setting( $this->fs_style_settings_key, $this->fs_style_settings_key );
        add_settings_section( 'section_advanced', 'Style Flat Share Settings', array( $this, 'section_advanced_desc' ), $this->fs_style_settings_key );
        add_settings_field( 'style_option', 'Styling Option', array( $this, 'field_style_option' ), $this->fs_style_settings_key, 'section_advanced' );
    }
    
    /*
     * The following methods provide descriptions
     * for their respective sections, used as callbacks
     * with add_settings_section
     */
    function section_general_desc() { echo 'Main section description goes here.'; }
    function section_advanced_desc() { echo 'Style section description goes here.'; }
    
    /*
     * General Option field callback, renders a
     * text input, note the name and value.
     */
    function field_main_option() {
        ?>
        <input type="text" name="<?php echo $this->fs_main_settings_key; ?>[main_option]" value="<?php echo esc_attr( $this->fs_main_settings['main_option'] ); ?>" />
        <?php
    }
    
    /*
     * Advanced Option field callback, same as above.
     */
    function field_style_option() {
        ?>
        <input type="text" name="<?php echo $this->fs_style_settings_key; ?>[style_option]" value="<?php echo esc_attr( $this->fs_style_settings['style_option'] ); ?>" />
        <?php
    }
    
    /*
     * Called during admin_menu, adds an options
     * page under Settings called My Settings, rendered
     * using the fs_plugin_options_page method.
     */
    function fs_add_admin_menus() {
        add_options_page( 'Flat Social Settings', 'Flat Share Settings', 'manage_options', $this->plugin_options_key, array( $this, 'fs_plugin_options_page' ) );
    }
    
    /*
     * Plugin Options page rendering goes here, checks
     * for active tab and replaces key with the related
     * settings key. Uses the fs_plugin_options_tabs method
     * to render the tabs.
     */
    function fs_plugin_options_page() {
        $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->fs_main_settings_key;
        ?>
        <div class="wrap flat-share">
            <?php $this->fs_plugin_options_tabs(); ?>
            <form method="post" action="options.php">
                <?php wp_nonce_field( 'update-options' ); ?>
                <?php settings_fields( $tab ); ?>
                <?php do_settings_sections( $tab ); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
    
    /*
     * Renders our tabs in the plugin options page,
     * walks through the object's tabs array and prints
     * them one by one. Provides the heading for the
     * fs_plugin_options_page method.
     */
    function fs_plugin_options_tabs() {
        $current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->fs_main_settings_key;
        ?>
        <!-- <header class="clearfix">
            <h1>Animated Share</h1>
            <nav>
                <a href="#" class="bp-icon bp-icon-archive" data-info="Blueprints archive"><span>Go to the archive</span></a>
                <a href="#" class="bp-icon bp-icon-archive" data-info="Blueprints archive"><span>Go to the archive</span></a>
                <a href="#" class="bp-icon bp-icon-archive" data-info="Blueprints archive"><span>Go to the archive</span></a>
                <a href="#" class="bp-icon bp-icon-archive" data-info="Blueprints archive"><span>Go to the archive</span></a>
            </nav>
        </header>   --> 
        <h1>Flat Social Share</h1>
        <div id="tabs" class="tabs">
            <nav>
                <ul>
                <?php 
                    foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
                        $active = $current_tab == $tab_key ? 'tab-current' : '';
                        echo '<li class="' . $active . '"><a href="?page=' . $this->plugin_options_key . '&tab=' . $tab_key . '" class="icon-shop"><span>' . $tab_caption . '</span></a></li>'; 
                    }
                ?>
                    <!-- <li><a href="#section-1" class="icon-shop"><span>Shop</span></a></li>
                    <li><a href="#section-2" class="icon-cup"><span>Drinks</span></a></li>
                    <li><a href="#section-3" class="icon-food"><span>Food</span></a></li>
                    <li><a href="#section-4" class="icon-lab"><span>Lab</span></a></li>
                    <li><a href="#section-5" class="icon-truck"><span>Order</span></a></li> -->
                </ul>
            </nav>
        </div><!-- /tabs -->
        
        <?php
        // echo '<h2 class="nav-tab-wrapper">';
        // foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
        //     $active = $current_tab == $tab_key ? 'nav-tab-active' : '';
        //     echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->plugin_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>'; 
        // }
        // echo '</h2>';
    }
};

// Initialize the plugin
// add_action( 'plugins_loaded', create_function( '', '$settings_api_tabs_demo_plugin = new Settings_API_Tabs_Demo_Plugin;' ) );