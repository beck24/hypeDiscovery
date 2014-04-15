//<script>

	elgg.provide('elgg.discovery');

	elgg.discovery.guid = 0;

	elgg.discovery.init = function() {
		$('.discovery-popup-control').live('click', elgg.discovery.popup);

		$('.discovery-share-provider').live('click', function(e) {
			e.preventDefault();
			var $elem = $(this);
			$elem.data('guid', elgg.discovery.guid);
			elgg.action($elem.attr('href'), {
				data: $elem.data(),
				success: function(response) {
					if (response.output) {
						window.open(decodeURIComponent(response.output));
					}
				}
			});
		});
	};

	/**
	 * Trigger an elgg popup and apply the guid value of the current item
	 *
	 * @param {object} event
	 * @returns {void}
	 */
	elgg.discovery.popup = function(e) {
		var guid = $(this).data('guid');
		elgg.discovery.guid = guid;
	};

	elgg.register_hook_handler('init', 'system', elgg.discovery.init);

	//</script>