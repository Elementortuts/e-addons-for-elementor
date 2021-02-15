<?php

namespace EAddonsForElementor\Modules\Query\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Background;
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
            'label' => '<i class="eaddicon eadd-skin-table"></i> ' . __('Table', 'e-addons'),
            'tab' => Controls_Manager::TAB_CONTENT,
                ]
        );

        $this->add_control(
                'datatables',
                [
                    'label' => __('Use DataTables', 'e-addons'),
                    'type' => Controls_Manager::SWITCHER,
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
                        $this->get_id() . '_datatables!' => '',
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
                        $this->get_id() . '_datatables!' => '',
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
                        $this->get_id() . '_datatables!' => '',
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
                        $this->get_id() . '_datatables!' => '',
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
                        $this->get_id() . '_datatables!' => '',
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
                        $this->get_id() . '_datatables!' => '',
                        $this->get_id() . '_hide_header' => '',
                    ]
                ]
        );


        $this->end_controls_section();
    }

    public function add_common_controls($selector = 'table', $selector_id = 'table', $conditions = array()) {

        $selector_cell = '{{WRAPPER}} ' . $selector . '>td, {{WRAPPER}} ' . $selector . '>th';
        if (strpos($selector, 'td') !== false || strpos($selector, 'th') !== false) {
            $selector_cell = '{{WRAPPER}} ' . $selector;
        }
        if (strpos($selector_id, 'datatables') !== false) {
            $selector_cell = '{{WRAPPER}} ' . $selector;
            $this->add_responsive_control(
                    $selector_id . '_margin', [
                'label' => __('Margin', 'e-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    $selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => $conditions,
                    ]
            );
        }
        $this->add_responsive_control(
                $selector_id . '_padding', [
            'label' => __('Padding', 'e-addons'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                $selector_cell => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                'condition' => $conditions,
                ]
        );
        $this->add_responsive_control(
                $selector_id . '_align', [
            'label' => __('Text Alignment', 'e-addons'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => true,
            'options' => [
                'left' => [
                    'title' => __('Left', 'e-addons'),
                    'icon' => 'fa fa-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'e-addons'),
                    'icon' => 'fa fa-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'e-addons'),
                    'icon' => 'fa fa-align-right',
                ]
            ],
            'default' => 'left',
            'prefix_class' => 'e-add-align%s-',
            'selectors' => [
                '{{WRAPPER}} ' . $selector => 'text-align: {{VALUE}};',
            ],
                'condition' => $conditions,
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => $selector_id . '_typography',
            'label' => __('Typography', 'e-addons'),
            'selector' => '{{WRAPPER}} ' . $selector,
                'condition' => $conditions,
                ]
        );
        $this->add_control(
                $selector_id . '_color', [
            'label' => __('Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} ' . $selector => 'color: {{VALUE}};'
            ],
                'condition' => $conditions,
                ]
        );
        $this->add_control(
                $selector_id . '_bgcolor', [
            'label' => __('Background Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} ' . $selector => 'background-color: {{VALUE}};'
            ],
                'condition' => $conditions,
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => $selector_id . '_border',
            'selector' => $selector_cell,
                'condition' => $conditions,
                ]
        );
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

        $this->add_control(
                'table_reset_style', [
            'label' => __('Reset Theme style', 'e-addons'),
            'type' => Controls_Manager::SWITCHER,
            'selectors' => [
                '{{WRAPPER}} table' => 'margin-bottom:0;font-size:1;',
                '{{WRAPPER}} table td, {{WRAPPER}} table th' => 'padding:0;line-height:1;border: none;',
                '{{WRAPPER}} table th' => 'font-weight: initial;',
                '{{WRAPPER}} table tbody>tr:nth-child(odd)>td, {{WRAPPER}} table tbody>tr:nth-child(odd)>th' => 'background: none;',
            /* table caption+thead tr:first-child td,
              table caption+thead tr:first-child th,
              table colgroup+thead tr:first-child td,
              table colgroup+thead tr:first-child th,
              table thead:first-child tr:first-child td,
              table thead:first-child tr:first-child th {
              border-top:1px solid #ccc
              }
              table tbody+tbody {
              border-top:2px solid #ccc
              }

              @media (max-width:767px) {
              table table {
              font-size:.8em
              }
              table table td,
              table table th {
              padding:7px;
              line-height:1.3
              }
              table table th {
              font-weight:400
              }
              }
             * 
             */
            ],
                ]
        );

        $this->add_control(
                'table_heading', [
            'type' => Controls_Manager::RAW_HTML,
            'show_label' => false,
            'raw' => '<i class="fas fa-table"></i> <b>' . __('Table', 'e-addons') . '</b>',
            'content_classes' => 'e-add-inner-heading',
            'separator' => 'before',
                ]
        );
        $this->add_common_controls();

        $this->add_control(
                'table_border_radius', [
            'label' => __('Border Radius', 'e-addons'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );

        $this->add_responsive_control(
                'border_spacing',
                [
                    'label' => '<i class="fas fa-arrows-alt-h"></i>&nbsp;' . __('Border spacing', 'e-addons'),
                    'type' => Controls_Manager::SLIDER,
                    /* 'default' => [
                      'size' => 5,
                      ], */
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

        // ROW
        // normal
        // even
        // odd
        // hover

        $this->add_control(
                'rows_heading', [
            'type' => Controls_Manager::RAW_HTML,
            'show_label' => false,
            'raw' => '<i class="fas fa-bars"></i> <b>' . __('Rows', 'e-addons') . '</b>',
            'content_classes' => 'e-add-inner-heading',
            'separator' => 'before',
                ]
        );

        $selector = 'table>tbody>tr';
        $selector_id = 'table_tr_style';
        $this->start_controls_tabs($selector_id);

        $this->start_controls_tab(
                $selector_id . '_normal', [
            'label' => __('Normal', 'e-addons'),
                ]
        );
        $this->add_common_controls($selector, $selector_id . '_normal');
        $this->end_controls_tab();

        $this->start_controls_tab(
                $selector_id . '_even', [
            'label' => __('Even', 'e-addons'),
                ]
        );
        $this->add_common_controls($selector . ':nth-child(even)>td', $selector_id . '_even');
        $this->end_controls_tab();

        $this->start_controls_tab(
                $selector_id . '_odd', [
            'label' => __('Odd', 'e-addons'),
                ]
        );
        $this->add_common_controls($selector . ':nth-child(odd)>td', $selector_id . '_odd');
        $this->end_controls_tab();

        $this->start_controls_tab(
                $selector_id . '_hover', [
            'label' => __('Hover', 'e-addons'),
                ]
        );
        $this->add_common_controls($selector . ':hover>td', $selector_id . '_hover');
        $this->end_controls_tab();

        $this->end_controls_tabs();

        // TD
        // normal
        // even
        // odd

        $this->add_control(
                'cols_heading', [
            'type' => Controls_Manager::RAW_HTML,
            'show_label' => false,
            'raw' => '<i class="fas fa-columns"></i> <b>' . __('Cells', 'e-addons') . '</b>',
            'content_classes' => 'e-add-inner-heading',
            'separator' => 'before',
                ]
        );

        $selector = 'table>tbody>tr>td';
        $selector_id = 'table_td_style';
        $this->start_controls_tabs($selector_id);

        $this->start_controls_tab(
                $selector_id . '_normal', [
            'label' => __('Normal', 'e-addons'),
                ]
        );
        $this->add_common_controls($selector, $selector_id . '_normal');
        $this->end_controls_tab();

        $this->start_controls_tab(
                $selector_id . '_even', [
            'label' => __('Even', 'e-addons'),
                ]
        );
        $this->add_common_controls($selector . ':nth-child(even)', $selector_id . '_even');
        $this->end_controls_tab();

        $this->start_controls_tab(
                $selector_id . '_odd', [
            'label' => __('Odd', 'e-addons'),
                ]
        );
        $this->add_common_controls($selector . ':nth-child(odd)', $selector_id . '_odd');
        $this->end_controls_tab();

        $this->end_controls_tabs();

        // TH
        // normal
        // even
        // odd

        $this->add_control(
                'heads_heading', [
            'type' => Controls_Manager::RAW_HTML,
            'show_label' => false,
            'raw' => '<i class="fas fa-header"></i> <b>' . __('Head', 'e-addons') . '</b>',
            'content_classes' => 'e-add-inner-heading',
            'separator' => 'before',
                ]
        );

        $selector = 'table>thead>tr>th, table.fixedHeader-floating>thead>tr>th';
        $selector_id = 'table_th_style';
        $this->add_common_controls($selector, $selector_id . '_normal');

        $conditions_datatables = array($this->get_id().'_datatables!' => '');
        $this->add_control(
                'datatables_heading', [
            'type' => Controls_Manager::RAW_HTML,
            'show_label' => false,
            'raw' => '<i class="fas fa-header"></i> <b>' . __('Datatables', 'e-addons') . '</b>',
            'content_classes' => 'e-add-inner-heading',
            'separator' => 'before',
            'condition' => $conditions_datatables,
                ]
        );
        
        $conditions_datatables_info = $conditions_datatables;
        $conditions_datatables_info[$this->get_id().'_info!'] = '';
        $this->add_control(
                'datatables_info_heading', [
            'type' => Controls_Manager::HEADING,
            'label' =>  __('Info', 'e-addons'),
            'condition' => $conditions_datatables_info,
                ]
        );
        $selector_id = 'datatables_info';
        $selector = '.dataTables_wrapper .dataTables_info';
        $this->add_common_controls($selector, $selector_id, $conditions_datatables_info);
        
        $conditions_datatables_filter = $conditions_datatables;
        $conditions_datatables_filter[$this->get_id().'_searching!'] = '';
        $this->add_control(
                'datatables_filter_heading', [
            'type' => Controls_Manager::HEADING,
            'label' =>  __('Search', 'e-addons'),
            'condition' => $conditions_datatables_filter,
                ]
        );
        $selector_id = 'datatables_filter';
        $selector = '.dataTables_wrapper .dataTables_filter input';
        $this->add_common_controls($selector, $selector_id, $conditions_datatables_filter);
        
        $conditions_datatables_buttons = $conditions_datatables;
        $conditions_datatables_buttons[$this->get_id().'_buttons!'] = '';
        $this->add_control(
                'datatables_buttons_heading', [
            'type' => Controls_Manager::HEADING,
            'label' =>  __('Buttons', 'e-addons'),
            'condition' => $conditions_datatables_buttons,
                ]
        );
        $selector_id = 'datatables_buttons';
        $selector = '.dataTables_wrapper .dt-buttons button';
        $this->add_common_controls($selector, $selector_id, $conditions_datatables_buttons);


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

    protected function render_loop_start() {
        echo '<table class="e-table ' . $this->get_container_class() . '">';

        $hide_header = $this->parent->get_settings($this->get_id() . '_hide_header');
        if (!$hide_header) {
            echo '<thead><tr>';
            $_items = $this->parent->get_settings_for_display('list_items');
            // ITEMS ///////////////////////
            foreach ($_items as $item) {
                $label = $this->get_item_label($item);
                echo '<th>' . $label . '</th>';
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

    /* protected function render_items() {
      $_skin = $this->parent->get_settings_for_display('_skin');
      $this->render_items_content();
      } */

    protected function render_repeateritem_start($id, $item_type) {
        $classItem = 'class="e-add-item e-add-' . $item_type . ' elementor-repeater-item-' . $id . '"';
        $dataIdItem = ' data-item-id="' . $id . '"';
        echo '<td ' . $classItem . $dataIdItem . '>';
    }

    protected function render_repeateritem_end() {
        echo '</td>';
    }

}
