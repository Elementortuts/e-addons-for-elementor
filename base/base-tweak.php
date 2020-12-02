<?php

namespace EAddonsForElementor\Base;

use EAddonsForElementor\Core\Utils;
use Elementor\Element_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Base_Tweak extends Element_Base {

    use \EAddonsForElementor\Base\Traits\Base;

    public function __construct() {
        parent::__construct();        
        add_action('elementor/preview/enqueue_styles', [$this, 'enqueue_styles']);
    }

    public function get_name() {
        return 'e-addons-tweak';
    }

    /**
     * Get widget icon.
     *
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-apps';
    }

    public function _update_controls($controls_data, $widget) {
        return $controls_data;
    }

    public function _update_form($content, $widget) {
        return $content;
    }

    public function start_section($element, $tab = 'style') {
        $slug = sanitize_key($this->get_label());
        $element->start_controls_section(
                'e_' . $slug . '_section_' . $tab,
                [
                    'label' => '<i class="eadd-logo-e-addons eadd-ic-right"></i>' . __($this->get_label(), 'e-addons-for-elementor'),
                    'tab' => $tab,
                ]
        );
    }

}
