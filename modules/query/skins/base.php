<?php

namespace EAddonsForElementor\Modules\Query\Skins;

use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Background;
use EAddonsForElementor\Core\Utils;
use EAddonsForElementor\Base\Base_Skin;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Base Skin
 *
 * Elementor widget query-posts for e-addons
 *
 */
class Base extends Base_Skin {

    use Traits\Infinite_Scroll;
    use Traits\Pagination;
    use Traits\Hover;
    use Traits\Reveal;

    use Traits\Post;
    use Traits\Term;
    use Traits\User;
    use Traits\Media;
    use Traits\Item;
    use Traits\Common;
    use Traits\Custommeta;
    use Traits\Label;

    protected $current_permalink;
    protected $current_id;
    protected $current_data;
    protected $counter = 0;
    protected $depended_scripts = [];
    protected $depended_styles = [];

    public function _register_controls_actions() {

        add_action('elementor/element/' . $this->parent->get_name() . '/section_items/after_section_end', [$this, 'register_controls_layout']);
        add_action('elementor/element/' . $this->parent->get_name() . '/section_items/before_section_start', [$this, 'register_controls_hovereffects']);

        add_action('elementor/preview/enqueue_scripts', [$this, 'preview_enqueue']);
    }

    public function register_controls_layout(Widget_Base $widget) {
        //$this->parent = $widget;
        // BLOCKS generic style 
        $this->register_style_controls();
        // PAGINATION style
        $this->register_style_pagination_controls();
        //INFINITE SCROLL style
        $this->register_style_infinitescroll_controls();
    }

