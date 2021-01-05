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
 * Description of Item
 *
 * @author fra
 */
trait Item {
    
    protected function render_item_subtitle($settings) {
        if($this->current_data['sl_subtitle'])
        echo $this->current_data['sl_subtitle'];
    }

    protected function render_item_descriptiontext($settings) {
        if($this->current_data['sl_descriptiontext'])
        echo $this->current_data['sl_descriptiontext'];
    }

}
