<?php

namespace EAddonsForElementor\Core\Ajax;

use EAddonsForElementor\Core\Utils;

/**
 * Ajax Actions
 *
 * @author fra
 */
class Actions {

    const ANY = 'any';
    const MIN_LENGHT = 2;
    
    public $actions = [];

    public function __construct() {
        add_action('elementor/ajax/register_actions', [$this, 'elementor_ajax_register_actions']);
        
        // Ajax page open Actions
        //add_action('wp_ajax_modal_action', array($this, 'modal_action'));
        //add_action('wp_ajax_nopriv_modal_action', array($this, 'modal_action'));

        $this->register_addons_actions();
    }

    public function elementor_ajax_register_actions($ajax_manager) {
        $ajax_manager->register_ajax_action('e_query_control_options', [$this, 'get_control_options']);
        $ajax_manager->register_ajax_action('e_query_control_search', [$this, 'get_control_search']);
        
        
    }
    
    public function register_addons_actions() {
        $addons = \EAddonsForElementor\Plugin::instance()->get_plugins();
        foreach ($addons as $addon) {
            if ($addon['active']) {
                $e_query_actions = glob($addon['path'].DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'controls'.DIRECTORY_SEPARATOR.'e-query'.DIRECTORY_SEPARATOR.'*.php');
                if (!empty($e_query_actions)) {
                    foreach($e_query_actions as $action) {
                        $action_class = \EAddonsForElementor\Core\Helper::path_to_class($action);
                        $this->actions[] = new $action_class();
                    }
                }
            }
        }
    }
    
    /*public static function modal_action() {
        
        //echo \EAddonsForElementor\Core\Managers\Template::ajax_template();
        //wp_die();
        
        $post_id = Utils::get_id($_POST['post_href']);
        $template_id = 0;
        if (!empty($_POST['template_id']) && is_numeric($_POST['template_id'])) {
            $template_id = intval($_POST['template_id']);
        }

        $title_seo = get_post_meta($post_id, '_yoast_wpseo_title', true); //get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
        $title = (!empty($title_seo)) ? $title_seo : get_the_title($post_id) . ' - ' . get_bloginfo('name');        

        
        
        $args = array('post_id' => $post_id, 'ajax' => true);
        $template = \EAddonsForElementor\Core\Managers\Template::e_template($template_id, $args);
        ?>
        <div class="content-p">        
            <div class="title-p"><?php echo $title; ?></div>        
        </div>
        <?php        
        wp_die();
    }*/

    /**
     * Calls function to get value titles depending on ajax query type
     *
     * @since  1.0.1
     * @return array
     */
    public function get_control_options($params) {
        $method = '_get_' . $params['query_type'];
        $control_options = array();
        if (method_exists($this, $method)) {
            $control_options = call_user_func([$this, $method], $params);
        }            
        $control_options = apply_filters('e_addons/e_query/options/'.$params['query_type'], $control_options, $params);        
        return $control_options;
    }

    public function get_control_search(array $params) {
        if (empty($params['query_type']) || empty($params['q'])) {
            throw new \Exception('Bad Request');
        }
        $method = 'get_' . $params['query_type'];
        $control_options = array();
        if (method_exists($this, $method)) {
            $control_options = call_user_func([$this, $method], $params);
        }
        $control_options = apply_filters('e_addons/e_query/search/'.$params['query_type'], $control_options, $params);        
        return [
            'results' => $control_options,
        ];
    }

    /*     * *********************************************************************** */

    public function get_options($params) {
        $control_options = [];
        $options = Utils::get_options($params['q']);
        if (!empty($options)) {
            foreach ($options as $okey => $oname) {
                $control_options[] = [
                    'id' => $okey,
                    'text' => $oname,
                ];
            }
        }
        return $control_options;
    }

    public function _get_options($params) {
        $control_options = [];
        $uid = (array) $params['id'];
        $options = Utils::get_options($uid);
        if (!empty($options)) {
            foreach ($options as $okey => $oname) {
                $control_options[] = [
                    'id' => $okey,
                    'text' => $oname,
                ];
            }
        }
        return $control_options;
    }

