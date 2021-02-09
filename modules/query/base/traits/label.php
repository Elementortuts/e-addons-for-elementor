<?php

namespace EAddonsForElementor\Modules\Query\Base\Traits;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

/**
 * Description of label
 *
 * @author fra
 */
trait Label {

    // -------------- Label Html ---------
    public function controls_items_label_content($target) {
        //
        // +********************* LabelHtml
        $target->add_control(
                'label_html_type', [
            'label' => __('Label Type', 'e-addons'),
            'type' => Controls_Manager::CHOOSE,
            'toggle' => false,
            'label_block' => false,
            'options' => [
                'text' => [
                    'title' => __('Text', 'e-addons'),
                    'icon' => 'fas fa-font',
                ],
                'image' => [
                    'title' => __('Image', 'e-addons'),
                    'icon' => 'fas fa-image',
                ],
                'icon' => [
                    'title' => __('Icon', 'e-addons'),
                    'icon' => 'fas fa-icons',
                ],
                'wysiwyg' => [
                    'title' => __('Wysiwyg', 'e-addons'),
                    'icon' => 'fas fa-align-justify',
                ],
                'code' => [
                    'title' => __('Code', 'e-addons'),
                    'icon' => 'fas fa-code',
                ],
            ],
            'default' => 'code',
            'conditions' => [
                'terms' => [
                    [
                        'name' => 'item_type',
                        'value' => 'item_label',
                    ]
                ]
            ]
                ]
        );

        $target->add_control(
                'label_html_image',
                [
                    'label' => __('Image', 'e-addons'),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => '',
                    ],
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'item_type',
                                'value' => 'item_label',
                            ],
                            [
                                'name' => 'label_html_type',
                                'value' => 'image',
                            ]
                        ]
                    ]
                ]
        );

        $target->add_group_control(
                Group_Control_Image_Size::get_type(), [
            'name' => 'label_html_image_size',
            'label' => __('Image Format', 'e-addons'),
            'default' => 'large',
            'condition' => [
                'item_type' => 'item_label',
                'label_html_type' => 'image',
                'label_html_image[url]!' => '',
            ]
                ]
        );
        $target->add_responsive_control(
                'label_html_image_width', [
            'label' => __('Image Width', 'e-addons'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['%', 'px'],
            'range' => [
                '%' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1
                ],
                'px' => [
                    'min' => 1,
                    'max' => 800,
                    'step' => 1
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} {{CURRENT_ITEM}}.e-add-item_label img' => 'width: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'item_type' => 'item_label',
                'label_html_type' => 'image',
                'label_html_image[url]!' => '',
            ]
                ]
        );
        $target->add_control(
                'label_html_icon',
                [
                    'label' => __('Icon', 'elementor'),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value' => '',
                        'library' => 'fa-solid',
                    ],
                    'skin' => 'inline',
                    'label_block' => false,
                    'fa4compatibility' => 'labelicon',
                    'condition' => [
                        'item_type' => 'item_label',
                        'label_html_type' => 'icon',
                    ]
                ]
        );

        $target->add_control(
                'label_html_text',
                [
                    'label' => 'Text Label',
                    'type' => Controls_Manager::TEXT,
                    'default' => '',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'item_type',
                                'value' => 'item_label',
                            ],
                            [
                                'name' => 'label_html_type',
                                'value' => 'text',
                            ]
                        ]
                    ]
                ]
        );

        $target->add_control(
                'label_html_code',
                [
                    'label' => 'Html Label',
                    'type' => Controls_Manager::CODE,
                    'default' => '',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'item_type',
                                'value' => 'item_label',
                            ],
                            [
                                'name' => 'label_html_type',
                                'value' => 'code',
                            ]
                        ]
                    ]
                ]
        );

        $target->add_control(
                'label_html_wysiwyg',
                [
                    'label' => __('Wysiwyg Label', 'elementor'),
                    'type' => Controls_Manager::WYSIWYG,
                    'default' => __('', 'elementor'),
                    'show_label' => false,
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'item_type',
                                'value' => 'item_label',
                            ],
                            [
                                'name' => 'label_html_type',
                                'value' => 'wysiwyg',
                            ]
                        ]
                    ]
                ]
        );
    }

}
