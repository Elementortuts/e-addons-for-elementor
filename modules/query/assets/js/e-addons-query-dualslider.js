jQuery(window).on('elementor/frontend/init', () => {

class WidgetQueryDualSliderHandlerClass extends elementorModules.frontend.handlers.Base {
    isThumbsCarouselEnabled = false;
    getDefaultSettings() {
        // e-add-posts-container e-add-posts e-add-skin-grid e-add-skin-grid-masonry
        return {
            selectors: {
                container: '.e-add-posts-container',
                containerCarousel: '.e-add-posts-container.e-add-skin-dualslider',
                thumbsCarousel: '.e-add-dualslider-gallery-thumbs',

                containerWrapper: '.e-add-posts-wrapper',
                items: '.e-add-post-item',
            },
        };
    }
    
    getDefaultElements() {
        const selectors = this.getSettings('selectors');

        return {
            $scope: this.$element,
            $id_scope: this.getID(),

            $container: this.$element.find(selectors.container),
            $containerCarousel: this.$element.find(selectors.containerCarousel),
            $thumbsCarousel: this.$element.find(selectors.thumbsCarousel),

            $containerWrapper: this.$element.find(selectors.containerWrapper),
            
            $items: this.$element.find(selectors.items),
            
            $animationReveal: null
        };
    }

    bindEvents() {
        let scope = this.elements.$scope,
            id_scope = this.elements.$id_scope,
            elementSettings = this.getElementSettings(),
            widgetType = this.getWidgetType(),
            galleryThumbs = null;

        if(galleryThumbs) galleryThumbs.destroy();
        galleryThumbs = new Swiper( this.elements.$thumbsCarousel[0], this.thumbCarouselOptions( id_scope, elementSettings ) );

        this.elements.$scope.data('thumbscarousel', galleryThumbs);

        //alert(widgetType+'.carousel');
        //Widget_EADD_Query_carousel_Handler();
        //elementorFrontend.elementsHandler.runReadyTrigger(jQUery(widgetType+'.carousel'));

        elementorFrontend.hooks.doAction('frontend/element_ready/'+widgetType+'.carousel', scope);
            
    }
    /*
    onInit(){
        //alert('init');
    }
    */
    
    onElementChange(propertyName){
       
    }

    thumbCarouselOptions( id_scope, elementSettings ){ 
        
        var slidesPerView = Number(elementSettings[EADD_skinPrefix+'thumbnails_slidesPerView']);
        var elementorBreakpoints = elementorFrontend.config.breakpoints;
        
        var eaddSwiperOptions = {
            spaceBetween: Number(elementSettings[EADD_skinPrefix+'dualslider_gap']) || 0,
            slidesPerView: slidesPerView || 'auto',
            //freeMode: true,
            
            autoHeight: true,
            //watchOverflow: true,
            
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
            
            // centeredSlides: true,
            loop: true,
            
            // loopedSlides: 4

            on: {
                init: function () {
                    this.isThumbsCarouselEnabled = true;    
                },
                
            }
        };
        
        var responsivePoints = eaddSwiperOptions.breakpoints = {};
        responsivePoints[elementorBreakpoints.lg] = {
            slidesPerView: Number(elementSettings[EADD_skinPrefix+'thumbnails_slidesPerView']) || 'auto',
            spaceBetween: Number(elementSettings[EADD_skinPrefix+'dualslider_gap']) || 0,
        };
        responsivePoints[elementorBreakpoints.md] = {
            slidesPerView: Number(elementSettings[EADD_skinPrefix+'thumbnails_slidesPerView_tablet']) || Number(elementSettings[EADD_skinPrefix+'thumbnails_slidesPerView']) || 'auto',
            spaceBetween: Number(elementSettings[EADD_skinPrefix+'dualslider_gap_tablet']) || Number(elementSettings[EADD_skinPrefix+'dualslider_gap']) || 0,
        };
        responsivePoints[elementorBreakpoints.xs] = {
            slidesPerView: Number(elementSettings[EADD_skinPrefix+'thumbnails_slidesPerView_mobile']) || Number(elementSettings[EADD_skinPrefix+'thumbnails_slidesPerView_tablet']) || Number(elementSettings[EADD_skinPrefix+'thumbnails_slidesPerView']) || 'auto',
            spaceBetween: Number(elementSettings[EADD_skinPrefix+'dualslider_gap_mobile']) || Number(elementSettings[EADD_skinPrefix+'dualslider_gap_tablet']) || Number(elementSettings[EADD_skinPrefix+'spaceBetween']) || 0,
        };
        eaddSwiperOptions = jQuery.extend(eaddSwiperOptions, responsivePoints);

        return eaddSwiperOptions;
    }
}

    const Widget_EADD_Query_dualslider_Handler = ($element) => {
       
        elementorFrontend.elementsHandler.addHandler(WidgetQueryDualSliderHandlerClass, {
            $element,
        });

    };

    elementorFrontend.hooks.addAction('frontend/element_ready/e-query-posts.dualslider', Widget_EADD_Query_dualslider_Handler);
	elementorFrontend.hooks.addAction('frontend/element_ready/e-query-users.dualslider', Widget_EADD_Query_dualslider_Handler);
	elementorFrontend.hooks.addAction('frontend/element_ready/e-query-terms.dualslider', Widget_EADD_Query_dualslider_Handler);
    elementorFrontend.hooks.addAction('frontend/element_ready/e-query-itemslist.dualslider', Widget_EADD_Query_dualslider_Handler);
    elementorFrontend.hooks.addAction('frontend/element_ready/e-query-media.dualslider', Widget_EADD_Query_dualslider_Handler);
});