    public function get_fields($params) {
        $control_options = [];
        if ($params['object_type'] == self::ANY) {
            $object_types = array('post', 'user', 'term');
        } else {
            $object_types = array($params['object_type']);
        }
        foreach ($object_types as $object_type) {
            $fields = Utils::get_fields($object_type, $params['q']);
            if (!empty($fields)) {
                foreach ($fields as $field_key => $field_name) {
                    $control_options[] = [
                        'id' => $field_key,
                        'text' => ($params['object_type'] == self::ANY ? '[' . $object_type . '] ' : '') . $field_name,
                    ];
                }
            }
        }
        //$control_options = array_reverse($control_options);
        return $control_options;
    }

    public function _get_fields($params) {
        $uid = (array) $params['id'];
        $control_options = [];
        if ($params['object_type'] == self::ANY) {
            $object_types = array('post', 'user', 'term');
        } else {
            $object_types = array($params['object_type']);
        }
        foreach ($object_types as $object_type) {
            foreach ($uid as $aid) {
                $fields = Utils::get_fields($object_type, $aid);
                if (!empty($fields)) {
                    
                    foreach ($fields as $field_key => $field_name) {
                        if (in_array($field_key, $uid)) {
                            $control_options[$field_key] = $field_name;
                        }
                    }
                }
            }
        }
        return $control_options;
    }

    public function get_terms_fields($params) {
        $control_options = [];
        $control_options = $this->get_fields($params);
        $terms = Utils::get_taxonomy_terms(null, $params['q']);
        if (!empty($terms)) {
            foreach ($terms as $field_key => $field_name) {
                $term = get_term_by('id', $field_key);
                $field_key = 'term_' . $term->slug;
                $control_options[] = [
                    'id' => $field_key,
                    'text' => ($params['object_type'] == self::ANY ? '[taxonomy_term] ' : '') . $field_name,
                ];
            }
        }
        return $control_options;
    }

    public function _get_terms_fields($params) {
        $uid = (array) $params['id'];
        $uid_post = array();
        $uid_term = array();
        foreach ($uid as $aid) {
            if (substr($aid, 0, 5) == 'term_') {
                $uid_term[] = substr($aid, 5);
            } else {
                $uid_post[] = $aid;
            }
        }
        $control_options = [];
        if (!empty($uid_post)) {
            $params['id'] = $uid_post;
            $posts = $this->_get_fields($params);
            if (!empty($posts)) {
                foreach ($posts as $key => $value) {
                    $control_options[$key] = $value;
                }
            }
        }
        if (!empty($uid_term)) {
            $params['id'] = $uid_term;
            $terms = $this->_get_terms($params);
            if (!empty($terms)) {
                foreach ($terms as $key => $value) {
                    $control_options['term_' . $key] = $value;
                }
            }
        }
        //$control_options = $this->_get_fields($params) + $this->_get_terms($params);
        return $control_options;
    }

    public function get_taxonomies($params) {
        $control_options = [];
        $taxonomies = Utils::get_taxonomies(false, $params['q']);
        if (!empty($taxonomies)) {
            foreach ($taxonomies as $field_key => $field_name) {
                if ($field_key) {
                    $control_options[] = [
                        'id' => $field_key,
                        'text' => $field_name,
                    ];
                }
            }
        }
        return $control_options;
    }

    public function _get_taxonomies($params) {
        $uid = (array) $params['id'];
        $control_options = [];
        foreach ($uid as $value) {
            $taxonomies = Utils::get_taxonomies(false, null, $value);
            if (!empty($taxonomies)) {
                foreach ($taxonomies as $field_key => $field_name) {
                    if ($field_key) {
                        $control_options[$field_key] = $field_name;
                    }
                }
            }
        }
        return $control_options;
    }

