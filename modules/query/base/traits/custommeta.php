<?php
namespace EAddonsForElementor\Modules\Query\Base\Traits;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Background;

use EAddonsForElementor\Core\Utils\Query as Query_Utils;


/**
 * Description of custommmeta
 *
 * @author fra
 */
trait Custommeta {
    
    // -------------- Custommeta SOURCE query_type Posts/Users/Terms ---------
    //@p leggo un campo personalizzato di tipo relationship oppure users oppure terms nel post, user o termine in cuii mi trovo
    public function custommeta_source_items( $target, $type_q = 'post') {
        
        $target->add_control(
            'avviso_meta_custommeta_source',
            [
                'type' => Controls_Manager::RAW_HTML,
                'show_label' => false,
                'raw' => '<i class="fas fa-exclamation-circle"></i> '.__('Select the post custom meta field of type '.$type_q,'e-addons'),
                'content_classes' => 'e-add-info-panel',
                'condition' => [
                    'query_type' => 'custommeta_source',
                ],
            ]
        );
        // @p custommeta_source from meta field ..non più tramite acf
        $target->add_control(
            'custommeta_source_key', [
                'label' => __('Custommeta Field Key', 'e-addons'),
                'type'      => 'e-query',
                'placeholder'   => __( 'Meta key custommeta_source', 'e-addons' ),
                'label_block'   => true,
                'query_type' => 'metas',
                'object_type' => 'post', //'term',//'user',//'post', //$type_q,,
                'default' => '',
                'condition' => [
                    'query_type' => 'custommeta_source',
                ],
            ]
        );
        /*
        $target->add_control(
            'custommeta_source_key', [
                'label' => __('Custommeta Field Key', 'e-addons'),
                'type'      => 'e-query',
                'placeholder'   => __( 'Meta key custommeta_source', 'e-addons' ),
                'label_block'   => true,
                'query_type' => 'options',
                'default' => '',
                'condition' => [
                    'query_type' => 'custommeta_source',
                ],
            ]
        );
        */
    }

