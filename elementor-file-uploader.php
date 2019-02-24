<?php
/**
 * Plugin Name: Elementor File Uploader
 * Plugin URI: http://weberz.ir/
 * Description: This plugin make a file uploader control for elementor widgets
 * Version: 1.0
 * Author: Payam Jafari (PayamWeber)
 * Author URI: http://weberz.ir/
 * Text Domain: efu
 */

namespace ElementorFileUploader;

if ( ! defined( 'ABSPATH' ) )
{
    exit; // Exit if accessed directly.
}

// setup defines
define( "EFU_DIR_PATH", plugin_dir_path( __FILE__ ) );
define( "EFU_DIR_URL", plugin_dir_url( __FILE__ ) );

class ElementorFileUploader
{
    // plugin version
    public static $version = "1.0";

    /**
     * this is instance of ElementorFileUploader class
     *
     * @var object
     */
    private static $instance;

    /**
     * this is the controls path, you can add controls at this folder too
     *
     * @var string
     */
    private static $controls_path = '/inc/controls';

    /**
     * this property is the extra mime types that makes us upload everything that we want
     * @var array
     */
    public static $extra_mime_types = [
        'image/svg' => 'svg',
        'image/svg+xml' => 'svg',
        'application/x-rar-compressed' => 'rar',
        'application/octet-stream' => [ 'rar', 'zip' ],
        'application/x-rar' => [ 'rar', 'zip' ],
        'application/zip' => 'zip',
        'application/x-zip-compressed' => 'zip',
        'multipart/x-zip' => 'zip',
        'application/x-compressed' => 'zip',
    ];

    private function __construct() { }

    /**
     * initialize the plugin
     */
    public static function init()
    {
        if ( ! self::$instance )
        {
            self::$instance = new self;

            // include files
            self::$instance->includes();

            // Register controls
            add_action( 'elementor/controls/controls_registered', [ self::$instance, 'load_controls' ] );

            // Add multiple mime types for a single ext
            add_filter( 'wp_check_filetype_and_ext', [ self::$instance, 'upload_mime_types' ], 99, 3 );
        }
    }

    /**
     * load needed files
     */
    private function includes()
    {
        include_once EFU_DIR_PATH . '/inc/functions.php';
        // $this->include_files( APD_DIR_PATH . "/inc", '', true );
    }

    /**
     * load extra elementor controls
     */
    public function load_controls()
    {
        $this->register_controls();
    }

    public function register_controls( $dir = '' )
    {
        $dir   = $dir ? $dir : EFU_DIR_PATH . self::$controls_path;
        $files = scandir( $dir );

        // remove dot member from array
        unset( $files[ array_search( '.', $files, TRUE ) ] );
        unset( $files[ array_search( '..', $files, TRUE ) ] );

        if ( $files )
        {
            foreach ( $files as $file )
            {
                if ( is_dir( $dir . '/' . $file ) )
                {
                    $this->register_controls( $dir . '/' . $file );
                } else if ( substr( $file, -4, 4 ) == '.php' )
                {
                    $first_name = reset( explode( '.', $file ) );
                    if ( $first_name != 'index' && mb_substr( $dir, strlen( $dir ) - strlen( $first_name ), strlen( $first_name ) ) == $first_name )
                    {
                        include( $dir . '/' . $file );
                        \Elementor\Plugin::instance()->controls_manager->register_control( $first_name::$_id, new $first_name );
                    }
                }
            }
        }
    }

    public function include_files( $dir, $prefix = '', $hierarchical = FALSE )
    {
        $files = scandir( $dir );
        // remove dot member from array
        unset( $files[ array_search( '.', $files, TRUE ) ] );
        unset( $files[ array_search( '..', $files, TRUE ) ] );
        // prevent empty ordered elements
        if ( count( $files ) < 1 )
        {
            return;
        }
        foreach ( $files as $file )
        {
            if ( substr( $file, -4, 4 ) == '.php' )
            {
                if ( $hierarchical === TRUE && is_dir( $dir . '/' . $file ) )
                {
                    $this->include_files( $dir . '/' . $file, $prefix, $hierarchical );
                } else
                {
                    $file_path = $dir . '/' . ( ( $prefix ) ? $prefix . substr( $file, strlen( $prefix ), strlen( $file ) ) : $file );
                    if ( file_exists( $file_path ) )
                        include( $file_path );
                }
            }
        }
    }

    /**
     * add custom file mime types for upload
     *
     * @param $check
     * @param $file
     * @param $filename
     *
     * @return array
     */
    public function upload_mime_types( $check, $file, $filename )
    {
        if ( empty( $check[ 'ext' ] ) && empty( $check[ 'type' ] ) )
        {
            /**
             * filter extra mime types for elementor file uploader plugin
             */
            $extra_mime_types = apply_filters( 'efu_mime_types', self::$extra_mime_types );
            $real_mime_type = mime_content_type( $file );

            if ( isset( $extra_mime_types[$real_mime_type] ) )
            {
                $ext = end( explode( '.', $filename ) );
                return [
                    'type' => $real_mime_type,
                    'ext' => $ext,
                    'proper_filename' => $filename
                ];
            }
        }
        return $check;
    }
}

ElementorFileUploader::init();


