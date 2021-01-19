<?php
namespace EAddonsForElementor\Modules\Query;

use EAddonsForElementor\Base\Module_Base;


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Query extends Module_Base {

    public function __construct() {
        parent::__construct();
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_editor_assets']);        
        add_action('elementor/frontend/before_enqueue_styles', [$this, 'register_libs']);
        add_filter( 'pre_handle_404', [ $this, 'allow_posts_widget_pagination' ], 10, 2 );
        
    }
    
    public function allow_posts_widget_pagination( $handled, $wp_query ) {
        // todo ... Fish salvaci tu!!!!!!!
        return true; 
	}


    /**
     * Register libs in Frontend
     *
     * @access public
     */
    public function register_libs() {
        $this->register_script( 'infiniteScroll', 'assets/lib/metafizzy/infinite-scroll.pkgd.min.js', [], '3.0.6' );
        $this->register_script( 'jquery-fitvids', 'assets/lib/fitvids/jquery.fitvids.js', [], '3.0.6' );
        // 'imagesloaded' -> default
        // 'jquery-masonry' -> default

        // 'isotope'
        $this->register_script( 'isotope', 'assets/lib/metafizzy/isotope.pkgd.min.js', [], '3.0.6' );

        $this->register_style( 'animatecss', 'assets/lib/animate/animate.min.css' );
        $this->register_style( 'custom-swiper', 'assets/lib/swiper/css/swiper.min.css' );
        // font-awesome     
    }

    /**
     * Enqueue admin styles in Editor
     *
     * @access public
     */
    public function enqueue_editor_assets() {
        wp_enqueue_style('e-addons-editor-query');
        wp_enqueue_script('e-addons-editor-query');
    }

}
