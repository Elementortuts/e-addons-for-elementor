<?php

namespace EAddonsForElementor\Base;

use Elementor\Element_Base;
use EAddonsForElementor\Core\Utils;
use EAddonsProForm\Core\Utils\Form;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!Utils::is_plugin_active('elementor-pro') || !class_exists('ElementorPro\Modules\Forms\Classes\Action_Base')) {

    class Base_Action extends Element_Base {

        use \EAddonsForElementor\Base\Traits\Base;
    }

} else {

    class Base_Action extends \ElementorPro\Modules\Forms\Classes\Action_Base {

        use \EAddonsForElementor\Base\Traits\Base;

        /**
         * Get Label
         *
         * Returns the action label
         *
         * @access public
         * @return string
         */
        public function get_label() {
            return __('e-addons Form PRO Action', 'e-addons-for-elementor');
        }

        /**
         * Register Settings Section
         *
         * Registers the Action controls
         *
         * @access public
         * @param \Elementor\Widget_Base $widget
         */
        public function register_settings_section($widget) {
            
        }

        /**
         * Run
         *
         * Runs the action after submit
         *
         * @access public
         * @param \ElementorPro\Modules\Forms\Classes\Form_Record $record
         * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
         */
        public function run($record, $ajax_handler) {
            $fields = Form::get_form_data($record);
            $settings = $record->get('form_settings');
        }

        public function get_settings($dynamic = true, $fields = array()) {
            $post_id = !empty($_POST['post_id']) ? absint($_POST['post_id']) : false;
            $form_id = !empty($_POST['form_id']) ? sanitize_title($_POST['form_id']) : false;
            $document = false;
            if ($post_id && $form_id) {
                $document = \Elementor\Plugin::$instance->documents->get($post_id);
            }
            if ($document) {
                $form = \ElementorPro\Modules\Forms\Module::find_element_recursive($document->get_elements_data(), $form_id);
                if ($form) {
                    $widget = \Elementor\Plugin::$instance->elements_manager->create_element_instance($form);
                    if ($widget) {
                        if ($dynamic) {
                            $settings = $widget->get_settings_for_display();
                        } else {
                            $settings = $widget->get_settings();
                        }
                    }
                }
            } else {
                $settings = $record->get('form_settings');
            }
            if ($dynamic) {
                $settings = Utils::get_dynamic_data($settings, $fields);
            }
            return $settings;
        }
        
        public function get_form_id($settings = array()) {
            $element_id = false;
            if (!empty($settings['id'])) {
                $element_id = $settings['id'];
            }
            if (!empty($_POST['form_id'])) {
                $element_id = !empty($_POST['form_id']) ? sanitize_title($_POST['form_id']) : $element_id;
            }
            return $element_id;
        }

        /**
         * On Export
         *
         * Clears form settings on export
         * @access Public
         * @param array $element
         */
        public function on_export($element) {
            $tmp = array();
            if (!empty($element)) {
                foreach ($element as $key => $value) {
                    if (substr($key, 0, 2) == 'e_') {
                        $element[$key];
                    }
                }
            }
        }
        
        public function start_controls_section($widget) {
            $widget->start_controls_section(
                    'section_' . $this->get_name(),
                    [
                        'label' => '<i class="eadd-logo-e-addons eadd-ic-right"></i>'.$this->get_label(),
                        'condition' => [
                            'submit_actions' => $this->get_name(),
                        ],
                    ]
            );
        }

    }

}
