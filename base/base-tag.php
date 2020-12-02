<?php

namespace EAddonsForElementor\Base;

use Elementor\Core\DynamicTags\Tag;
use EAddonsForElementor\Core\Utils;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

abstract class Base_Tag extends Tag {
    
    public $data = false;

    use \EAddonsForElementor\Base\Traits\Base;

    public function get_group() {
        return 'e-addons';
    }
    
    public static function _group() {
        return array('name' => 'e-addons', 'title' => 'e-addons');
    }

    public function get_categories() {
        return Utils::get_dynamic_tags_categories();
    }
    
    /**
     * @since 2.0.0
     * @access public
     *
     * @param array $options
     *
     * @return string
     */
    public function get_content(array $options = []) {
        $settings = $this->get_settings();

        if ($this->data) {
            $value = $this->get_value($options);
        } else {
            ob_start();
            $this->render();
            $value = ob_get_clean();
        }
        
        if ( ! Utils::empty( $value ) ) {
                // TODO: fix spaces in `before`/`after` if WRAPPED_TAG ( conflicted with .elementor-tag { display: inline-flex; } );
                if ( ! Utils::empty( $settings, 'before' ) ) {
                        $value = wp_kses_post( $settings['before'] ) . $value;
                }

                if ( ! Utils::empty( $settings, 'after' ) ) {
                        $value .= wp_kses_post( $settings['after'] );
                }

                if ( static::WRAPPED_TAG ) :
                        $value = '<span id="elementor-tag-' . esc_attr( $this->get_id() ) . '" class="elementor-tag">' . $value . '</span>';
                endif;

        } elseif ( ! Utils::empty( $settings, 'fallback' ) ) {
                $value = $settings['fallback'];
                $value = Utils::get_dynamic_data($value);
        }

        return $value;
    }

}
