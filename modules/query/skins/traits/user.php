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
 * Description of User
 *
 * @author fra
 */
trait User {

    // USERS
    protected function render_item_avatar($settings) {
        $user_info = $this->current_data;
        // Settings ------------------------------
        $use_bgimage = $settings['use_bgimage'];
        $use_overlay = $settings['use_overlay'];
        $use_overlay_hover = $this->get_instance_value('use_overlay_hover');
        $use_link = !empty($settings['use_link']) ? $settings['use_link'] : '';

        //
        // ---------------------------------------
        // @p preparo il dato in base a 'thumbnail_size'
        $avatarsize = $settings['avatar_size'];

        $image_attr = [
            'class' => $this->get_image_class()
        ];
        // @p questa è l'mmagine avatar HTML
        $avatar_html = get_avatar($user_info->user_email, $avatarsize);
        // @p questa è l'mmagine avatar URL
        $avatar_url = get_avatar_url($user_info->user_email, $avatarsize);


        $bgimage = '';
        if ($use_bgimage) {
            $bgimage = ' e-add-post-bgimage';
        }
        $overlayimage = '';
        if ($use_overlay) {
            $overlayimage = ' e-add-post-overlayimage';
        }
        $overlayhover = '';
        if ($use_overlay_hover) {
            $overlayhover = ' e-add-post-overlayhover';
        }

        $html_tag = 'div';
        $attribute_link = '';
        if ($use_link) {
            $html_tag = 'a';
            $attribute_link = ' href="' . $this->current_permalink . '"';
        }
        echo '<' . $html_tag . ' class="e-add-post-image' . $bgimage . $overlayimage . $overlayhover . '"' . $attribute_link . '>';

        if ($use_bgimage) {
            echo '<figure class="e-add-img e-add-bgimage" style="background: url(' . $avatar_url . ') no-repeat center; background-size: cover; display: block;"></figure>';
        } else {
            echo '<figure class="e-add-img">' . $avatar_html . '</figure>';
        }

        echo '</' . $html_tag . '>';
    }

    protected function render_item_userdata($usertype, $settings) {
        $user_id = $this->current_id;
        $user_info = $this->current_data;
        $c = $this->counter;

        $use_link = !empty($settings['use_link']) ? $settings['use_link'] : '';
        $html_tag = !empty($settings['html_tag']) ? $settings['html_tag'] : 'div';
        
        $start_a = '';
        $end_a = '';
        if ($use_link) {
            $attribute_link = 'href="' . $this->current_permalink . '"';

            // in caso di email
            if ($usertype == 'email')
                $attribute_link = 'href="mailto:' . $user_info->user_email . '"';

            // in caso di website
            if ($usertype == 'website')
                $attribute_link = 'href="' . $user_info->user_url . '" target="_blank"';

            $start_a = '<a ' . $attribute_link . '>';
            $end_a = '</a>';
        }
        
        echo sprintf('<%1$s class="e-add-queryuser-%2$s">', $html_tag, $usertype) . $start_a;

        switch ($usertype) {
            case 'displayname' :
                //echo 'sono Display Name';
                echo $this->render_label_before_item($settings,'Name: ');
                echo $user_info->display_name;
                break;
            case 'user' :
                //echo 'sono l\'user';
                echo $this->render_label_before_item($settings,'User: ');
                echo $user_info->user_login;
                break;
            case 'role' :
                //echo 'sono il ruolo';
                echo $this->render_label_before_item($settings,'Role: ');
                echo Utils::to_string($user_info->roles);
                break;
            case 'firstname' :
                //echo 'sono il first name';
                echo $this->render_label_before_item($settings,'First Name: ');
                echo $user_info->first_name;
                break;
            case 'lastname' :
                //echo 'sono il last name';
                echo $this->render_label_before_item($settings,'Last Name: ');
                echo $user_info->last_name;
                break;
            case 'nickname' :
                //echo 'sono il Nickname';
                echo $this->render_label_before_item($settings,'Nick Name: ');
                echo $user_info->user_nicename;
                break;
            case 'email' :
                //echo 'sono l\'email'; 
                echo $this->render_label_before_item($settings,'Email: ');
                echo $user_info->user_email;
                break;
            case 'website' :
                //echo 'sono il website';
                echo $this->render_label_before_item($settings,'Website: ');
                echo $user_info->user_url;
                break;
            case 'bio' :
                //echo 'sono la bio';
                echo $this->render_label_before_item($settings,'Description: ');
                echo $user_info->description;
                break;
        }
        echo $end_a . sprintf('</%s>', $html_tag);
    }

}
