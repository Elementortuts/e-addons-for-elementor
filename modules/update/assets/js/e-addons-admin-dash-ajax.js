jQuery(document).ready(function () {
    jQuery(document).on('click', '.my_e_addon_install', function () {
        jQuery(this).children('.dashicons-download').addClass('dashicons-update').addClass('spin').removeClass('dashicons-download');
        jQuery(this).children('.btn-txt').text('INSTALLING...please wait');
        jQuery.ajax({
            url: window.location.href,
            dataType: "html",
            context: jQuery(this),
            type: "POST",
            data: {"url": jQuery(this).attr('href'), "action": 'add'},
            error: function () {
                console.log("error");
                window.location.href = jQuery(this).attr('href');
            },
            success: function (data, status, xhr) {
                //console.log(data);
                jQuery('#e_addons_form').html(jQuery(data).find('#e_addons_form').html());
                jQuery(this).closest('.my_e_addon').fadeOut();
                jQuery('#adminmenu .dashicons-warning.e-count ').fadeOut();
            },
        });
        return false;
    });

    jQuery(document).on('click', '.my_e_addon_version_update', function () {
        jQuery(this).children('.dashicons-update').addClass('spin');
        jQuery(this).children('.btn-txt').text('UPDATING...');
        jQuery.ajax({
            url: window.location.href,
            dataType: "html",
            context: jQuery(this),
            type: "POST",
            data: {"url": jQuery(this).attr('href'), "action": 'update', "addon": jQuery(this).data('addon')},
            error: function () {
                console.log("error");
                window.location.href = jQuery(this).attr('href');
            },
            success: function (data, status, xhr) {
                //console.log(data);
                jQuery('#e_addons_form').html(jQuery(data).find('#e_addons_form').html());
            },
        });
        return false;
    });
    
    jQuery(document).on('click', '.my_e_addon_license_close, .my_e_addon_license_closed', function () {   
        if (!jQuery(this).hasClass('my_e_addon_license_closed_no')) {
            jQuery(this).closest('.my_e_addon').find('.my_e_addon_license').toggle();
            jQuery(this).closest('.my_e_addon').find('.my_e_addon_license_closed').toggle();
            return false;
        }        
    });
    
    jQuery(document).on('click', '.my_notice_eaddons_update', function () {        
        //console.log(jQuery(this).attr('href')+' .my_e_addon_version_update');
        
        jQuery(this).children('.dashicons').addClass('spin');
        jQuery(jQuery(this).attr('href')+' .my_e_addon_version_update').trigger('click');
        
        /*if (jQuery(jQuery(this).attr('href')).length) {
            if (jQuery(jQuery(this).attr('href')).offset().top) {
                jQuery('html, body').animate({
                    scrollTop: jQuery(jQuery(this).attr('href')).offset().top - jQuery('#wpadminbar').height()
                }, 500);
            }
        }*/
        jQuery(this).closest('.notice').delay(2000).fadeOut();
        return false;
    });
    
});