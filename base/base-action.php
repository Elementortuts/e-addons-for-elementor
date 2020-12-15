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
                        'label' => '<i class="eadd-logo-e-addons eadd-ic-right"></i>' . $this->get_label(),
                        'condition' => [
                            'submit_actions' => $this->get_name(),
                        ],
                    ]
            );
        }
        
        public function fields_filter($fields) {
            $tmp = array();
            if (!empty($fields) && is_array($fields)) {
                foreach ($fields as $akey => $adata) {
                    if ($adata != '') {
                        $tmp[$akey] = $adata;
                    }
                }
            }
            return $tmp;
        }

        public function save_extra($obj_id, $type, $settings, $fields) {
            if ($settings['e_form_save_' . ($type ? $type . '_' : '') . 'file']) {
                if (!empty($fields) && is_array($fields)) {
                    foreach ($fields as $akey => $adatas) {
                        $afield = Utils::get_field($akey, $settings);
                        if ($afield) {
                            if ($afield['field_type'] == 'upload') {
                                $files = Utils::explode(',', $adatas);
                                if (!empty($files)) {
                                    foreach ($files as $adata) {
                                        if (filter_var($adata, FILTER_VALIDATE_URL)) {
                                            //$adata = str_replace(get_bloginfo('url'), WP, $value);
                                            $filename = Utils::url_to_path($adata);
                                            if (is_file($filename)) {
                                                // Check the type of file. We'll use this as the 'post_mime_type'.
                                                $filetype = wp_check_filetype(basename($filename), null);
                                                $fileinfo = pathinfo($filename);
                                                // Prepare an array of post data for the attachment.
                                                $attachment = array(
                                                    'guid' => $adata,
                                                    'post_mime_type' => $filetype['type'],
                                                    'post_status' => 'inherit',
                                                    'post_title' => $fileinfo['filename'],
                                                    'post_parent' => $obj_id,
                                                        //'post_content' => '',
                                                );
                                                if ($obj_id <= 0) {
                                                    unset($attachment['post_parent']);
                                                }
                                                // Insert the attachment.
                                                $attach_id = wp_insert_attachment($attachment, $filename, $obj_id);
                                                // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                                                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                                                // Generate the metadata for the attachment, and update the database record.
                                                $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
                                                wp_update_attachment_metadata($attach_id, $attach_data);
                                                if ($afield['allow_multiple_upload']) {
                                                    if (is_array($fields[$akey])) {
                                                        $fields[$akey][] = $attach_id;
                                                    } else {
                                                        $fields[$akey] = array($attach_id);
                                                    }
                                                } else {
                                                    $fields[$akey] = $attach_id;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (!empty($fields) && is_array($fields)) {
                if (!empty($settings['e_form_save_' . ($type ? $type . '_' : '') . 'metas']) && is_array($settings['e_form_save_' . ($type ? $type . '_' : '') . 'metas'])) {
                    $settings['e_form_save_' . ($type ? $type . '_' : '') . 'metas'] = array_filter($settings['e_form_save_' . ($type ? $type . '_' : '') . 'metas']); // remove the "No field" empty value
                }
                foreach ($fields as $akey => $adata) {
                    if (!empty($settings['e_form_save_' . ($type ? $type . '_' : '') . 'metas']) && !in_array($akey, $settings['e_form_save_' . ($type ? $type . '_' : '') . 'metas']))
                        continue;
                    /* if ($settings['e_form_save_anonymous'] && ($akey == 'ip_address' || $akey == 'referrer' || $akey == 'user_id'))
                      continue; */
                    if ($settings['e_form_save_' . ($type ? $type . '_' : '') . 'array']) {
                        $afield = Utils::get_field($akey, $settings);
                        if ($afield) {
                            if ($afield['field_type'] == 'checkbox' || ($afield['field_type'] == 'select' && $afield['allow_multiple']) || ($afield['field_type'] == 'upload' && $afield['allow_multiple_upload'])) {
                                $adata = Utils::explode(',', $adata);
                            }
                        }
                    }
                    if ($type == 'option') {
                        $exist_opt = false;
                        if ($settings['e_form_save_' . ($type ? $type . '_' : '') . 'override'] == 'add') {
                            $exist_opt = get_option($akey);
                        }
                        if ($settings['e_form_save_' . ($type ? $type . '_' : '') . 'override'] == 'update' || !$exist_opt) {
                            update_option($akey, $adata);
                        }
                    } else {
                        update_metadata($type, $obj_id, $akey, $adata);
                    }
                }
            }
        }

        public function get_obj_id($obj_id, $type, $ajax_handler) {
            $obj_id = Utils::get_dynamic_data($obj_id);
            if (is_string($obj_id) && is_numeric($obj_id)) {
                $obj_id = intval($obj_id);
            }
            if (!$obj_id) {
                $ajax_handler->add_error_message(__($type . ' ID not valid', 'e-addons'));
                return false;
            }
            switch ($type) {
                case 'post':
                    $obj_check = get_post($obj_id);
                    break;
                case 'user':
                    $obj_check = get_user_by('ID', $obj_id);
                    break;
                case 'term':
                    $obj_check = get_term($obj_id);
                    break;
                case 'comment':
                    $obj_check = get_comment($obj_id);
                    break;
            }

            if (!$obj_check) {
                $ajax_handler->add_error_message(__($type . ' not exist', 'e-addons'));
                return false;
            }
            return $obj_id;
        }

    }

}
