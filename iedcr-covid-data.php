<?php
/*
Plugin Name: IEDCR Covid-19 Live Data
Plugin URI: https://www.devsforyou.com
Description: IEDCR Covid-19 Live Data. Use shortcode <strong>[iedcr_live_data area="full"]</strong> for full-width or <strong>[iedcr_live_data area="widget"]</strong> for sidebar.
Version: 1.0.0
Author: Abdul Hannan
Author URI: https://www.devsforyou.com
License: GPLv2
Text Domain: iedcr-covid
*/



if(! defined('ABSPATH')){
    exit;
}

/**
 * The main plugin class
 */
final class Iedcr_Covid
{
    /**
     * Plugin version
     */
    const version = '1.0';

    private function __construct(){
        $this->define_constants();
        register_activation_hook( __FILE__, [$this, 'activate'] );

        add_action( 'plugins_loaded', [$this, 'init_plugin'] );

    }

     /**
     * Init plugin
     */
    public function init_plugin(){
        
        require_once 'Inc/covid.php';
        
    }

    /**
     * initializing a singleton instance
     * @return \Iedcr_Covid 
     */
    public static function init(){
        static $instance = false;
        if(!$instance){
            $instance = new self();
        }
        return $instance;
    }

    /**
     * define constants
     */
    public function define_constants(){
        define('IEDCR_COVID_VERSION', self::version);
        define('IEDCR_COVID_FILE', __FILE__);
        define('IEDCR_COVID_PATH', __DIR__);
        define('IEDCR_COVID_URL', plugins_url( '', IEDCR_COVID_FILE ));
        define('IEDCR_COVID_ASSETS', IEDCR_COVID_URL . '/assets');
    }


    /**
     * activation update options
     */
    public function activate(){

        require_once 'Inc/installer.php';

        $installer = new Iedcr\Covid\Installer();
        $installer->run();

    }

    

}

/**
 * Initializing the main plugin
 * @return \Iedcr_Covid
 */
function Iedcr_Covid(){
    return Iedcr_Covid::init();
}

// Kick-off the plugin
Iedcr_Covid();