    // -------------- Custom Fields for Posts/Users/Terms ---------
    public function custommeta_items( $target, $type_q = 'post') {

        //Key
        $target->add_control(
            'metafield_key', [
                'label' => __('META Field', 'e-addons'),
                'type'      => 'e-query',
                'placeholder'   => __( 'Meta key or Name', 'e-addons' ),
                'label_block'   => true,
                
                //$type_q
                //'query_type' => 'posts', 
                //'object_type' => 'elementor_library',

                //'query_type' => 'users',
                //'object_type' => 'role',

                'query_type' => 'metas', //'fields',
                'object_type' => $type_q,

                //'query_type' => 'fields',
                //'object_type' => 'term',

                //'query_type' => 'fields',
                //'object_type' => 'post',

                //--------
                //'query_type' => 'post',
                //'object_type' => 'meta',


                //'query_type' => 'terms',
                //'object_type' => 'tags',

                //'query_type' => 'taxonomies',

                //'query_type'    => 'metas',
                //'object_type'   => $type_mf,


                'default' => '',
                'dynamic' => [
                    'active' => false,
                ],
                'condition' => [
                    'item_type' => 'item_custommeta'
                ]
            ]
        );
        //Type
        $target->add_control(
            'metafield_type', [
                'label' => __('Render Field type', 'e-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'separator' => 'after',
                'options' => [
                    '' => __('None', 'e-addons'), //
                    'image' => __('Image', 'e-addons'),
                    'oembed' => __('oEmbed', 'e-addons'), //
                    'date' => __('Date', 'e-addons'), //
                    'text' => __('Text', 'e-addons'),
                    'textarea' => __('Textarea', 'e-addons'), //
                    'button' => __('Button(url)', 'e-addons'), //
                    'map' => __('Map(address)', 'e-addons'), //
                    'file' => __('File(media-id)', 'e-addons'), //
                    'post' => __('Post(id)', 'e-addons'), //
                    'user' => __('Users(id)', 'e-addons'), //
                    'term' => __('Terms(id)', 'e-addons'), //
                    'gallery' => __('Gallery', 'e-addons'), //
                    'array' => __('Array', 'e-addons'), //
                ],
                'condition' => [
                    'item_type' => 'item_custommeta'
                ]
            ]
        );
        //...'metafield_type!' => ['','textarea','date','button','file','oembed','map','term','post','user','gallery','array'],
        //
        //Array
        $target->add_control(
            'array_dump', [
                'label' => __('Show dump array', 'e-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'metafield_type' => 'array',
                ]
            ]
        );
        $target->add_control(
            'array_index', [
                'label' => __('Indexes of array', 'e-addons'),
                'description' => __('write the string of logic array with a dot for separatior (example: 0.0 or 1.val ecc). Empty it\'s all', 'e-addons'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => 'example: 1.val',

                'default' => '',
                'condition' => [
                    'metafield_type' => 'array',
                ]
            ]
        );
        //Image
        $target->add_group_control(
            Group_Control_Image_Size::get_type(), [
                'name' => 'metafield_image_size',
                'label' => __('Image Format', 'e-addons'),
                'default' => 'large',
                'condition' => [
                    'metafield_type' => 'image',
                ]
            ]
        );
        $target->add_responsive_control(
            'metafield_image_width', [
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
                    '{{WRAPPER}} {{CURRENT_ITEM}}.e-add-item_custommeta img' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'metafield_type' => 'image',
                ]
            ]
        );
        // list of post-users-terms
        /*$target->add_control(
            'metafield_gallery_type', [
                'label' => __('Gallery type', 'e-addons'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'grid' => [
                        'title' => __('Grid', 'e-addons'),
                        'icon' => 'fas fa-ellipsis-h',
                    ],
                    'carousel' => [
                        'title' => __('Carousel', 'e-addons'),
                        'icon' => 'fas fa-ellipsis-v',
                    ]
                ],
                'default' => 'grid',
                'condition' => [
                    'metafield_type' => 'gallery'
                ]
            ]
        );*/
        //Date
        $target->add_control(
            'metafield_date_format_source', [
                'label' => __('Date Format: SOURCE', 'e-addons'),
                'description' => '<a target="_blank" href="https://www.php.net/manual/en/function.date.php">' . __('Use standard PHP format character') . '</a>' . __(', you can also use "timestamp"'),
                'type' => Controls_Manager::TEXT,
                'default' => 'F j, Y, g:i a',
                'placeholder' => __('YmdHis, d/m/Y, m-d-y', 'e-addons'),
                'condition' => [
                    'metafield_type' => 'date',
                ]
            ]
        );
        $target->add_control(
            'metafield_date_format_display', [
                'label' => __('Date Format: DISPLAY', 'e-addons'),
                'placeholder' => __('YmdHis, d/m/Y, m-d-y', 'e-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => 'F j, Y, g:i a',
                'condition' => [
                    'metafield_type' => 'date',
                ]
            ]
        );
        // button
        $target->add_control(
            'metafield_button_label', [
                'label' => __('Label', 'e-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Click me',
                'condition' => [
                    'metafield_type' => 'button',
                ]
            ]
        );
        $target->add_control(
            'metafield_button_size',
            [
                'label' => __('Size', 'elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'sm',
                'options' => Query_Utils::get_button_sizes(),
                'style_transfer' => true,
                'condition' => [
                    'metafield_type' => 'button',
                ]
            ]
        );
        //Text
        $target->add_control(
            'html_tag_item', [
                'label' => __('HTML Tag', 'e-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => __('H1', 'e-addons'),
                    'h2' => __('H2', 'e-addons'),
                    'h3' => __('H3', 'e-addons'),
                    'h4' => __('H4', 'e-addons'),
                    'h5' => __('H5', 'e-addons'),
                    'h6' => __('H6', 'e-addons'),
                    'p' => __('p', 'e-addons'),
                    'div' => __('div', 'e-addons'),
                    'span' => __('span', 'e-addons'),
                ],
                'condition' => [
                    'metafield_type' => 'text',
                ],
                'default' => 'span',
            ]
        );
        //Terms
        $target->add_control(
            'metafield_term_count', [
                'label' => __('Show count', 'e-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'metafield_type' => 'term'
                ]
            ]
        );
        $target->add_control(
            'metafield_term_hideempty', [
                'label' => __('Hide Empty' , 'e-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => [
                    'metafield_type' => 'term'
                ]
            ]
        );
        //Users


        //Posts
        $target->add_control(
            'metafield_post_image', [
                'label' => __('Show image' , 'e-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => [
                    'metafield_type' => 'post'
                ]
            ]
        );

        // list of post-users-terms
        $target->add_control(
            'metafield_list_direction', [
                'label' => __('Direction', 'e-addons'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'horizontal' => [
                        'title' => __('Horizontal', 'e-addons'),
                        'icon' => 'fas fa-ellipsis-h',
                    ],
                    'vertical' => [
                        'title' => __('Vertical', 'e-addons'),
                        'icon' => 'fas fa-ellipsis-v',
                    ]
                ],
                'default' => 'horizontal',
                'condition' => [
                    'metafield_type' => ['user','term']
                ]
            ]
        );
        $target->add_control(
            'metafield_list_separator', [
                'label' => __('Separator', 'e-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => ',',
                'condition' => [
                    'metafield_type' => ['user','term'],
                    'metafield_term_style' => 'horizontal'
                ]
            ]
        );
        
        //File
        $target->add_control(
            'metafield_file_label', [
                'label' => __('Label', 'e-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => 'File',
                'condition' => [
                    'metafield_type' => 'file',
                ]
            ]
        );
        //l'icona vale per text, button o file
        $target->add_control(
			'show_icon', 
			[
				'label' => __( 'Icon', 'elementor' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => '',
					'library' => 'fa-solid',
                ],
                'skin' => 'inline',
                'label_block' => false,
                
                'fa4compatibility' => 'icon',
                'condition' => [
                    'metafield_type' => ['button','file','text'],
                ]
			]
        );
        //The Link... 
        // @p abilito il link se loo voglio x i custommmeta di tipo users, terms, posts
        $target->add_control(
            'metafield_list_link', [
                'label' => __('Link', 'e-addons'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'metafield_type' => ['user','term','post']
                ]
            ]
        );
        // @p in caso di immagine posso decidere che il link è:
        // 1 - al post (naturale)
        // 2 - custom (ad altro)
        $target->add_control(
            'link_to', [
                'label' => __('Link to', 'e-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'separator' => 'before',
                'options' => [
                    '' => __('None', 'e-addons'),
                    'post' => strtoupper($type_q).' URL',
                    'custom' => __('Custom URL', 'e-addons'),
                ],
                'condition' => [
                        //'','textarea','date','button','file','oembed','map','term','post','user','gallery'
                        // 
                        'metafield_type' => ['image', 'text'],
                    ]
            ]
        );
        $target->add_control(
            'link', [
                'label' => __('Link', 'e-addons'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('http://your-link.com', 'e-addons'),
                'condition' => [
                    'metafield_type' => ['image', 'text'],
                    'link_to' => 'custom',
                ],
                'dynamic' => [
                    'active' => true
                ],
                'default' => [
                    'url' => '',
                ],
                'show_label' => false,
            ]
        );

    }
}
