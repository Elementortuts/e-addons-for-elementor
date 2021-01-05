<?php
namespace EAddonsForElementor\Modules\Query\Skins\Traits;

use EAddonsForElementor\Core\Utils;
/**
 * Description of Common
 *
 * @author fra
 */
trait Label {

    protected function render_label_before_item($settings,$default_label = '') {

        $use_label = !empty($settings['use_label_before']) ? $settings['use_label_before'] : '';
        if ($use_label) {
            $label_text = !empty($settings['text_label_before']) ? $settings['text_label_before'] : '';

            $start_label = '<span class="e-add-label-before">';
            $end_label = '</span>';
            
            $the_label = '';
            if($default_label) $the_label = $default_label;
            if($label_text) $the_label = $label_text;

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
            $use_link = !empty($settings['use_link']) ? true : false;
            if ($use_link) echo '<a href="'.$this->current_permalink.'">';
            echo $html_label;
            if ($use_link) echo '</a>';
        }
    }
    
}
