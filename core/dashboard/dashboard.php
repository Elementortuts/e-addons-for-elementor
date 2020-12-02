<?php

namespace EAddonsForElementor\Core\Dashboard;

use EAddonsForElementor\Core\Utils;
use EAddonsForElementor\Base\Module_Base;
use Elementor\Settings;
use EAddonsForElementor\Includes\Edd\Edd;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Dashboard {

    public function __construct() {
        $this->maybe_redirect_to_getting_started();
        add_action( 'admin_init', [ $this, 'on_admin_init' ] );
        add_action('admin_menu', array($this, 'e_addons_menu'), 200);
        add_action('admin_enqueue_scripts', [$this, 'add_admin_dash_assets']);

        add_action("elementor/admin/after_create_settings/elementor", array($this, 'e_addons_elementor'));

        if (empty($_GET['page']) || $_GET['page'] != 'e_addons_getting_started') {
            add_action('admin_notices', [$this, 'e_admin_notice__license']);
        }
        
        $addons = \EAddonsForElementor\Plugin::instance()->get_addons();
        foreach ($addons as $akey => $addon) {
            add_filter('plugin_action_links_' . $addon['plugin'], [$this, 'e_plugin_action_links_settings']);
            if (!\EAddonsForElementor\Plugin::instance()->is_addon_valid($addon)) {
                add_filter('plugin_action_links_' . $addon['plugin'], [$this, 'e_plugin_action_links_license'], 10 , 2);
            }
        }
    }
    
    public function e_plugin_action_links_license($actions, $plugin_file) {
        //var_dump($plugin_file); die();
        $actions['license'] = '<a style="color:brown;" title="Activate license" href="' . admin_url() . 'admin.php?page=e_addons"><b>' . __('License', 'e_addons') . '</b></a>';
        return $actions;
    }
    public function e_admin_notice__license() {
        $addons = \EAddonsForElementor\Plugin::instance()->get_addons();
        foreach ($addons as $akey => $addon) {
            if (did_action('elementor/loaded')) {
                if (!\EAddonsForElementor\Plugin::instance()->is_addon_valid($addon)) {
                    \EAddonsForElementor\Core\Utils::e_admin_notice(
                            '<b>' . $addon['Name'] . __(' license is not active', 'e_addons') . '</b>' . '<br>' . __('Your copy seems to be not activated, please <a href="' . admin_url() . 'admin.php?page=e_addons">activate</a> or <a href="https://e-addons.com/plugins/' . $addon['TextDomain'] . '" target="blank">buy a new license code</a>.', 'e_addons'),
                            'error');
                }
            }
        }
    }

    public function e_plugin_action_links_settings($links) {
        $links['settings'] = '<a title="Configure settings" href="' . admin_url() . 'admin.php?page=e_addons_settings"><b>' . __('Settings', 'e_addons') . '</b></a>';
        return $links;
    }

    /**
     * @since 1.1
     * @access public
     */
    public function maybe_redirect_to_getting_started() {
        if (!wp_doing_ajax()) {
            if (!get_transient('e_addons_activation_redirect')) {
                return;
            }        
            delete_transient('e_addons_activation_redirect');
            if (is_network_admin() || isset($_GET['activate-multi'])) {
                return;
            }
            wp_safe_redirect(admin_url('admin.php?page=e_addons_getting_started'));
            exit;
        }
    }

    public function add_admin_dash_assets() {
        if (!empty($_GET['page'])) {
            switch ($_GET['page']) {
                case 'e_addons':
                    wp_enqueue_style('e-addons-admin-dash');
                    wp_enqueue_script('e-addons-admin-dash');
                    break;
                case 'e_addons_settings':
                    wp_enqueue_style('e-addons-admin-settings');
                    break;
                //default:
                case 'e_addons_getting_started':
                    wp_enqueue_style('e-addons-admin-welcome');
                case 'e_addons_changelog':
                    wp_enqueue_style('e-addons-admin-changelog');
            }
        }
        wp_enqueue_style('e-addons-admin');
        wp_enqueue_style('e-addons-icons');
    }

    public function e_addons_elementor(Settings $settings) {
        $settings->add_section(Settings::TAB_INTEGRATIONS, 'google_maps', [
            'label' => __('Google Maps', 'elementor-pro'),
            'fields' => [
                'google_maps_js_api_key' => [
                    'label' => __('Maps JavaScript API Key', 'elementor-pro'),
                    'field_args' => [
                        'type' => 'text',
                        'desc' => sprintf(__('To integrate custom Maps in page you need an <a href="%s" target="_blank">API Key</a>.', 'elementor-pro'), 'https://developers.google.com/maps/documentation/javascript/get-api-key'),
                    ],
                ],
            ],
        ]);
    }

    public function e_addons_menu() {
        $e_addons_plugins = \EAddonsForElementor\Plugin::instance()->get_addons(true);

        $e_addons_count = count($e_addons_plugins) - 1;
        if ($e_addons_count) {
            $counter = '<span class="update-plugins e-count count-' . $e_addons_count . '"><span class="update-count">' . $e_addons_count . '</span></span>';
        } else {
            //$counter = '<span class="update-plugins e-count count-0"><span class="update-count">0</span></span>';
            $counter = '';
        }
        

        //$sub_page = \Elementor\Settings::PAGE_ID;
        $sub_page = 'e_addons';

        add_menu_page(
                __('e-addons Dashboard', 'e-addons-for-elementor'),
                __('Addons', 'e-addons-for-elementor') . $counter,
                'manage_options',
                $sub_page,
                [
                    $this,
                    'dashboard'
                ],
                'dashicons-admin-generic',
                '58.5'
        );

        //if (count($e_addons_plugins) > 1) {
        add_submenu_page(
                $sub_page,
                __('e-addons Dashboard', 'e-addons-for-elementor'),
                __('Dashboard', 'e-addons-for-elementor'),
                'manage_options',
                'e_addons',
                [$this, 'dashboard']
        );
        //}

        add_submenu_page(
                $sub_page,
                __('e-addons Settings', 'e-addons-for-elementor'),
                __('Settings', 'e-addons-for-elementor'),
                'manage_options',
                'e_addons_settings',
                [$this, 'settings']
        );

        if (count($e_addons_plugins) > 1) {
            add_submenu_page(
                    $sub_page,
                    __('e-addons Version Control', 'e-addons-for-elementor'),
                    __('Version Control', 'e-addons-for-elementor'),
                    'manage_options',
                    'e_addons_version',
                    [$this, 'version']
            );
        }

        add_submenu_page(
                $sub_page,
                __('e-addons Changelog', 'e-addons-for-elementor'),
                __('Changelog', 'e-addons-for-elementor'),
                'manage_options',
                'e_addons_changelog',
                [$this, 'changelog']
        );
        
        add_submenu_page(
                $sub_page,
                __('e-addons Started', 'elementor'),
                __('Getting Started', 'elementor'),
                'manage_options',
                'e_addons_getting_started',
                [$this, 'getting_started']
        );
        /*
        $redirect = apply_filters('e_addons/more', false);
        if ( !$redirect ) {
            add_submenu_page(
                    $sub_page,
                    __('e-addons Add more', 'e-addons-for-elementor'),
                    __('More e-addons', 'e-addons-for-elementor'),
                    'manage_options',
                    'e_addons_more',
                    [$this, 'more_addons']
            );
        }
         */
    }

    public function top_menu() {
        $e_addons_plugins = \EAddonsForElementor\Plugin::instance()->get_addons(true);
        ?>
        <h1 class="e_addons_heading"><i class="eadd-logo-e-addons"></i> <b>Addons</b> for Elementor</h1>

        <span class="e_addons_version">v. <?php echo $e_addons_plugins['e-addons-for-elementor']['Version']; ?></span>

        <div id="e-addons-settings-tabs-wrapper" class="nav-tab-wrapper">

            <a id="e-tab-settings" class="nav-tab<?php echo $_GET['page'] == 'e_addons' ? ' nav-tab-active' : ''; ?>" href="?page=e_addons">
                <span class="elementor-icon eicon-apps"></span> Dashboard
            </a>
            <?php //if (count($e_addons_plugins) > 1) { ?>
                <a id="e-tab-settings" class="nav-tab<?php echo $_GET['page'] == 'e_addons_settings' ? ' nav-tab-active' : ''; ?>" href="?page=e_addons_settings">
                    <span class="elementor-icon eicon-settings"></span> Settings
                </a>
            <?php //} ?>
        <!--<a id="e-tab-integration" class="nav-tab<?php echo $_GET['page'] == 'e_addons_integration' ? ' nav-tab-active' : ''; ?>" href="?page=elementor#tab-integrations">
        <span class="elementor-icon eicon-plus-square-o"></span> Integrations
        </a>-->
            <?php if (count($e_addons_plugins) > 1) { ?>
                <a id="e-tab-version" class="nav-tab<?php echo $_GET['page'] == 'e_addons_version' ? ' nav-tab-active' : ''; ?>" href="?page=e_addons_version">
                    <span class="elementor-icon eicon-history"></span> Version control
                </a>
            <?php } ?>
            <a id="e-tab-changelog" class="nav-tab<?php echo $_GET['page'] == 'e_addons_changelog' ? ' nav-tab-active' : ''; ?>" href="?page=e_addons_changelog">
                <span class="elementor-icon eicon-info-circle-o"></span> Changelog
            </a>
        </div>
        <?php
    }

    public function dashboard() {
        include_once(__DIR__ . '/pages/dash.php');
    }

    public function settings() {
        include_once(__DIR__ . '/pages/settings.php');
    }

    public function version() {
        include_once(__DIR__ . '/pages/version.php');
    }

    public function changelog() {
        include_once(__DIR__ . '/pages/changelog.php');
    }

    public function getting_started() {
        include_once(__DIR__ . '/pages/getting_started.php');
    }
    
    public function more_addons() {        
        $this->dashboard();
    }
    
    public function on_admin_init() {
        if ( !empty( $_GET['page'] ) && 'e_addons_more' === $_GET['page'] ) {            
            wp_redirect( 'https://e-addons.com/?p=2950' );
            die;
        }
    }

}
