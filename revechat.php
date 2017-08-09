<?php
/*
Plugin Name: Reve Chat
Description: REVE Chat is a powerful and intuitive real-time customer engagement software. As a customer support software, REVE Chat puts a live person on your website to personally guide and help your visitors, while they go through the various sections of your digital display. This live chat service helps them to get the most out of your web presence, while allowing you to understand their diverse needs on a one-to-one basis. REVE Chat is easy to install and use.
Version: 2.0.1
Author: ReveChat
Author URI: www.revechat.com
License: GPL2
*/
if(!class_exists('WP_Plugin_Revechat'))
{
    class WP_Plugin_Revechat
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
            // Plugin Details
            $this->plugin = new stdClass;
            $this->plugin->name = 'revechat'; // Plugin Folder
            $this->plugin->displayName = 'ReveChat'; // Plugin Name
            $this->plugin->version = '2.0.1';
            
            // Hooks
            add_action('admin_init', array(&$this, 'registerSettings'));
            //add_action('admin_menu', array(&$this, 'adminPanels'));
            
            add_action('wp_head', array(&$this, 'frontendHeader'));
            add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(&$this,'add_action_links') );

            // Add Menu Page
            add_action('admin_menu',array($this,'admin_menu'));

            //enqueue scripts
            add_action('admin_enqueue_scripts',array($this,'admin_scripts'));
        } // END public function __construct

        /**
         * Activate the frontendHeader
         */
        public static function frontendHeader()
        {
            $accountId = get_option('revechat_accountid' , '');
            if( (isset($accountId) && !empty($accountId))  ) {

                $script = "<script type='text/javascript'>";
                $script .= 'window.$_REVECHAT_API || (function(d, w) { var r = $_REVECHAT_API = function(c) {r._.push(c);}; w.__revechat_account=\''.$accountId.'\';w.__revechat_version=2;
                        r._= []; var rc = d.createElement(\'script\'); rc.type = \'text/javascript\'; rc.async = true; rc.setAttribute(\'charset\', \'utf-8\');
                        rc.src = (\'https:\' == document.location.protocol ? \'https://\' : \'http://\') + \'static.revechat.com/widget/scripts/new-livechat.js?\'+new Date().getTime();
                        var s = d.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(rc, s);
                        })(document, window);';

                $script .='</script>';

                echo $script ;

            }

        } // END public static function activate
        /*
         * show parameter section
         */
        public function registerSettings(){
            register_setting($this->plugin->name, 'revechat_accountid', 'trim');
            register_setting($this->plugin->name, 'revechat_trackingid', 'trim');
        }
        /*
         * admin panel 
         */
        public function adminPanels(){
            //add_options_page("ReveChat Dashboard" , "ReveChat" , "read" , "reveChatOptions");
            // Add a new submenu under Settings:
            add_options_page(__('ReveChat Dashboard','revechat-settings'), __('ReveChat Settings','menu-revechat'), 'manage_options', 'revechatsettings', array($this , 'reveChatOptions') );
        }
        /*
         * revechat options
         */
        public function reveChatOptions(){
            if ( !current_user_can( 'manage_options' ) )  {
                wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
            }
            // variables for the field and option names
            $accountId = 'revechat_accountid';
            
            // Read in existing option value from database
            $val_accountId = get_option( $accountId );
            
            if( isset($_POST[ $accountId ])){
                
                // Read in existing option value from POST
                $val_accountId = $_POST[ $accountId ];
                update_option( $accountId , $val_accountId );
                ?>
                <div class="updated" xmlns="http://www.w3.org/1999/html"><p><strong><?php _e('Settings saved.', 'revechat-menu' ); ?></strong></p></div>
                <?php
            }
            ?>
            <div class="wrap" id="revechat">

                <div class="reve-chat-logo">
                    <img src="<?php echo plugin_dir_url( __FILE__ )."images/logo.png";?>" alt="REVE Chat">
                </div>
                <div class="form-item form-type-item" id="edit-ajax-message">
                    <p class="ajax_message"></p>
                </div>

                <form name="form1" id="revechat-admin-settings-form" method="post" action="">

                    <?php
                    if(isset($val_accountId) && $val_accountId != 0){
                        require (plugin_dir_path( __FILE__ )."includes/remove-form.php");
                        return;
                    }

                    require (plugin_dir_path( __FILE__ )."includes/choose-form.php");
                    require (plugin_dir_path( __FILE__ )."includes/login-form.php");
                    require (plugin_dir_path( __FILE__ )."includes/signup-form.php");
                    ?>
                    
                    <p class="submit">
                        <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
                    </p>
                
                </form>
            </div>
            
            <?php 
        }

        public function admin_menu()
        {
//            add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )
            add_menu_page(__('ReveChat Dashboard','revechat-settings'), __('REVE Chat','menu-revechat'), 'manage_options', 'revechatsettings', array($this , 'reveChatOptions'), plugin_dir_url( __FILE__ )."images/favicon.png");

        }

        /**
         * Deactivate the plugin
         */
        public static function deactivate()
        {
            delete_option('revechat_accountid');
        } // END public static function deactivate

        public function admin_scripts(){
            wp_enqueue_script( 'revechat-admin-script', plugin_dir_url( __FILE__ ) . '/js/revechat-admin.js' );
            wp_enqueue_style( 'custom_wp_admin_css',plugin_dir_url( __FILE__ ).'css/admin-styles.css' );
        }

        function add_action_links ( $links ) {
             $menu_link = array(
             '<a href="' . admin_url( 'admin.php?page=revechatsettings' ) . '">Settings</a>',
             );
            return array_merge( $links, $menu_link );
            }
    } // END class WP_Plugin_Revechat
} // END if(!class_exists('WP_Plugin_Revechat'))
$revechat = new WP_Plugin_Revechat ;
register_deactivation_hook( __FILE__, array( 'WP_Plugin_Revechat', 'deactivate' ) );