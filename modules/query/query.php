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
        $this->register_script( 'gsap-lib', 'assets/lib/greensock/gsap.min.js', [], '3.5.1' );
        $this->register_script( 'gsap-CSSRulePlugin-lib', 'assets/lib/greensock/CSSRulePlugin.min.js', ['gsap-lib'], '3.5.1' );
        $this->register_script( 'gsap-InertiaPlugin-lib', 'assets/lib/greensock/InertiaPlugin.min.js', ['gsap-lib'], '3.5.1' );
        $this->register_script( 'gsap-ScrollToPlugin-lib', 'assets/lib/greensock/ScrollToPlugin.min.js', ['gsap-lib'], '3.5.1' );
        $this->register_script( 'gsap-ScrollTrigger-lib', 'assets/lib/greensock/ScrollTrigger.min.js', ['gsap-lib'], '3.5.1' );
        $this->register_script( 'gsap-SplitText-lib', 'assets/lib/greensock/SplitText.min.js', ['gsap-lib'], '3.5.1' );
        
        $this->register_script( 'threejs-lib', 'assets/lib/threejs/three.min.js', [], '120' );
        $this->register_script( 'threejs-gridtofullscreeneffect-lib', 'assets/lib/threejs/GridToFullscreenEffect.js', ['threejs-lib'], '1.0.0' );
        $this->register_script( 'threejs-OrbitControls-lib', 'assets/lib/threejs/OrbitControls.js', ['threejs-lib'], '117' );
        $this->register_script( 'threejs-CSS3DRendere-lib', 'assets/lib/threejs/CSS3DRenderer.js', ['threejs-lib'], '117' );

        $this->register_script( 'infiniteScroll', 'assets/lib/metafizzy/infinite-scroll.pkgd.min.js', [], '3.0.6' );
        $this->register_script( 'jquery-fitvids', 'assets/lib/fitvids/jquery.fitvids.js', [], '3.0.6' );
        // 'imagesloaded' -> default
        // 'jquery-masonry' -> default

        // 'isotope'
        $this->register_script( 'isotope', 'assets/lib/metafizzy/isotope.pkgd.min.js', [], '3.0.6' );

        //justifiedGallery
        $this->register_script( 'justifiedgallery', 'assets/lib/justifiedgallery/js/jquery.justifiedGallery.min.js', ['jquery'], '3.8.1' );
        $this->register_style( 'justifiedgallery', 'assets/lib/justifiedgallery/css/justifiedGallery.min.css' );
        
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