    public function get_taxonomies_fields($params) {
        $control_options = [];
        $control_options = $this->get_fields($params);
        $taxonomies = Utils::get_taxonomies(false, null, $params['q']);
        if (!empty($taxonomies)) {
            foreach ($taxonomies as $field_key => $field_name) {
                if ($field_key) {
                    $field_key = 'taxonomy_' . $field_key;
                    $control_options[] = [
                        'id' => $field_key,
                        'text' => '[taxonomy] ' . $field_name,
                    ];
                }
            }
        }
        return $control_options;
    }

    public function _get_taxonomies_fields($params) {
        $uid = (array) $params['id'];
        $uid_post = array();
        $uid_tax = array();
        foreach ($uid as $aid) {
            if (substr($aid, 0, 9) == 'taxonomy_') {
                $uid_tax[] = substr($aid, 9);
            } else {
                $uid_post[] = $aid;
            }
        }
        $control_options = [];
        if (!empty($uid_post)) {
            $params['id'] = $uid_post;
            $posts = $this->_get_fields($params);
            if (!empty($posts)) {
                foreach ($posts as $key => $value) {
                    $control_options[$key] = $value;
                }
            }
        }
        if (!empty($uid_tax)) {
            $params['id'] = $uid_tax;
            $taxonomies = $this->_get_taxonomies($params);
            if (!empty($taxonomies)) {
                foreach ($taxonomies as $key => $value) {
                    $control_options['taxonomy_' . $key] = $value;
                }
            }
        }
        return $control_options;
    }

    public function get_uploads($params) {
        $control_options = [];
        $types = (!empty($params['object_type'])) ? $params['object_type'] : null;

        $upload_dir = wp_upload_dir();
        $uploads_basedir = $upload_dir['basedir'];
        switch ($types) {
            case 'folder':
                $contents = Utils::recursive_glob($uploads_basedir, '/*', GLOB_ONLYDIR);
                break;
            case 'file':
                $contents = Utils::recursive_glob($uploads_basedir, '/*', 'GLOB_ONLYFILE');
                break;
            default:
                $contents = Utils::recursive_glob($uploads_basedir, '/*');
                break;
        }

        if (!empty($contents)) {
            foreach ($contents as $akey => $acontent) {
                $file_path = str_replace($uploads_basedir . '/', '', $acontent);
                if (strlen($params['q']) > self::MIN_LENGHT) {
                    if (strpos($file_path, $params['q']) === false) {
                        continue;
                    }
                }
                $control_options[] = [
                    'id' => $file_path,
                    'text' => $file_path,
                ];
            }
        }
        return $control_options;
    }

    public function _get_uploads($params) {
        return $this->_get_files($params);
    }

    public function get_files($params) {
        $control_options = [];
        $root_dir = ABSPATH;
        $contents = glob($root_dir . $params['q'] . '*');

        if (!empty($contents)) {
            foreach ($contents as $akey => $acontent) {
                if (is_dir($acontent)) {
                    $acontent .= '/';
                }
                $file_path = str_replace($root_dir, '', $acontent);
                $control_options[] = [
                    'id' => $file_path,
                    'text' => $file_path,
                ];
            }
        }
        return $control_options;
    }

    public function _get_files($params) {
        $uid = (array) $params['id'];
        $control_options = [];
        foreach ($uid as $aid) {
            $control_options[$aid] = basename($aid);
        }
        return $control_options;
    }

    public function get_metas($params) {
        $control_options = [];
        $fields = Utils::get_metas($params['object_type'], $params['q']);
        foreach ($fields as $field_key => $field_name) {
            if ($field_key) {
                $control_options[] = [
                    'id' => $field_key,
                    'text' => $field_name,
                ];
            }
        }
        return $control_options;
    }

    public function _get_metas($params) {
        $uid = (array) $params['id'];
        $control_options = [];
        foreach ($uid as $aid) {
            $fields = Utils::get_metas($params['object_type'], $aid);
            foreach ($fields as $field_key => $field_name) {
                if (in_array($field_key, $uid)) {
                    $control_options[$field_key] = $field_name;
                }
            }
        }
        return $control_options;
    }

