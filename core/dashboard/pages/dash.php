<?php
/* * ***************************************************** */

// NOTE: license check it's for code not hosted on .org //
/* * ***************************************************** */

use \EAddonsForElementor\Core\Utils;

// check user capabilities
if (!current_user_can('manage_options')) {
    return;
}

$e_addons = \EAddonsForElementor\Plugin::instance();
$all_addons = Utils::get_addons(true);

if (!empty($_REQUEST['action'])) {
    $action = sanitize_key($_REQUEST['action']);
    if (in_array($action, array('add', 'update'))) {
        $addon_url = esc_url_raw($_POST['url']);
        list($dwn, $addon_name) = explode('addon=', $addon_url);
        $wp_plugin_dir = str_replace('/', DIRECTORY_SEPARATOR, WP_PLUGIN_DIR);
        $e_addons_path = $wp_plugin_dir . DIRECTORY_SEPARATOR . $addon_name;
        $version_manager = \EAddonsForElementor\Plugin::instance()->version_manager;
        $version_manager->addon_backup($addon_name);
        $version_manager->download_plugin($addon_url, $e_addons_path);
        $e_addons->clear_addons();
    }
    if (in_array($action, array('vendors'))) {
        if (!empty($_GET['plugin'])) {
            $addon = sanitize_key($_REQUEST['plugin']);
            $e_addons->update_vendors($addon);
        }
        
    }
    /*if (Utils::is_plugin_active('e-addons-manager')) {  
        $manager = new \EAddonsForElementor\Modules\License\Globals\Activation();
        $manager->execute_action($action);
    }*/
    do_action('e_addons/dash/action', $action);
}
?>
<div class="wrap">

    <?php
    $this->top_menu();

    $has_license = false;    
    $e_addons_plugins = $e_addons->get_addons(true);
    $message = '';
    if (!empty($e_addons_plugins)) {
        foreach ($e_addons_plugins as $e_plugin) {
            if ($e_plugin['new_version']) {
                $text = 'New update available for <b>' . $e_plugin['Name'] . '</b>! <a class="button my_notice_eaddons_update" href="#my_e_addons__' . $e_plugin['TextDomain'] . '"><span class="dashicons dashicons-update"></span> UPDATE NOW!</a>';
                Utils::e_admin_notice($text, 'warning');
            }
        }
    }
    if (count($e_addons_plugins) == 1) {
        $all_addons['e-addons-for-elementor']['thumb'] = E_ADDONS_URL.'assets/img/splash.png';
    }
    ?>

    <h1 class="e_addons-title"><span class="e_addons_ic elementor-icon eicon-apps"></span> Your e-addons</h1>

    <form action="?page=e_addons" method="POST" id="e_addons_form">
        <input type="hidden" name="page" value="e_addons">
        <div class="my_e_addons<?php if (count($e_addons_plugins) == 1) { ?> my_e_addons_foreveralone<?php } ?>">
            <?php                            
                foreach ($e_addons_plugins as $e_plugin) {
                    $install_body_class = '';

                    //$status = get_option('e_addons_' . $e_plugin['TextDomain'] . '_license_status'); //
                    //$status = Edd::check_license($e_plugin);                
                    // http://localhost/wp-admin/update.php?action=upgrade-plugin&plugin=e-addons-dev%2Fe-addons-dev.php
                    ?>
                    <div class="my_e_addon<?php if (count($e_addons_plugins) == 1 && $_GET['page'] == 'e_addons') { ?> my_e_addons_foreveralone<?php } ?><?php if ($e_plugin['new_version']) { ?> my_e_addon_update<?php
                    };
                    if (!$e_plugin['active']) {
                        ?> my_e_addon_disabled<?php } ?>" id="my_e_addons__<?php echo $e_plugin['TextDomain']; ?>">
                        <div class="my_eaddon_header">
                            
                            <?php if ($e_plugin['TextDomain'] == 'e-addons-for-elementor') { ?>
                                <span class="my_e_addon_activation my_e_addon_activated my_e_addon_core"><span class="dashicons dashicons-heart"></span> <?php _e('Core', 'e-addons-for-elementor'); ?></span>
                            <?php } else { ?>
                                <span class="my_e_addon_activation my_e_addon_activated"><span class="dashicons dashicons-yes-alt"></span> <?php _e('Enabled', 'e-addons-for-elementor'); ?></span>
                                <a class="my_e_addon_activation my_e_addon_activate" href="<?php echo wp_nonce_url('plugins.php?action=activate&amp;plugin=' . urlencode($e_plugin['plugin']), 'activate-plugin_' . $e_plugin['plugin']); ?>"><span class="dashicons dashicons-insert"></span> <span class="btn-txt"><?php _e('Enable addon', 'e-addons-for-elementor'); ?></span></a>
                                <a class="my_e_addon_activation my_e_addon_deactivate e_addons-button e_addon-button-icon" href="<?php echo wp_nonce_url('plugins.php?action=deactivate&amp;plugin=' . urlencode($e_plugin['plugin']), 'deactivate-plugin_' . $e_plugin['plugin']); ?>" title="<?php _e('Deactivate', 'e-addons-for-elementor'); ?>"><span class="dashicons dashicons-remove"></span></a>                                                    
                            <?php }
                            //var_dump($e_plugin['Version']); var_dump($e_plugin['new_version']);
                            if ($e_plugin['new_version'] && version_compare($e_plugin['Version'], $e_plugin['new_version'], '<')) {
                                $install_url = '';
                                if ($e_plugin['Free']) {
                                    if (empty($e_plugin['url'])) {
                                        //var_dump($e_plugin);
                                        $e_plugin['url'] = $e_plugin["PluginURI"].'/plugins/'.$e_plugin['TextDomain'].'/';
                                    }
                                    $install_url = substr(str_replace('plugins/', 'edd/download.php?addon=', $e_plugin['url']), 0, -1);
                                } else {
                                    if (!empty($e_plugin['package'])) {
                                        $install_url = $e_plugin['package'];
                                    }
                                }                                
                                $install_body_class = ' my_eaddon_body_updatenew';
                                if ($install_url && ($e_plugin['Free'] || $e_plugin['license_status'] == 'valid')) {
                                    echo '<a class="my_e_addon_version my_e_addon_version_update" href="' . $install_url . '" target="_blank" alt="' . __('New version available', 'e-addons-for-elementor') . '"><span class="dashicons dashicons-update"></span> <span class="btn-txt">' . $e_plugin['Version'] . ' &gt; ' . $e_plugin['new_version'] . '</span></a>';
                                } else {
                                    echo '<b class="my_e_addon_version"><span class="dashicons dashicons-warning"></span> <span class="btn-txt">' . $e_plugin['Version'] . ' &gt; ' . $e_plugin['new_version'] . '</span></b>';
                                }
                            } else {
                                echo '<b class="my_e_addon_version"><span class="dashicons dashicons-saved"></span> ' . $e_plugin['Version'] . '</b>';
                            }

                            if (Utils::is_plugin_active('e-addons-manager')) { ?>
                                <a class="my_e_addon_info e_addons-button e_addon-button-icon thickbox" href="<?php echo self_admin_url('plugin-install.php?tab=plugin-information&amp;plugin=' . $e_plugin['TextDomain'] . '&amp;TB_iframe=true&amp;width=800&amp;height=600'); ?>" target="_blank" title="<?php _e('Info'); ?>"><span class="dashicons dashicons-info"></span></a>
                            <?php } else { ?>
                                <a class="my_e_addon_info e_addons-button e_addon-button-icon" href="<?php echo $e_plugin['PluginURI'] . '/plugins/' . $e_plugin['TextDomain']; ?>" target="_blank" title="<?php _e('Info'); ?>"><span class="dashicons dashicons-info"></span></a>
                            <?php } 
                            
                            if (WP_DEBUG && $e_addons->has_vendors($e_plugin['TextDomain'])) { ?>
                                <a class="my_e_addon_info e_addons-button e_addon-button-icon" href="?page=e_addons&action=vendors&plugin=<?php echo $e_plugin['TextDomain']; ?>" title="<?php _e('Update Vendors'); ?>"><span class="dashicons dashicons-update-alt"></span></a>
                            <?php } ?>
                                
                            <a class="my_e_addon_settings e_addons-button e_addon-button-icon" href="?page=e_addons_settings#e_addon_plugin_module_<?php echo $e_plugin['TextDomain']; ?>" title="<?php _e('Settings'); ?>"><span class="dashicons dashicons-admin-generic"></span></a>                            
                        </div>
                        <div class="my_eaddon_body<?php echo $install_body_class; ?>">                            
                            <?php 
                            
                            if (!empty($all_addons[$e_plugin['TextDomain']]['thumb'])) { ?>
                                <figure class="my_eaddon_figure">
                                    <img class="my_e_addon_thumb" height="auto" width="100" src="<?php echo $all_addons[$e_plugin['TextDomain']]['thumb']; ?>">
                                </figure>
                            <?php } ?>
                            <div class="my_eaddon_desc">
                                <h3 class="my_e_addon_title"><?php echo $e_plugin['Name']; ?></h3>
                                <?php /* if (defined('E_ADDONS_DEBUG') && E_ADDONS_DEBUG && !empty($e_plugin['price'])) { ?>
                                  Price: <?php echo $e_plugin['price']; ?>
                                  <a href="https://e-addons.com/?edd_action=get_version&item_name=<?php echo $e_plugin['TextDomain']; ?>" target="_blank">Info</a>
                                  <?php } */ ?>
                                <p class="my_e_addon_description"><?php echo $e_plugin['Description']; ?></p>  
                                <?php if (empty($e_plugin['Description']) && !empty($all_addons[$e_plugin['TextDomain']]['excerpt'])) { ?>
                                    <p><?php echo $all_addons[$e_plugin['TextDomain']]['excerpt']; ?></p>
                                <?php } ?>
                            </div>
                          </div>  
                            <?php /*if (count($e_addons_plugins) == 1 && $e_plugin['TextDomain'] == 'e-addons-for-elementor' && $_GET['page'] == 'e_addons') { ?>
                                <div class="my_eaddon_cta">
                                    <a href="<?php echo admin_url('admin.php?page=e_addons_settings'); ?>" class="button button-primary button-hero"><span class="dashicons dashicons-admin-generic"></span> <?php _e('Configure it', 'e-addons-for-elementor'); ?></a>
                                </div>
                            <?php }*/ ?>
                        
                        <div style="clear: both;"></div>

                        <?php
                        if (!$e_plugin['Free']) {
                            do_action('e_addons/dash/addon_license', $e_plugin);
                            $has_license = true;
                        }
                        //echo '<pre>';var_dump($e_plugin);echo '</pre>';
                        ?>
                    </div>
                    <?php
                }
            ?>
        </div>
        <input type="hidden" name="action" value="license_update">

        <?php if ($has_license) { ?>
            <div class="my_e_addon_update-actions">
                <a class="e_addons-button e_addons-button-primary my_e_addon_update my_e_addon_update-user" href="https://e-addons.com/edd/activation.php?url=<?php echo admin_url('admin.php?page=e_addons'); ?>"><span class="dashicons dashicons-admin-users"></span> Activate ALL through your account</a>
                <input class="e_addons-button e_addons-button-primary my_e_addon_update my_e_addon_update-key" type="submit" value="Save and Activate Licenses">
            </div>
        <?php } ?>
    </form>

    
    <?php
    $not_installed = array_diff_key($all_addons, $e_addons_plugins);
    do_action('e_addons/dash/more', $not_installed);    
    ?>
</div>