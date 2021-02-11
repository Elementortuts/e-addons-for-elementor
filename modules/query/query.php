<?php

namespace EAddonsForElementor\Modules\Query;

use EAddonsForElementor\Base\Module_Base;
use EAddonsForElementor\Core\Utils;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Query extends Module_Base {

    public function __construct() {
        parent::__construct();
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_editor_assets']);
        add_action('elementor/frontend/before_enqueue_styles', [$this, 'register_libs']);
        add_filter('pre_handle_404', [$this, 'allow_posts_widget_pagination'], 10, 2);
    }

    public function allow_posts_widget_pagination($handled, $wp_query) {

        // If another plugin/theme already used this filter, exit here to avoid conflicts.
        if ($handled) {
            return true;
        }

        // Check it's not already handled and it's a single paged query.
        if ($handled || empty($wp_query->query_vars['page']) || !is_singular() || empty($wp_query->post)) {
            return $handled;
        }

        $query_widgets = $this->get_query_widgets();
        if (empty($query_widgets)) {
            return $handled;
        }

        if (\Elementor\Plugin::instance()->db->is_built_with_elementor($wp_query->post->ID) || Utils::is_plugin_active('elementor-pro')) {
            $document = \Elementor\Plugin::instance()->documents->get($wp_query->post->ID);
            if ($this->is_valid_pagination($document->get_elements_data(), $wp_query->query_vars['page'])) {
                return true;
            }
        }

        return false;
    }

    public function get_query_widgets() {
        // Get all widgets that may add pagination.
        $widgets = \Elementor\Plugin::instance()->widgets_manager->get_widget_types();
        $query_widgets = [];
        foreach ($widgets as $widget) {
            if ($widget instanceof \EAddonsForElementor\Modules\Query\Base\Query) {
                $query_widgets[] = $widget->get_name();
            }
        }
        return $query_widgets;
    }

    /**
     * Checks a set of elements if there is a posts/archive widget that may be paginated to a specific page number.
     *
     * @param array $elements
     * @param       $current_page
     *
     * @return bool
     */
    public function is_valid_pagination(array $elements, $current_page) {
        $is_valid = false;
        $query_widgets = $this->get_query_widgets();
        \Elementor\Plugin::instance()->db->iterate_data($elements, function($element) use (&$is_valid, $query_widgets, $current_page) {
            if (isset($element['widgetType']) && in_array($element['widgetType'], $query_widgets, true)) {
                // Has pagination.
                if (!empty($element['settings']['pagination_enable'])) {
                    $is_valid = true;
                }
            }
        });
        return $is_valid;
    }

    /**
     * Register libs in Frontend
     *
     * @access public
     */
    public function register_libs() {
        $this->register_script('infiniteScroll', 'assets/lib/metafizzy/infinite-scroll.pkgd.min.js', [], '3.0.6');
        $this->register_script('jquery-fitvids', 'assets/lib/fitvids/jquery.fitvids.js', [], '3.0.6');
        // 'imagesloaded' -> default
        // 'jquery-masonry' -> default
        // 'isotope'
        $this->register_script('isotope', 'assets/lib/metafizzy/isotope.pkgd.min.js', [], '3.0.6');

        $this->register_style('animatecss', 'assets/lib/animate/animate.min.css');
        $this->register_style('custom-swiper', 'assets/lib/swiper/css/swiper.min.css');
        // font-awesome     
        
        // dataTables
        $this->register_style('datatables-jquery', 'assets/lib/datatables/DataTables-1.10.23/css/jquery.dataTables.min.css');
        $this->register_style('datatables-buttons', 'assets/lib/datatables/Buttons-1.6.5/css/buttons.dataTables.min.css');
        $this->register_style('datatables-fixedHeader', 'assets/lib/datatables/FixedHeader-3.1.8/css/fixedHeader.dataTables.min.css');
        $this->register_style('datatables-responsive', 'assets/lib/datatables/Responsive-2.2.7/css/responsive.dataTables.min.css');
        $this->register_script('datatables-jquery', 'assets/lib/datatables/DataTables-1.10.23/js/jquery.dataTables.min.js');
        $this->register_script('datatables-jszip', 'assets/lib/datatables/JSZip-2.5.0/jszip.min.js');        
        $this->register_script('datatables-buttons', 'assets/lib/datatables/Buttons-1.6.5/js/dataTables.buttons.min.js');
        $this->register_script('datatables-html5', 'assets/lib/datatables/Buttons-1.6.5/js/buttons.html5.min.js');
        $this->register_script('datatables-fixedHeader', 'assets/lib/datatables/FixedHeader-3.1.8/js/dataTables.fixedHeader.min.js');
        $this->register_script('datatables-responsive', 'assets/lib/datatables/Responsive-2.2.7/js/dataTables.responsive.min.js');
    }

    /**
     * Enqueue admin styles in Editor
     *
     * @access public
     */
    public function enqueue_editor_assets() {
        $widgets = \Elementor\Plugin::instance()->widgets_manager->get_widget_types();
        if (!empty($widgets)) {
            wp_enqueue_style('e-addons-editor-query');
            wp_enqueue_script('e-addons-editor-query');
        }
    }

}