    public function get_posts($params) {
        $control_options = [];
        $object_type = (!empty($params['object_type'])) ? $params['object_type'] : self::ANY;

        switch ($object_type) {
            case 'type':
                $post_types = Utils::get_post_types();
                if (!empty($post_types)) {
                    foreach ($post_types as $akey => $apt) {
                        if (strlen($params['q']) > self::MIN_LENGHT) {
                            if (strpos($akey, $params['q']) === false && strpos($apt, $params['q']) === false) {
                                continue;
                            }
                        }
                        $control_options[] = [
                            'id' => $akey,
                            'text' => $apt,
                        ];
                    }
                }
                break;
            case 'meta':
                $fields = Utils::get_metas('post', $params['q']);
                foreach ($fields as $field_key => $field_name) {
                    if ($field_key) {
                        $control_options[] = [
                            'id' => $field_key,
                            'text' => $field_name,
                        ];
                    }
                }
                break;
            default:
                $query_params = [
                    'post_type' => $object_type,
                    's' => $params['q'],
                    'posts_per_page' => -1,
                ];
                if ('attachment' === $query_params['post_type']) {
                    $query_params['post_status'] = 'inherit';
                }
                if (class_exists('EAddonsForElementor\Overrides\E_Query')) {
                    $query = new \EAddonsForElementor\Overrides\E_Query($query_params);
                } else {
                    $query = new \WP_Query($query_params);
                }
                foreach ($query->posts as $post) {
                    $post_title = $post->post_title;
                    if (empty($params['object_type']) || $object_type == self::ANY) {
                        $post_title = '[' . $post->ID . '] ' . $post_title . ' (' . $post->post_type . ')';
                    }
                    if (!empty($params['object_type']) && $object_type == 'elementor_library') {
                        $etype = get_post_meta($post->ID, '_elementor_template_type', true);
                        $post_title = '[' . $post->ID . '] ' . $post_title . ' (' . $post->post_type . ' > ' . $etype . ')';
                    }
                    $control_options[] = [
                        'id' => $post->ID,
                        'text' => $post_title,
                    ];
                }
        }
        return $control_options;
    }

    public function _get_posts($params) {
        $uid = (array) $params['id'];
        $control_options = [];
        $is_txt = false;
        if (!empty($uid)) {
            if (is_array($uid)) {
                $first = reset($uid);
            } else {
                $first = $uid;
            }
            $is_txt = !is_numeric($first);
        }
        if ($is_txt) {
            $post_types = Utils::get_post_types();
            if (!empty($uid)) {
                // post type
                foreach ($uid as $aid) {
                    if (isset($post_types[$aid])) {
                        $control_options[$aid] = $post_types[$aid];
                    }
                }
                // meta
                foreach ($uid as $aid) {
                    if (empty($control_options)) {
                        global $wpdb;
                        $sql = "SELECT post_id FROM ".$wpdb->postmeta." WHERE meta_key = '".$aid."' LIMIT 1";
                        if ( $wpdb->get_var( $sql ) ) {
                            $control_options[$aid] = $aid;
                        }
                    }
                }
            }
            
        } else {
            foreach ($uid as $akey => $aid) {
                $uid[$akey] = intval($aid);
            }
            $args = [
                'post_type' => self::ANY,
                'posts_per_page' => -1,
                'ignore_sticky_posts' => true,
                'post__in' => $uid,
            ];
            if (class_exists('EAddonsForElementor\Overrides\E_Query')) {
                $the_query = new \EAddonsForElementor\Overrides\E_Query($args);
            } else {
                $the_query = new \WP_Query($args);
            }
            while ($the_query->have_posts()) :
                $the_query->the_post();
                $control_options[get_the_ID()] = get_the_title();
            endwhile;
            wp_reset_query();
            wp_reset_postdata();
        }
        return $control_options;
    }

