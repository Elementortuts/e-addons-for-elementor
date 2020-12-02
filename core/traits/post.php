<?php

namespace EAddonsForElementor\Core\Traits;

/**
 * Description of post
 *
 * @author fra
 */
trait Post {

    public static function get_post_fields($filter = false, $info = true) {
        return self::get_fields(get_post(), $filter, $info);
    }

    public static function get_post_types($exclude = true) {
        $post_types = get_post_types(array('public' => true));
        if ($exclude) {
            $post_types = array_diff($post_types, ['attachment', 'elementor_library']);
        }
        foreach ($post_types as $akey => $acpt) {
            $post_type = get_post_type_object($acpt);
            $post_types[$akey] = $post_type->label;
        }
        return $post_types;
    }

    public static function get_post_terms($p_id = 0, $taxonomy = null, $args = array(), $taxonomies_args = array('public' => true)) {
        $p_id = $p_id ? $p_id : get_the_ID();
        if ($taxonomy) {
            $terms = wp_get_post_terms($p_id, $taxonomy, $args);
            return is_wp_error($terms) ? false : $terms;
        }
        $terms = array();
        $post_taxonomies = get_taxonomies($taxonomies_args);
        if (!empty($post_taxonomies)) {
            foreach ($post_taxonomies as $atax) {
                $tmp = wp_get_post_terms($p_id, $atax, $args);
                $terms = array_merge($terms, $tmp);
            }
        }
        return $terms;
    }

    public static function get_post_field($p_id = null, $field = 'post_title', $single = null) {
        $value = null;
        $p_id = (is_int($p_id)) ? $p_id : get_the_ID();
        $post = get_post($p_id);
        if ($post) {
            switch ($field) {
                case 'permalink':
                case 'get_permalink':
                    $value = get_permalink($p_id);
                    break;

                case 'post_excerpt':
                case 'excerpt':
                    $value = get_the_excerpt($p_id);
                    break;

                case 'thumbnail': 
                case 'post_thumbnail':
                case 'thumb':
                    $value = get_the_post_thumbnail_url($p_id);
                    break;

                case 'the_author': 
                case 'author':
                    $value = get_the_author($post->post_author);
                    break;

            }
            if ($value === null) {
                $value = self::get_wp_object_field($post, $field, $single);
            }
            if ($value === null) {
                $value = self::get_post_terms($p_id, $field);
            }
        }
        return self::adjust_data($value, $single);
    }
    
    public static function get_post_url($id = null) {
        return get_permalink($id);
    }
    
    public static function url_to_postid($url = '') {
        if (!empty($_GET['p'])) {
            return intval($_GET['p']);
        }
        if (!$url) {
            global $wp;
            $url = home_url(add_query_arg(array(), $wp->request));
        }
        $id = url_to_postid($url);
        if ($id) {
            return $id;
        }
        global $wpdb;
        $sql = "SELECT ID FROM " . $wpdb->prefix . "posts WHERE guid LIKE '%" . $url . "';";
        $col = $wpdb->get_col($sql);
        if ($col) {
            return reset($col);
        }
        return false;
    }

}
