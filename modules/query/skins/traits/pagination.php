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
 * Description of Pagination
 *
 * @author fra
 */
trait Pagination {

    // ------------------------------------------------------------------ [SECTION STYLE PAGINATION]
    // @p questa è la parte di style relativa alla paginazione
    protected function register_style_pagination_controls() {
        $this->start_controls_section(
                'section_style_pagination', [
            'label' => __('Pagination', 'e-addons'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'pagination_enable' => 'yes',
                'infiniteScroll_enable' => ''
            //$this->get_control_id('pagination_enable') => 'yes', 
            //$this->get_control_id('infiniteScroll_enable') => '',
            ],
                ]
        );
        $this->add_control(
                'pagination_heading_style', [
            'label' => __('Pagination', 'e-addons'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );
        $this->add_responsive_control(
                'pagination_align', [
            'label' => __('Alignment', 'e-addons'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'options' => [
                'flex-start' => [
                    'title' => __('Left', 'e-addons'),
                    'icon' => 'eicon-h-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'e-addons'),
                    'icon' => 'eicon-h-align-center',
                ],
                'flex-end' => [
                    'title' => __('Right', 'e-addons'),
                    'icon' => 'eicon-h-align-right',
                ],
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination' => 'justify-content: {{VALUE}};',
            ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'pagination_typography',
            'label' => __('Typography', 'e-addons'),
            'selector' => '{{WRAPPER}} .e-add-pagination',
                ]
        );
        $this->add_responsive_control(
                'pagination_space', [
            'label' => __('Space', 'e-addons'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 10,
            ],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination' => 'padding-top: {{SIZE}}{{UNIT}};'
            ],
                ]
        );
        $this->add_responsive_control(
                'pagination_spacing', [
            'label' => __('Horizontal Spacing', 'e-addons'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 1,
            ],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination span, {{WRAPPER}} .e-add-pagination a' => 'margin-right: {{SIZE}}{{UNIT}};'
            ],
                ]
        );
        $this->add_control(
                'pagination_padding', [
            'label' => __('Padding', 'e-addons'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination span, {{WRAPPER}} .e-add-pagination a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'pagination_radius', [
            'label' => __('Border Radius', 'e-addons'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination span, {{WRAPPER}} .e-add-pagination a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_control(
                'pagination_heading_colors', [
            'label' => __('Colors', 'e-addons'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );

        $this->start_controls_tabs('pagination_colors');

        $this->start_controls_tab(
                'pagination_text_colors', [
            'label' => __('Normal', 'e-addons'),
                ]
        );

        $this->add_control(
                'pagination_text_color', [
            'label' => __('Text Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination span, {{WRAPPER}} .e-add-pagination a' => 'color: {{VALUE}};',
            ],
                ]
        );

        $this->add_control(
                'pagination_background_color', [
            'label' => __('Background Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination span, {{WRAPPER}} .e-add-pagination a' => 'background-color: {{VALUE}};',
            ],
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'pagination_border',
            'label' => __('Border', 'e-addons'),
            'selector' => '{{WRAPPER}} .e-add-pagination span, {{WRAPPER}} .e-add-pagination a',
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'pagination_text_colors_hover', [
            'label' => __('Hover', 'e-addons'),
                ]
        );
        $this->add_control(
                'pagination_hover_color', [
            'label' => __('Text Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination a:hover' => 'color: {{VALUE}};',
            ],
                ]
        );
        $this->add_control(
                'pagination_background_hover_color', [
            'label' => __('Background Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination a:hover' => 'background-color: {{VALUE}};',
            ]
                ]
        );
        $this->add_control(
                'pagination_hover_border_color', [
            'label' => __('Border Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'condition' => [
                $this->get_control_id('pagination_border_border!') => '',
            ],
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination a:hover' => 'border-color: {{VALUE}};',
            ],
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'pagination_text_colors_current', [
            'label' => __('Current', 'e-addons'),
                ]
        );
        $this->add_control(
                'pagination_current_color', [
            'label' => __('Text Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination span.current' => 'color: {{VALUE}};',
            ],
                ]
        );
        $this->add_control(
                'pagination_background_current_color', [
            'label' => __('Background Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination span.current' => 'background-color: {{VALUE}};',
            ]
                ]
        );
        /* $this->add_control(
          'pagination_current_border_color', [
          'label' => __('Border Color', 'e-addons'),
          'type' => Controls_Manager::COLOR,
          'condition' => [
          'pagination_border_border!' => '',
          ],
          'selectors' => [
          '{{WRAPPER}} .e-add-pagination span.current' => 'border-color: {{VALUE}};',
          ],
          ]
          ); */

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // PrevNext

        $this->add_control(
                'pagination_heading_prevnext', [
            'label' => __('Prev/Next', 'e-addons'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );
        $this->add_responsive_control(
                'pagination_spacing_prevnext', [
            'label' => __('Spacing PrevNext', 'e-addons'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .pageprev' => 'margin-right: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .e-add-pagination .pagenext' => 'margin-left: {{SIZE}}{{UNIT}};'
            ],
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );


        $this->add_control(
                'pagination_heading_icons_prevnext', [
            'label' => __('Icons', 'e-addons'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );
        $this->add_responsive_control(
                'pagination_icon_spacing_prevnext', [
            'label' => __('Icon Spacing', 'e-addons'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'max' => 50,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .pageprev i' => 'margin-right: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .e-add-pagination .pagenext i' => 'margin-left: {{SIZE}}{{UNIT}};'
            ],
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );
        $this->add_responsive_control(
                'pagination_icon_size_prevnext', [
            'label' => __('Icon Size', 'e-addons'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .pageprev i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .e-add-pagination .pagenext i' => 'font-size: {{SIZE}}{{UNIT}};'
            ],
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );

        $this->start_controls_tabs('pagination_prevnext_colors');

        $this->start_controls_tab(
                'pagination_prevnext_text_colors', [
            'label' => __('Normal', 'e-addons'),
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );

        $this->add_control(
                'pagination_prevnext_text_color', [
            'label' => __('Text Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .pageprev, {{WRAPPER}} .e-add-pagination .pagenext' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );

        $this->add_control(
                'pagination_prevnext_background_color', [
            'label' => __('Background Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .pageprev, {{WRAPPER}} .e-add-pagination .pagenext' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'pagination_prevnext_border',
            'label' => __('Border', 'e-addons'),
            'selector' => '{{WRAPPER}} .e-add-pagination .pageprev, {{WRAPPER}} .e-add-pagination .pagenext',
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_prevnext_radius', [
            'label' => __('Border Radius', 'e-addons'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .pageprev, {{WRAPPER}} .e-add-pagination .pagenext' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
                'pagination_prevnext_text_colors_hover', [
            'label' => __('Hover', 'e-addons'),
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_prevnext_hover_color', [
            'label' => __('Text Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .pageprev:hover, {{WRAPPER}} .e-add-pagination .pagenext:hover' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_prevnext_background_hover_color', [
            'label' => __('Background Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .pageprev:hover, {{WRAPPER}} .e-add-pagination .pagenext:hover' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_prevnext' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_prevnext_hover_border_color', [
            'label' => __('Border Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .pageprev:hover, {{WRAPPER}} .e-add-pagination .pagenext:hover' => 'border-color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_prevnext' => 'yes',
                'pagination_prevnext_border_border!' => '',
            ]
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // FirstLast

        $this->add_control(
                'pagination_heading_firstlast', [
            'label' => __('First/last', 'e-addons'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );
        $this->add_responsive_control(
                'pagination_spacing_firstlast', [
            'label' => __('Spacing', 'e-addons'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .pagefirst' => 'margin-right: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .e-add-pagination .pagelast' => 'margin-left: {{SIZE}}{{UNIT}};'
            ],
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );
        $this->start_controls_tabs('pagination_firstlast_colors');

        $this->start_controls_tab(
                'pagination_firstlast_text_colors', [
            'label' => __('Normal', 'e-addons'),
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );

        $this->add_control(
                'pagination_firstlast_text_color', [
            'label' => __('Text Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .pagefirst, {{WRAPPER}} .e-add-pagination .pagelast' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );

        $this->add_control(
                'pagination_firstlast_background_color', [
            'label' => __('Background Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .pagefirst, {{WRAPPER}} .e-add-pagination .pagelast' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'pagination_firstlast_border',
            'label' => __('Border', 'e-addons'),
            'selector' => '{{WRAPPER}} .e-add-pagination .pagefirst, {{WRAPPER}} .e-add-pagination .pagelast',
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_firstlast_radius', [
            'label' => __('Border Radius', 'e-addons'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .pagefirst, {{WRAPPER}} .e-add-pagination .pagelast' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
                'pagination_firstlast_text_colors_hover', [
            'label' => __('Hover', 'e-addons'),
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_firstlast_hover_color', [
            'label' => __('Text Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .pagefirst:hover, {{WRAPPER}} .e-add-pagination .pagelast:hover' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_firstlast_background_hover_color', [
            'label' => __('Background Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .pagefirst:hover, {{WRAPPER}} .e-add-pagination .pagelast:hover' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_firstlast' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_firstlast_hover_border_color', [
            'label' => __('Border Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .pagefirst:hover, {{WRAPPER}} .e-add-pagination .pagelast:hover' => 'border-color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_firstlast' => 'yes',
                'pagination_firstlast_border_border!' => '',
            ]
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Progression
        $this->add_control(
                'pagination_heading_progression', [
            'label' => __('Progression', 'e-addons'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );
        $this->add_responsive_control(
                'pagination_spacing_progression', [
            'label' => __('Spacing', 'e-addons'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'max' => 100,
                    'min' => 0,
                    'step' => 1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .progression' => 'margin-right: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );
        $this->start_controls_tabs('pagination_progression_colors');

        $this->start_controls_tab(
                'pagination_progression_text_colors', [
            'label' => __('Normal', 'e-addons'),
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );

        $this->add_control(
                'pagination_progression_text_color', [
            'label' => __('Text Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .progression' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );

        $this->add_control(
                'pagination_progression_background_color', [
            'label' => __('Background Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .progression' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'pagination_progression_border',
            'label' => __('Border', 'e-addons'),
            'selector' => '{{WRAPPER}} .e-add-pagination .progression',
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_progression_radius', [
            'label' => __('Border Radius', 'e-addons'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .progression' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
                'pagination_progression_text_colors_hover', [
            'label' => __('Hover', 'e-addons'),
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_progression_hover_color', [
            'label' => __('Text Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .progression' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_progression_background_hover_color', [
            'label' => __('Background Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .progression' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_progression' => 'yes',
            ]
                ]
        );
        $this->add_control(
                'pagination_progression_hover_border_color', [
            'label' => __('Border Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .e-add-pagination .progression' => 'border-color: {{VALUE}};',
            ],
            'condition' => [
                'pagination_show_progression' => 'yes',
                'pagination_firstlast_border_border!' => '',
            ]
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_section();
    }

}
