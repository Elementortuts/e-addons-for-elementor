jQuery(window).on('elementor/frontend/init', () => {
		
	class WidgetQueryGridHandlerClass extends elementorModules.frontend.handlers.Base {
		getDefaultSettings() {
			// e-add-posts-container e-add-posts e-add-skin-grid e-add-skin-grid-masonry
			return {
				selectors: {
					container: '.e-add-posts-container',
					containerGrid: '.e-add-posts-container.e-add-skin-grid',
					containerGridMasonry: '.e-add-posts-container.e-add-skin-grid-masonry',
					
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
				$containerGrid: this.$element.find(selectors.containerGrid),
				$containerGridMasonry: this.$element.find(selectors.containerGridMasonry),
				$containerWrapper: this.$element.find(selectors.containerWrapper),
				
				$items: this.$element.find(selectors.items),
				
				//$isMasonryEnabled = false
				$masonryObject: null,
				$animationReveal: null
			};
		}

		bindEvents() {
			let scope = this.elements.$scope,
				id_scope = this.elements.$id_scope,
				elementSettings = this.getElementSettings();
			
			//if( elementSettings[EADD_skinPrefix+'grid_type'] == 'masonry' )
			//imagesLoaded(this.elements.$tems, function(){ new eadd_masonry($containerGridMasonry) });
			
			if( elementSettings[EADD_skinPrefix+'grid_type'] == 'masonry' ){
				//elementorFrontend.utils.eadd_masonry = new eadd_masonry(this.elements.$containerGridMasonry);
				this.elements.$masonryObject = new eadd_masonry(this.elements.$containerGridMasonry,id_scope);
			}
			
			//alert(this.getWidgetType());
			//console.log(elementorFrontend.elementsHandler.getHandlers());
			//console.log(elementorFrontend.getSettings());
			//console.log(elementorFrontend.utils);

			// -------------------------------------------
			if( elementSettings[EADD_skinPrefix+'scrollreveal_effect_type'] ){
				var isLive = elementSettings[EADD_skinPrefix+'scrollreveal_live'] ? false : true;
				this.elements.$animationReveal = new eadd_animationReveal( this.elements.$container, isLive );
			}

			// ---------------------------------------------
			// Funzione di callback eseguita quando avvengono le mutazioni
			/*var eAddns_MutationObserverCallback = function(mutationsList, observer) {
				for(var mutation of mutationsList) {
					if (mutation.type == 'attributes') {
					if (mutation.attributeName === 'class') {
							if (elementorFrontend.utils.eadd_masonry.isMasonryEnabled) {
								elementorFrontend.utils.eadd_masonry.layoutMasonry();
							}
						}
					}
				}
			};
			observe_eAddns_element(scope[0], eAddns_MutationObserverCallback);*/
		}
		/*
		onInit(){
			//alert('init');
		}
		*/
		/*getChangeableProperties(){
			return {
				autoplay: 'autoplay',
				pause_on_hover: 'pauseOnHover',
				pause_on_interaction: 'disableOnInteraction',
				autoplay_speed: 'delay',
				speed: 'speed',
				image_spacing_custom: 'spaceBetween'
			};
		}*/
		onElementChange(propertyName){
			//console.log(propertyName);
			let elementSettings = this.getElementSettings();

			if (EADD_skinPrefix+'grid_type' === propertyName) {
				if(  elementSettings[propertyName] != 'masonry' && this.elements.$masonryObject ){
					this.elements.$masonryObject.removeMasonry();
				}
			}

			if ( EADD_skinPrefix+'columns_grid' === propertyName ||
				EADD_skinPrefix+'row_gap' === propertyName && this.elements.$masonryObject ) {
				if(this.elements.$masonryObject)
					this.elements.$masonryObject.layoutMasonry();
			}
		}

	}
	
    const Widget_EADD_Query_grid_Handler = ($element) => {
		
        elementorFrontend.elementsHandler.addHandler(WidgetQueryGridHandlerClass, {
            $element,
        });
	};
	
	elementorFrontend.hooks.addAction('frontend/element_ready/e-query-posts.grid', Widget_EADD_Query_grid_Handler);
	elementorFrontend.hooks.addAction('frontend/element_ready/e-query-users.grid', Widget_EADD_Query_grid_Handler);
	elementorFrontend.hooks.addAction('frontend/element_ready/e-query-terms.grid', Widget_EADD_Query_grid_Handler);
	elementorFrontend.hooks.addAction('frontend/element_ready/e-query-itemslist.grid', Widget_EADD_Query_grid_Handler);
	elementorFrontend.hooks.addAction('frontend/element_ready/e-query-media.grid', Widget_EADD_Query_grid_Handler);
});