    // ---------------------------------------------------------------
    protected function register_style_controls() {
        //
        // Blocks - Style
        $this->start_controls_section(
            'section_blocks_style',
            [
                'label' => __('Blocks Style', 'e-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'style_items!' => 'template',
                ]
            ]
        );
        $this->add_responsive_control(
            'blocks_align', [
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
                    '{{WRAPPER}} .e-add-post-item' => 'text-align: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'heading_blocks_align_flex',
            [
                'type' => Controls_Manager::RAW_HTML,
                'show_label' => false,
                'raw' => '<i class="fas fa-arrows-alt" aria-hidden="true"></i> '.__('Flex Alignnment', 'e-addons'),
                'separator' => 'before',
                'content_classes' => 'e-add-inner-heading',
                /*'condition' => [
                    $this->get_control_id('v_pos_postitems') => ['', 'stretch'],
                ],*/
            ]
        );
        $this->add_responsive_control(
            'blocks_align_flex', [
                'label' => __('Flex Align-items', 'e-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => 'Default',
                    'flex-start' => 'Start',
                    'center' => 'Center',
                    'flex-end' => 'End',
                    'space-between' => 'Space Between',
                    'space-around' => 'Space Around',
                    'stretch' => 'Stretch',
                ],
                'selectors' => [
                    '{{WRAPPER}} .e-add-post-block, {{WRAPPER}} .e-add-item-area' => 'align-items: {{VALUE}} !important;',
                ],
                /*'condition' => [
                    $this->get_control_id('v_pos_postitems') => ['', 'stretch'],
                ],*/
            ]
        );
        $this->add_responsive_control(
            'blocks_align_justify', [
                'label' => __('Flex justify-content', 'e-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => 'Default',
                    'flex-start' => 'Start',
                    'center' => 'Center',
                    'flex-end' => 'End',
                    'space-between' => 'Space Between',
                    'space-around' => 'Space Around',
                    'stretch' => 'Stretch',
                ],
                'selectors' => [
                    '{{WRAPPER}} .e-add-post-block, {{WRAPPER}} .e-add-item-area' => 'justify-content: {{VALUE}} !important;',
                ],
                'separator' => 'after',
                /*'condition' => [
                    $this->get_control_id('v_pos_postitems') => ['', 'stretch'],
                ],*/
            ]
        );
        $this->add_control(
            'blocks_bgcolor', [
                'label' => __('Background Color', 'e-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .e-add-post-item .e-add-post-block' => 'background-color: {{VALUE}};'
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'blocks_border',
                'selector' => '{{WRAPPER}} .e-add-post-item .e-add-post-block',
            ]
        );
        $this->add_responsive_control(
            'blocks_padding', [
                'label' => __('Padding', 'e-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .e-add-post-item .e-add-post-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'blocks_border_radius', [
                'label' => __('Border Radius', 'e-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .e-add-post-item .e-add-post-block' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(), [
                'name' => 'blocks_boxshadow',
                'selector' => '{{WRAPPER}} .e-add-post-item .e-add-post-block',
            ]
        );
        // Vertical Alternate
        /*
          $this->add_control(
          'dis_alternate',
          [
          'type' => Controls_Manager::RAW_HTML,
          'show_label' => false,
          'separator' => 'before',
          'raw' => '<img src="' . E_ADDONS_QUERY_URL . 'assets/img/skins/alternate.png' . '" />',
          'content_classes' => 'e-add-skin-dis',
          'condition' => [
          $this->get_control_id('grid_type') => ['flex']
          ],
          ]
          );

          $this->add_responsive_control(
          'blocks_alternate', [
          'label' => __('Vertical Alternate', 'e-addons'),
          'type' => Controls_Manager::SLIDER,
          'size_units' => ['px'],
          'range' => [
          'px' => [
          'max' => 100,
          'min' => 0,
          'step' => 1,
          ],
          ],
          'selectors' => [
          '{{WRAPPER}}.e-add-col-3 .e-add-post-item:nth-child(3n+2) .e-add-post-block, {{WRAPPER}}:not(.e-add-col-3) .e-add-post-item:nth-child(even) .e-add-post-block' => 'margin-top: {{SIZE}}{{UNIT}};',
          ],
          'condition' => [
          $this->get_control_id('grid_type') => ['flex']
          ],
          ]
          ); */
        $this->end_controls_section();

        //
    }

    public function render() {
        $this->parent->render();

        /** @p enquequo gli script e gli style... */
        $this->enqueue();

        /** @p elaboro la query... */
        $this->parent->query_the_elements();

        /** @p qui prendo il valore di $query elaborato in query-base.php */
        $query = $this->parent->get_query();
        $querytype = $this->parent->get_querytype();

        $this->render_loop_start();

        switch ($querytype) {
            case 'media':
            case 'post':
                if (!$query->found_posts) {
                    return;
                }

                /** @p qui identifico se mi trovo in un loop, altrimenti uso la wp_query */
                if ($query->in_the_loop) {
                    $this->current_permalink = get_permalink();
                    $this->current_id = get_the_ID();
                    $this->current_data = get_post( get_the_ID() );
                    //
                    $this->render_element_item();
                } else {
                    while ($query->have_posts()) {
                        $query->the_post();

                        $this->current_permalink = get_permalink();
                        $this->current_id = get_the_ID();
                        $this->current_data = get_post( get_the_ID() );
                        //
                        $this->render_element_item();
                    }
                }
                wp_reset_postdata();

                break;
            case 'user':

                if (!empty($query->get_results())) {
                    //var_dump($query->get_results());
                    //global $user;
                    //$user_temp = $user;
                    foreach ($query->get_results() as $user) {
                        $this->current_permalink = get_author_posts_url($user->ID);
                        $this->current_id = $user->ID;
                        $this->current_data = $user;
                        //
                        $this->render_element_item();
                    }
                    //$user = $user_temp;
                } else {
                    echo 'No users found.';
                }

                break;
            case 'term':

                /* foreach($query->get_terms() as $term){ 

                  echo $term->name." (".$term->count.")<br>";

                  } */
                if (!empty($query) && !is_wp_error($query)) {
                    foreach ($query->get_terms() as $term) {
                        $this->current_permalink = get_term_link($term->term_id);
                        $this->current_id = $term->term_id;
                        $this->current_data = $term;
                        //
                        $this->render_element_item();
                    }
                } else {
                    echo 'No terms found.';
                }

                break;
            case 'listof':

                echo 'questo è LISTOF (che nella mia testa è il widget-ACFrepeater)';

                break;
            case 'items':
                // il ripetitore di contenuti statici
                $sl_items = $this->parent->get_settings_for_display('repeater_staticlist');
                if ( !empty($sl_items) ) {
                    foreach ($sl_items as $item) {
                        //echo $item['sl_title'];
                        $this->current_permalink = $item['sl_link']['url'];
                        $this->current_id = '';
                        $this->current_data = $item;

                        //echo $this->current_data['sl_image']['id'];
                        //
                        $this->render_element_item();
                    }
                } else {
                    echo 'The element is empty.';
                }
                break;
        }

        $this->render_loop_end();

        $this->parent->render_pagination();
    }

    protected function render_element_item() {
        $style_items = $this->parent->get_settings_for_display('style_items');

        $this->render_item_start();

        if ($style_items == 'template') {
            $this->render_template();
        } else {
            $this->render_items();
        }

        $this->render_item_end();

        $this->counter++;
    }

    protected function render_template() {

        $template_id = $this->parent->get_settings_for_display('template_id');
        $templatemode_enable_2 = $this->parent->get_settings_for_display('templatemode_enable_2');
        $template_2_id = $this->parent->get_settings_for_display('template_2_id');

        if ($templatemode_enable_2) {
            if ($this->counter % 2 == 0) {
                // Even
                $post_template_id = $template_id;
            } else {
                // Odd
                $post_template_id = $template_2_id;
            }
        } else {
            $post_template_id = $template_id;
        }

        if ($post_template_id)
            $this->render_e_template($post_template_id);
        //
    }

    protected function render_e_template($id_temp) {

        $args = array();
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $args['css'] = true;
        }
        $args['post_id'] = $this->current_id;

        echo \EAddonsForElementor\Core\Managers\Template::e_template($id_temp, $args);
    }

    /* !!!!!!!!!!!!!!!!!!! ITEMS !!!!!!!!!!!!!!!!! */
    protected function render_items() {
        $_skin = $this->parent->get_settings_for_display('_skin');
        $style_items = $this->parent->get_settings_for_display('style_items');

        //@p in caso di justifiedgrid forzo lo style_items in "float"
        //@p probabilmente farò lo stesso anche per lo skin rhombus ex diamond
        if($_skin == 'justifiedgrid'){
            $style_items = 'float';
        }

        //@p questo interviene per animare al rollhover il blocco di contenuto
        $hover_animation = $this->get_instance_value('hover_content_animation');
        $animation_class = !empty($hover_animation) && $style_items != 'float' && $_skin != 'gridtofullscreen3d' ? ' elementor-animation-' . $hover_animation : '';

        //@p questo interviene per fare gli effetti di animazione al rollhover in caso di layout-FLOAT
        $hover_effects = $this->get_instance_value('hover_text_effect');
        $hoverEffects_class = !empty($hover_effects) && $style_items == 'float' && $_skin != 'gridtofullscreen3d' ? ' e-add-hover-effect-' . $hover_effects . ' e-add-hover-effect-content e-add-close' : '';

        $hoverEffects_start = !empty($hover_effects) && $style_items == 'float' && $_skin != 'gridtofullscreen3d' ? '<div class="e-add-hover-effect-' . $hover_effects . ' e-add-hover-effect-content e-add-close">' : '';
        $hoverEffects_end = !empty($hover_effects) && $style_items == 'float' ? '</div>' : '';
        
        //@p NOTA:  [x timeline] una piccola considerazione a timeline....
        //          forzo la timeliine al layout default e non uso l'immagine nel content
        if ( ($style_items && $style_items != 'default' && $_skin != 'timeline') || $_skin == 'justifiedgrid' ) {
            // Layouts
            //echo 'sono un layout: left/right/alternate/textZone/overlay/float';
            echo '<div class="e-add-image-area e-add-item-area">';
            $this->render_items_image();
            echo '</div>';
            echo $hoverEffects_start . '<div class="e-add-content-area e-add-item-area' . $animation_class . '">';
            $this->render_items_content(false); //@p il false non produce l'immagine
            echo '</div>' . $hoverEffects_end;
            
        } else {
            // Layout-default
            //echo 'sono layout-default';
            
            if($_skin == 'timeline'){
                $this->render_items_content(false); //@p il false non produce l'immagine
            }else{
                $this->render_items_content(true);
            }
        }
        
    }

    // IMAGE
    protected function render_items_image() {
        $_items = $this->parent->get_settings_for_display('list_items');
        $querytype = $this->parent->get_querytype();
        
        //QUERY_MEDIA //////////////////
        //@p l'immagine viene renderizzata sempre per il query_media widget
        if($querytype == 'media'){
            $this->render_repeateritem_start('e-add-media-image', 'item_image');
            //----------------------------------
            $this->render_item_image($this->parent->get_settings_for_display());
            //----------------------------------
            $this->render_repeateritem_end();
        }else{
            //
            // ITEMS ///////////////////////
            foreach ($_items as $item) {
                $_id = $item['_id'];
                $item_type = $item['item_type'];
                //
                if ( !empty($item) ) {

                    if ($item_type == 'item_image') {
                        $this->render_repeateritem_start($_id, $item_type);
                        //----------------------------------
                        $this->render_item_image($item);
                        //----------------------------------
                        $this->render_repeateritem_end();
                    }
                    if ($item_type == 'item_avatar') {
                        $this->render_repeateritem_start($_id, $item_type);
                        //----------------------------------
                        $this->render_item_avatar($item);
                        //----------------------------------
                        $this->render_repeateritem_end();
                    }
                }
            }
        }
    }

    // FIELDS
    protected function render_items_content($useimg) {
        $_items = $this->parent->get_settings_for_display('list_items');
        $querytype = $this->parent->get_querytype();
        //
        // QUERY_MEDIA //////////////////
        //@p l'immagine viene renderizzata sempre per il query_media widget
        if($querytype == 'media' && $useimg){
            $this->render_repeateritem_start('e-add-media-image', 'item_image');
            //----------------------------------
            $this->render_item_image($this->parent->get_settings_for_display());
            //----------------------------------
            $this->render_repeateritem_end();
        }
        //
        // ITEMS ///////////////////////
        foreach ($_items as $item) {
            $_id = $item['_id'];
            $item_type = $item['item_type'];
            //$custommetakey = $item['metafield_key'];

            if ( !empty($item) ) {

                //if( $item_type == 'item_custommeta' && $this->get_value_custommeta($custommetakey) )
                $this->render_repeateritem_start($_id, $item_type);
                //----------------------------------
                // posts
                switch ($item_type) {
                    case 'item_title': $this->render_item_title($item);
                        break;
                    case 'item_date': $this->render_item_date($item);
                        break;
                    case 'item_author': $this->render_item_author($item);
                        break;
                    case 'item_termstaxonomy': $this->render_item_termstaxonomy($item);
                        break;
                    case 'item_content': $this->render_item_content($item);
                        break;
                    case 'item_posttype': $this->render_item_posttype($item);
                        break;
                    //----------------------------------
                    // ueser
                    case 'item_displayname': $this->render_item_userdata('displayname', $item);
                        break;
                    case 'item_user': $this->render_item_userdata('user', $item);
                        break;
                    case 'item_role': $this->render_item_userdata('role', $item);
                        break;
                    case 'item_firstname': $this->render_item_userdata('firstname', $item);
                        break;
                    case 'item_lastname': $this->render_item_userdata('lastname', $item);
                        break;
                    case 'item_nickname': $this->render_item_userdata('nickname', $item);
                        break;
                    case 'item_email': $this->render_item_userdata('email', $item);
                        break;
                    case 'item_website': $this->render_item_userdata('website', $item);
                        break;
                    case 'item_bio': $this->render_item_userdata('bio', $item);
                        break;
                    //----------------------------------
                    // terms
                    case 'item_counts': $this->render_item_postscount($item);
                        break;
                    case 'item_taxonomy': $this->render_item_taxonomy($item);
                        break;
                    case 'item_imagemeta': $this->render_item_imagemeta($item);
                        break;
                    case 'item_mimetype': $this->render_item_mimetype($item);
                        break;
                    case 'item_description': $this->render_item_description($item);
                        break;
                    //----------------------------------
                    // media
                    case 'item_caption': $this->render_item_caption($item);
                        break;
                    case 'item_alternativetext': $this->render_item_alternativetext($item);
                        break; 
                    case 'item_uploadedto': $this->render_item_uploadedto($item);
                        break;
                    //----------------------------------
                    // items list
                    case 'item_subtitle': $this->render_item_subtitle($item);
                        break;
                    case 'item_descriptiontext': $this->render_item_descriptiontext($item);
                        break;
                    //----------------------------------
                    // posts/user/terms
                    case 'item_custommeta': $this->render_item_custommeta($item);
                        break;
                    case 'item_readmore': $this->render_item_readmore($item);
                        break;
                    case 'item_label': $this->render_item_labelhtml($item);
                        break;

                    case 'item_image':
                        if ($useimg) {
                            $this->render_item_image($item);
                        }
                        break;
                    case 'item_avatar':
                        if ($useimg) {
                            $this->render_item_avatar($item);
                        }
                        break;
                }
                
                //----------------------------------
                $this->render_repeateritem_end();
            }
        }
        
    }

    // REPEATER-ITEM start
    protected function render_repeateritem_start($id, $item_type) {
        /* echo 'eadditem_' . $id . '_' . $item_type;
          $this->parent->add_render_attribute('eadditem_' . $id . '_' . $item_type, [
          'class' => [
          'e-add-item',
          'e-add-' . $item_type,
          'elementor-repeater-item-' . $id
          ],
          'data-item-id' => [
          $id
          ]
          ]
          ); */
        $classItem = 'class="e-add-item e-add-' . $item_type . ' elementor-repeater-item-' . $id . '"';
        $dataIdItem = ' data-item-id="' . $id . '"';

        echo '<div ' . $classItem . $dataIdItem /* $this->parent->get_render_attribute_string('eadditem_' . $id . '_' . $item_type) */ . '>';
    }

    // REPEATE-ITEM end
    protected function render_repeateritem_end() {
        echo '</div>';
    }

    

    /////////////////////////////////////////////////////////////
    // render post item -----------------------------------------
    protected function render_item_start($key = 'post') {
        $hover_animation = $this->get_instance_value('hover_animation');
        $animation_class = !empty($hover_animation) ? ' elementor-animation-' . $hover_animation : '';

        $_skin = $this->parent->get_settings_for_display('_skin');
        $style_items = $this->parent->get_settings_for_display('style_items');

        //@p in caso di justifiedgrid forzo lo style_items in "float"
        //@p probabilmente farò lo stesso anche per lo skin rhombus ex diamond
        if($_skin == 'justifiedgrid'){
            $style_items = 'float';
        }

        $hover_effects = $this->get_instance_value('hover_text_effect');
        $hoverEffects_class = !empty($hover_effects) && $style_items == 'float' ? ' e-add-hover-effects' : '';
        
        //@p data post ID
        $data_post_id = ' data-e-add-post-id="' . $this->current_id . '"';
        //@p data post INDEX
        $data_post_index = ' data-e-add-post-index="' . $this->counter . '"';
        //@p una classe personalizzata per lo skin
        $item_class = ' ' . $this->get_item_class();
        ?>
        <article <?php post_class(['e-add-post e-add-post-item e-add-post-item-' . $this->parent->get_id() . $item_class]);
        echo $data_post_id . $data_post_index;
        ?>>
            <div class="e-add-post-block<?php echo $hoverEffects_class . $animation_class; ?>">
                
                <?php
    }

    protected function render_item_end() {
                ?>

            </div>
        </article>
        <?php
    }

    ////////////////////////////////////////////////////////////////
    // render loop wrapper -----------------------------------------
    protected function render_loop_start() {


        // TO DO
        /** @p qui prendo il valore di $query elaborato in query.php /base */
        /*
          $wp_query = $this->parent->get_query();

          // @p questa è una classe che c'è solo se ci sono posts, identifica se non è vuoto.
          if ( $wp_query->found_posts ) {
          $classes[] = 'e-add-grid';
          }
         */
        $this->parent->add_render_attribute('eaddposts_container', [
            'class' => [
                'e-add-posts-container',
                'e-add-posts',
                $this->get_scrollreveal_class(), //@p prevedo le classi per generare il reveal,
                $this->get_container_class(), //@p una classe personalizzata per lo skin
            ],
        ]);
        $this->parent->add_render_attribute('eaddposts_container_wrap', [
            'class' => [
                'e-add-posts-wrapper',
                $this->get_wrapper_class(), //@p una classe personalizzata per lo skin
            ],
        ]);
        ?>
        <?php $this->render_container_before(); ?>
        <div <?php echo $this->parent->get_render_attribute_string('eaddposts_container'); ?>>
            <?php $this->render_posts_before(); ?>
            <div <?php echo $this->parent->get_render_attribute_string('eaddposts_container_wrap'); ?>>
                <?php
                $this->render_postsWrapper_before();
    }

    protected function render_loop_end() {
                $this->render_postsWrapper_after();
                ?>      
            </div>
            <?php
            $this->render_posts_after();
            ?>
        </div>
        <?php $this->render_container_after(); ?>
        <?php
    }

    protected function render_container_before() {
        
    }

    protected function render_container_after() {
        
    }

    protected function render_posts_before() {
        
    }

    protected function render_posts_after() {
        
    }

    protected function render_postsWrapper_before() {
        
    }

    protected function render_postsWrapper_after() {
        
    }

    // Classes ----------
    public function get_container_class() {
        return 'e-add-skin-' . $this->get_id();
    }

    public function get_wrapper_class() {
        return 'e-add-wrapper-' . $this->get_id();
    }

    public function get_item_class() {
        return 'e-add-item-' . $this->get_id();
    }

    public function get_image_class() {
        
    }

    public function get_scrollreveal_class() {
        return '';
    }

    // Utility ----------
    public function filter_excerpt_length() {
        return $this->get_instance_value('textcontent_limit');
    }

    public function filter_excerpt_more($more) {
        return '';
    }

    protected function limit_content($limit) {
        $post = get_post();
        $content = $post->post_content; //do_shortcode($post['post_content']); //$content_post->post_content; //
        //
        $content = substr(wp_strip_all_tags($content), 0, $limit) . ' ...'; //

        return $content;
    }
}