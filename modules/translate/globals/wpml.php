<?php

namespace EAddonsForElementor\Modules\Translate\Globals;

use EAddonsForElementor\Base\Base_Global;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Translate extenstion
 *
 * @since 1.0.1
 */
class Wpml extends Base_Global {
    
    public function __construct() {
        parent::__construct();
        
        // WPML String Translation plugin exist check
        add_filter('wpml_elementor_widgets_to_translate', [$this, 'e_wpml_elementor_widgets_to_translate']);
    }
    
    public function get_icon() {
        return 'eadd-e-addoons-wpml';
    }
    
    public function get_pid() {
        return 6760;
    }
    
    /**
     * Adds additional translatable nodes to WPML
     *
     * @since 1.5.4
     *
     * @param  array   $nodes_to_translate WPML nodes to translate
     * @return array   $nodes_to_translate Updated nodes
     */
    public function e_wpml_elementor_widgets_to_translate($widgets) {
        $e_widgets = \Elementor\Plugin::instance()->widgets_manager->get_widget_types();
        foreach ($e_widgets as $widget) {
            if (is_subclass_of($widget, 'EAddonsForElementor\Base\Base_Widget')) {
                $fields = array();
                $controls = $widget->get_controls();
                //var_dump($stack); die();
                if (!empty($controls)) {
                    //var_dump($stack['controls']); die();
                    foreach ($controls as $akey => $acontrol) {
                        $type = false;
                        switch ($acontrol['type']) {
                            case 'text':
                            case 'heading':
                                $type = 'LINE';
                                break;
                            case 'textarea':
                                $type = 'AREA';
                                break;
                            case 'wysiwyg':
                                $type = 'VISUAL';
                                break;
                            case 'url':
                                $type = 'LINK';
                                break;
                        }

                        if ($type) {
                            $fields[] = array(
                                'field' => $akey,
                                'type' => __($acontrol['label'], 'e-addons'),
                                'editor_type' => $type, // 'LINE', 'VISUAL', 'AREA', 'LINK'
                            );
                        }
                    }
                }
                if (!empty($fields)) {
                    $widgets[$widget->get_name()] = array(
                        'conditions' => array('widgetType' => $widget->get_name()),
                        'fields' => $fields,
                    );
                }
            }
        }
        return $widgets;
    }
}