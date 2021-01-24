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
 * Description of infinite-scroll
 *
 * @author fra
 */
trait Infinite_Scroll {

    protected function register_style_infinitescroll_controls() {
        $this->start_controls_section(
                'section_style_infiniteScroll', [
            'label' => __('Infinite Scroll', 'e-addons'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'infiniteScroll_enable' => 'yes'
                //$this->get_control_id('infiniteScroll_enable') => 'yes', 
            ],
                ]
        );
        $this->add_responsive_control(
                'infiniteScroll_spacing', [
            'label' => __('Spacing status', 'e-addons'),
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
                '{{WRAPPER}} .infiniteScroll' => 'margin-top: {{SIZE}}{{UNIT}};'
            ],
                ]
        );
        $this->add_control(
                'infiniteScroll_heading_button_style', [
            'label' => __('Button', 'e-addons'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'infiniteScroll_trigger' => 'button',
                //$this->get_control_id('infiniteScroll_trigger') => 'button',
            ],
                ]
        );

        $this->add_responsive_control(
                'infiniteScroll_button_align', [
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
                '{{WRAPPER}} div.infiniteScroll' => 'justify-content: {{VALUE}};',
            ],
            'condition' => [
                'infiniteScroll_trigger' => 'button',
                //$this->get_control_id('infiniteScroll_trigger') => 'button',
            ],
                ]
        );
        $this->start_controls_tabs('infiniteScroll_button_colors');

        $this->start_controls_tab(
                'infiniteScroll_button_text_colors', [
            'label' => __('Normal', 'e-addons'),
            'condition' => [
                'infiniteScroll_trigger' => 'button',
                //$this->get_control_id('infiniteScroll_trigger') => 'button',
            ],
                ]
        );

        $this->add_control(
                'infiniteScroll_button_text_color', [
            'label' => __('Text Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .infiniteScroll button' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'infiniteScroll_trigger' => 'button',
                //$this->get_control_id('infiniteScroll_trigger') => 'button',
            ],
                ]
        );

        $this->add_control(
                'infiniteScroll_button_background_color', [
            'label' => __('Background Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .infiniteScroll button' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'infiniteScroll_trigger' => 'button',
                //$this->get_control_id('infiniteScroll_trigger') => 'button',
            ],
                ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
                'infiniteScroll_button_text_colors_hover', [
            'label' => __('Hover', 'e-addons'),
            'condition' => [
                'infiniteScroll_trigger' => 'button',
                //$this->get_control_id('infiniteScroll_trigger') => 'button',
            ],
                ]
        );
        $this->add_control(
                'infiniteScroll_button_hover_color', [
            'label' => __('Text Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .infiniteScroll button:hover' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'infiniteScroll_trigger' => 'button',
                //$this->get_control_id('infiniteScroll_trigger') => 'button',
            ],
                ]
        );
        $this->add_control(
                'infiniteScroll_button_background_hover_color', [
            'label' => __('Background Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .infiniteScroll button:hover' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'infiniteScroll_trigger' => 'button',
                //$this->get_control_id('infiniteScroll_trigger') => 'button',
            ],
                ]
        );
        $this->add_control(
                'infiniteScroll_button_hover_border_color', [
            'label' => __('Border Color', 'e-addons'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .infiniteScroll button:hover' => 'border-color: {{VALUE}};',
            ],
            'condition' => [
                'infiniteScroll_trigger' => 'button',
                //$this->get_control_id('infiniteScroll_trigger') => 'button',
            ],
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_control(
                'infiniteScroll_button_padding', [
            'label' => __('Padding', 'e-addons'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .infiniteScroll button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'infiniteScroll_trigger' => 'button',
                //$this->get_control_id('infiniteScroll_trigger') => 'button',
            ],
            'separator' => 'before'
                ]
        );
        $this->add_control(
                'infiniteScroll_button_radius', [
            'label' => __('Border Radius', 'e-addons'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .infiniteScroll button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'infiniteScroll_trigger' => 'button',
                //$this->get_control_id('infiniteScroll_trigger') => 'button',
            ],
                ]
        );

        $this->end_controls_section();
    }

}