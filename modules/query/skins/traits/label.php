<?php
namespace EAddonsForElementor\Modules\Query\Skins\Traits;

use EAddonsForElementor\Core\Utils;
/**
 * Description of Common
 *
 * @author fra
 */
trait Label {
    
    public function get_item_label($item) {
        $label = !empty($item['item_text_label']) ? $item['item_text_label'] : '';
        if (empty($label)) {
            if ($item['item_type'] == 'item_custommeta') {
                $label = ucfirst($item['metafield_key']);
                $label = str_replace('-', ' ', $label);
                $label = str_replace('_', ' ', $label);
            } else {
                $label = ucfirst(str_replace('item_', '', $item['item_type']));
            }
        }
        return $label;
    }

    protected function render_label_before_item($settings, $default_label = '') {
        if (!empty($settings['use_label_before'])) {
            $label_text = $this->get_item_label($settings); //!empty($settings['item_text_label']) ? $settings['item_text_label'] : '';

            $start_label = '<span class="e-add-label-before">';
            $end_label = '</span>';
            
            $the_label = '';
            if($default_label) $the_label = $default_label;
            if($label_text) $the_label = $label_text.': ';

            if( $the_label != '' )
            return $start_label . $the_label . $end_label;
        }
    }
    protected function render_item_labelhtml($settings) {
        // Settings ------------------------------
        $label_html_type = $settings['label_html_type'];

        if ( !empty($label_html_type) ) {
            switch ($label_html_type) {
                case 'text':
                    $html_label = $settings['label_html_text'];
                    
                    break;
                case 'image':
                    $setting_key = $settings['label_html_image_size_size'];
                    $image_id = $settings['label_html_image']['id'];
                    $image_attr = [
                        'class' => $this->get_image_class()
                    ];
                    $html_label = wp_get_attachment_image($image_id, $setting_key, false, $image_attr);
                    break;
                case 'icon':
                    
                    $html_label = $this->render_item_icon($settings,'label_html_icon','labelicon','e-add-query-icon');
                    
                    break;
                case 'code':
                    $html_label = $settings['label_html_code'];

                    break;
                case 'wysiwyg':
                    $html_label = $settings['label_html_wysiwyg'];

                    break;
            }
            $use_link = !empty($settings['use_link']) && !is_wp_error($this->current_permalink) ? true : false;
            if ($use_link) echo '<a href="'.$this->current_permalink.'">';
            echo $html_label;
            if ($use_link) echo '</a>';
        }
    }
    
}
