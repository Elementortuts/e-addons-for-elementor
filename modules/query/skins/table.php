<?php

namespace EAddonsForElementor\Modules\Query\Skins;

use Elementor\Controls_Manager;
use EAddonsForElementor\Modules\Query\Skins\Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Table Skin
 *
 * Elementor widget query-posts for e-addons
 *
 */
class Table extends Base {

    public function _register_controls_actions() {

        parent::_register_controls_actions();
        add_action('elementor/element/' . $this->parent->get_name() . '/section_e_query/after_section_end', [$this, 'register_additional_controls'], 20);
        //add_action('elementor/element/' . $this->parent->get_name() . '/section_items/before_section_start', [$this, 'register_reveal_controls'], 20);
    }

    public function get_script_depends() {
        return ['datatables-jquery', 'datatables-jszip', 'datatables-buttons', 'datatables-html5', 'datatables-responsive', 'datatables-fixedHeader', 'e-addons-query-table'];
    }

    public function get_style_depends() {
        return ['datatables-jquery', 'datatables-buttons', 'datatables-responsive', 'datatables-fixedHeader'];
    }

    public function get_id() {
        return 'table';
    }

    public function get_title() {
        return __('Table', 'e-addons');
    }

    public function get_icon() {
        return 'eadd-skin-table';
    }

    public function register_additional_controls() {
        //var_dump($this->get_id());
        //var_dump($this->parent->get_settings('_skin')); //->get_current_skin()->get_id();

        $this->start_controls_section(
                'section_table', [
            'label' => '<i class="eaddicon eadd-queryviews-table"></i> ' . __('Table', 'e-addons'),
            'tab' => Controls_Manager::TAB_CONTENT,
                ]
        );
        
        $this->add_control(
                'datatables',
                [
                    'label' => __('Use DataTables', 'e-addons'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'frontend_available' => true,
                ]
        );
        
        
        $this->add_control(
                'searching',
                [
                    'label' => __('Searching', 'e-addons'),
                    'type' => Controls_Manager::SWITCHER,
                    'frontend_available' => true,
                    'condition' => [
                        $this->get_id().'_datatables!' => '',
                    ]
                ]
        );
        $this->add_control(
                'ordering',
                [
                    'label' => __('Ordering', 'e-addons'),
                    'type' => Controls_Manager::SWITCHER,
                    'frontend_available' => true,
                    'condition' => [
                        $this->get_id().'_datatables!' => '',
                    ]
                ]
        );
        $this->add_control(
                'info',
                [
                    'label' => __('Info', 'e-addons'),
                    'type' => Controls_Manager::SWITCHER,
                    'frontend_available' => true,
                    'condition' => [
                        $this->get_id().'_datatables!' => '',
                    ]
                ]
        );
        $this->add_control(
                'responsive',
                [
                    'label' => __('Responsive', 'e-addons'),
                    'type' => Controls_Manager::SWITCHER,
                    'frontend_available' => true,
                    'selectors' => [
                        '{{WRAPPER}} table' => 'max-width: 100%',
                    ],
                    'condition' => [
                        $this->get_id().'_datatables!' => '',
                    ]
                ]
        );
        $this->add_control(
                'buttons',
                [
                    'label' => __('Buttons', 'e-addons'),
                    'type' => Controls_Manager::SWITCHER,
                    'frontend_available' => true,
                    'condition' => [
                        $this->get_id().'_datatables!' => '',
                    ]
                ]
        );
        
        $this->add_control(
                'hide_header',
                [
                    'label' => __('Hide Header', 'e-addons'),
                    'type' => Controls_Manager::SWITCHER,
                ]
        );
        $this->add_control(
                'fixed_header',
                [
                    'label' => __('Fixed Header', 'e-addons'),
                    'type' => Controls_Manager::SWITCHER,
                    'frontend_available' => true,
                    'condition' => [
                        $this->get_id().'_datatables!' => '',
                        $this->get_id().'_hide_header' => '',
                    ]
                ]
        );
        
        
        $this->end_controls_section();
    }

    protected function register_style_controls() {
        parent::register_style_controls();

        $this->start_controls_section(
                'section_style_table',
                [
                    'label' => __('Table', 'e-addons'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_responsive_control(
                'border_spacing',
                [
                    'label' => '<i class="fas fa-arrows-alt-h"></i>&nbsp;' . __('Border spacing', 'e-addons'),
                    'type' => Controls_Manager::SLIDER,
                    /*'default' => [
                        'size' => 5,
                    ],*/
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        //'{{WRAPPER}} .e-add-posts-container' => 'column-gap: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} table' => 'border-spacing: {{SIZE}}{{UNIT}}; border-collapse: separate;',
                    ],
                ]
        );

        $this->end_controls_section();
    }

    protected function render_element_item() {
        
        $this->render_item_start();

            $this->render_items();
 
        $this->render_item_end();

        $this->counter++;
    }

    public function get_container_class() {
        return 'e-add-skin-' . $this->get_id();
    }
    
    public function get_item_label($item) {
        $label = $item['item_label'];
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

    protected function render_loop_start() {
        echo '<table>';

        $hide_header = $this->parent->get_settings($this->get_id().'_hide_header');
        if (!$hide_header) {
            echo '<thead><tr>';
            $_items = $this->parent->get_settings_for_display('list_items');
            // ITEMS ///////////////////////
            foreach ($_items as $item) {
                $label = $this->get_item_label($item);
                echo '<th>'.$label.'</th>';
            }
            echo '</tr></thead>';
        }
        
        echo '<tbody>';
    }
    protected function render_loop_end() {
        echo '</tbody></table>';
    }
    public function render_item_start($key = 'post') {
        echo '<tr>';
    }
    public function render_item_end() {
        echo '</tr>';
    }
    /*protected function render_items() {
        $_skin = $this->parent->get_settings_for_display('_skin');
        $this->render_items_content();     
    }*/
    protected function render_repeateritem_start($id, $item_type) {
        echo '<td>';        
    }
    protected function render_repeateritem_end() {
        echo '</td>';
    }
}
