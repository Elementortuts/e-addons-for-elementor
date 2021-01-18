<?php
namespace EAddonsForElementor\Modules\Query\Skins\Traits;

use Elementor\Controls_Manager;

/**
 * Description of Animation Reveal
 *
 * @author fra
 */
trait Reveal {
    // ------------------------------------------------------------ [SECTION Aniimtion Reveal]
    public function register_reveal_controls() {
        $this->start_controls_section(
                'section_scrollreveal', [
                'label' => '<i class="eaddicon eicon-animation"></i> ' . __('Animation reveal', 'e-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'scrollreveal_effect_type', [
                'label' => __('Effect', 'e-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => '0',
                'options' => [
                    '0' => 'None',
                    '1' => 'Opacity',
                    '2' => 'Move Up',
                    '3' => 'Scale Up',
                    '4' => 'Fall Perspective',
                    '5' => 'Fly',
                    '6' => 'Flip',
                    '7' => 'Helix',
                    '8' => 'Bounce',
                ],
                'frontend_available' => true,
            ]
        );
        $this->add_control(
            'scrollreveal_live', [
                'label' => __('Live', 'e-addons'),
                'type' => Controls_Manager::SWITCHER,
                'frontend_available' => true,
                'condition' => [
                    $this->get_control_id('scrollreveal_effect_type!') => '0'
                ]
            ]
        ); 
        
        $this->end_controls_section();
    }
}