    public function get_terms($params) {
        $control_options = [];
        if (empty($params['object_type'])) {
            $taxonomies = get_object_taxonomies('');
        } else {
            $post_type = get_post_type_object($params['object_type']);
            if ($post_type) {
                $taxonomies = get_object_taxonomies($params['object_type']);
            } else {
                $taxonomies = $params['object_type'];
            }
        }
        $query_params = [
            'taxonomy' => $taxonomies,
            'search' => $params['q'],
            'hide_empty' => false,
        ];
        $terms = get_terms($query_params);
        foreach ($terms as $term) {
            $term_name = $term->name;
            if (empty($params['object_type'])) {
                $taxonomy = get_taxonomy($term->taxonomy);
                $term_name = $term_name . ' ('.$taxonomy->labels->singular_name.')';
            }
            $control_options[] = [
                'id' => $term->term_id,
                'text' => $term_name,
            ];
        }
        return $control_options;
    }

    public function _get_terms($params) {
        $uid = (array) $params['id'];
        $control_options = [];
        $term_id = reset($uid);
        if (is_numeric($term_id)) {
            $query_params = [
                'include' => $uid,
            ];
        } else {
            $query_params = [
                'slug' => $uid,
            ];
        }
        $terms = get_terms($query_params);
        foreach ($terms as $term) {
            $control_options[$term->term_id] = $term->name;
        }
        return $control_options;
    }

    public function get_users($params) {
        $control_options = [];
        $object_type = (!empty($params['object_type'])) ? $params['object_type'] : false;
        if ($object_type == 'role') {
            $user_roles = Utils::get_user_roles();
            if (!empty($user_roles)) {
                foreach ($user_roles as $akey => $aur) {
                    if (strlen($params['q']) > self::MIN_LENGHT) {
                        if (strpos($akey, $params['q']) === false && strpos($aur, $params['q']) === false) {
                            continue;
                        }
                    }
                    $control_options[] = [
                        'id' => $akey,
                        'text' => $aur,
                    ];
                }
            }
        } else {
            $query_params = [
                'search' => '*' . $params['q'] . '*'
            ];
            if (!empty($params['object_type'])) {
                $query_params['role__in'] = Utils::explode($params['object_type']);
            }
            $users = get_users($query_params);
            foreach ($users as $user) {
                $control_options[] = [
                    'id' => $user->ID,
                    'text' => $user->display_name,
                ];
            }
        }
        return $control_options;
    }

    public function _get_users($params) {
        $uid = (array) $params['id'];
        $control_options = [];
        $is_role = false;
        if (!empty($uid)) {
            $first = reset($uid);
            $is_role = !is_numeric($first);
        }
        if ($is_role) {
            $roles = Utils::get_user_roles();
            if (!empty($uid)) {
                foreach ($uid as $aid) {
                    if (isset($roles[$aid])) {
                        $control_options[$aid] = $roles[$aid];
                    }
                }
            }
        } else {
            $query_params = [
                'fields' => [
                    'ID',
                    'display_name',
                ],
                'include' => $uid,
            ];
            $user_query = new \WP_User_Query($query_params);
            foreach ($user_query->get_results() as $user) {
                $control_options[$user->ID] = $user->display_name;
            }
        }
        return $control_options;
    }

    public function get_authors($params) {
        $control_options = [];
        $query_params = [
            'who' => 'authors',
            'has_published_posts' => true,
            'fields' => [ 'ID', 'display_name' ],
            'search' => '*' . $params['q'] . '*',
            'search_columns' => [ 'user_login', 'user_nicename' ],
        ];
        $user_query = new \WP_User_Query($query_params);
        foreach ($user_query->get_results() as $author) {
            $control_options[] = [
                'id' => $author->ID,
                'text' => $author->display_name,
            ];
        }
        return $control_options;
    }

    public function _get_authors($params) {
        $uid = (array) $params['id'];
        $control_options = [];
        $query_params = [
            'who' => 'authors',
            'has_published_posts' => true,
            'fields' => [ 'ID', 'display_name' ],
            'include' => $uid,
        ];
        $user_query = new \WP_User_Query($query_params);
        foreach ($user_query->get_results() as $author) {
            $control_options[$author->ID] = $author->display_name;
        }
        return $control_options;
    }

}