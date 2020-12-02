jQuery(window).load(function () {
    let iFrameDOM = jQuery("iframe#elementor-preview-iframe").contents();
    // add EDIT Template on Context Menu
    iFrameDOM.on('mousedown', '.elementor-editor-active .elementor:not(.elementor-edit-mode)', function (event) {
        if (event.which == 3) {
            let template_id = jQuery(this).data('elementor-id');
            let post_id = iFrameDOM.find('.elementor-editor-active .elementor.elementor-edit-mode').data('elementor-id');
            if (template_id && post_id) {
                setTimeout(function () {
                    let menu = jQuery('.elementor-context-menu:visible');
                    if (menu.length) {
                        menu.find('.elementor-context-menu-list__item-template').remove();
                        let edit_url = window.location.href.replace('post=' + post_id, 'post=' + template_id);
                        menu.find('.elementor-context-menu-list__item-edit').after('<div class="elementor-context-menu-list__item elementor-context-menu-list__item-template" onclick="window.open(\'' + edit_url + '\'); return false;"><div class="elementor-context-menu-list__item__icon"><i class="eicon-inner-section"></i></div><div class="elementor-context-menu-list__item__title">Edit Template</div></div>');
                    }
                }, 10);
            }
        }
    });
});
