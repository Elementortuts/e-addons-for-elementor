<?php

namespace EAddonsForElementor\Modules\Query\Skins\Traits;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Background;

/**
 * Description of term
 *
 * @author fra
 */
trait Term {

    // TERMS
    protected function render_item_postscount($settings) {
        //
        $term_info_count = $this->current_data['count'];
        if( !empty($term_info_count) ){
            
            echo $this->render_label_before_item($settinngs,'Posts: ');
            //
            echo $term_info->count;
        }
    }

    protected function render_item_taxonomy($settings) {
        $term_info = $this->current_data;
        $taxonomy_label = $settings['taxonomy_label']; //plural - singular
        //var_dump($taxObj);
        switch ($taxonomy_label) {
            case 'plural' :
                $tax = get_taxonomy($term_info->taxonomy)->labels->name;
                break;
            case 'singular' :
            default:
                $tax = get_taxonomy($term_info->taxonomy)->labels->singular_name;
                break;
        }
        echo $tax;
    }

    protected function render_item_description($settings) {
        $textcontent_limit = $settings['textcontent_limit'];
        $term_info = $this->current_data;
        $description_content = $term_info->description;

        if ($description_content)
            if ($textcontent_limit) {
                echo substr(wp_strip_all_tags($description_content), 0, $textcontent_limit) . ' ...';
            } else {
                echo $description_content;
            }
    }

}
