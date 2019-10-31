(function($){
	$.fn.dragNdrop_position = function(options) {
		var defaults = {
			route_url : '',
			token_name : '',
			php_token : '',
			page : '0',
			limit : '0',
			routes : [
				'catalog/category',
				'catalog/product',
				'catalog/product/insert',
				'catalog/product/add',
				'catalog/product/update',
				'catalog/product/edit',
				'catalog/filter',
				'catalog/filter/insert',
				'catalog/filter/add',
				'catalog/filter/update',
				'catalog/filter/edit',
				'catalog/attribute_group',
				'catalog/attribute',
				'catalog/option',
				'catalog/manufacturer',
				'catalog/information',
				'extension/extension',
				'marketplace/extension',
				'extension/shipping',
				'extension/payment',
				'extension/total',
				'customer/customer_group',
				'sale/customer_group',
				'design/banner/update',
				'design/banner/edit',
				'design/layout/edit',
				'localisation/language'
			],
			actions : [
				['category', true, 'category_id', 4, '> tr:has(td)'],
				['product', true, 'product_id', false, '> tr:has(td)'],
				['product_insert', false, false, false, '> tr:has(td)'],
				['product_insert', false, false, false, '> tr:has(td)'],
				['product_update', false, false, false, 'tbody'],
				['product_update', false, false, false, '> tr:has(td)'],
				['filter', true, 'filter_group_id', 4, '> tr:has(td)'],
				['filter_insert', false, false, false, '> tr:has(td)'],
				['filter_insert', false, false, false, '> tr:has(td)'],
				['filter_update', false, false, false, 'tbody'],
				['filter_update', false, false, false, '> tr:has(td)'],
				['attribute_group', true, 'attribute_group_id', 4, '> tr:has(td)'],
				['attribute', true, 'attribute_id', 5, '> tr:has(td)'],
				['option', true, 'option_id', 4, '> tr:has(td)'],
				['manufacturer', true, 'manufacturer_id', 4, '> tr:has(td)'],
				['information', true, 'information_id', 4, '> tr:has(td)'],
				['extension', true, 'extension', 4, '> tr:has(td)'],
				['marketplace', true, 'extension', 4, '> tr:has(td)'],
				['shipping', true, 'extension', 4, '> tr:has(td)'],
				['payment', true, 'extension', 5, '> tr:has(td)'],
				['total', true, 'extension', 4, '> tr:has(td)'],
				['customer_group', true, 'customer_group_id', 4, '> tr:has(td)'],
				['customer_group', true, 'customer_group_id', 4, '> tr:has(td)'],
				['banner_update', false, false, false, 'tbody'],
				['banner_update', false, false, false, '> tr:has(td)'],
				['layout_update', false, false, false, '> tr:has(td)'],
				['language', true, 'language_id', 5, '> tr:has(td)']
			]
		}

		Array.prototype.indexOf = function(obj, start) {
			for (var i = (start || 0), j = this.length; i < j; i++) {
				if (this[i] === obj) { return i; }
			}

			return -1;
		}

		var getParmFromUrl = function(parm, url) {
			var re = new RegExp(".*[?&]" + parm + "=([^&']+)(&|$|')");

			if (typeof url == 'undefined') url = window.location.href;

			var match = url.match(re);

			return (match ? match[1] : '');
		}

		var createCss = function() {
			var css = document.createElement('style');
			css.type = 'text/css';

			var styles = '\
				td.ddp_drag { width: 2%; text-align: center; }\
				td.ddp_drag img { cursor: move; }\
				.ui-sortable-placeholder td {\
					border: 1px dashed #aaa !important;\
					height: 45px;\
					width: 344px;\
					background: #999 !important; }\
				.ui-sortable-helper { border: 1px dashed #aaa !important; }\
				tbody.ui-sortable-placeholder { height: 130px; display: table-row; }\
				#tab-attribute tbody.ui-sortable-placeholder { height: 120px; }\
				#tab-option tbody.ui-sortable-placeholder { height: 60px }\
				#tab-image tbody.ui-sortable-placeholder { height: 130px; }';

			if (css.styleSheet) css.styleSheet.cssText = styles;
			else css.appendChild(document.createTextNode(styles));

			document.getElementsByTagName("head")[0].appendChild(css);
		}

		var addHand = function() {
			$('[class^="dd_sortable_list_"]').each(function (i) {
				/*$(this).children('tr').filter(function() {
					return !$(this).find('td:first').hasClass('ddp_drag');
				}).prepend('<td class="ddp_drag"><img src="view/javascript/DragNDropPosition/image/ddp_drag_on.png" alt="Drag" title="Drag" /></td>').parents('table').find('thead > tr').filter(function() {
					return !$(this).find('td:first').hasClass('ddp_drag');
				}).prepend('<td class="ddp_drag">&nbsp;</td>');*/

				if ($(this).get(0).nodeName.toLowerCase() == 'table') {
					var branch = $(this);
				} else {
					var branch = $(this).parents('table');
				}

				branch.find('tr').filter(function() {
					if (!$(this).find('td:first').hasClass('ddp_drag')) {
						if ($(this).parent().get(0).nodeName.toLowerCase() == 'tbody') {
							$(this).prepend('<td class="ddp_drag"><img src="view/javascript/DragNDropPosition/image/ddp_drag_on.png" alt="Drag" title="Drag" /></td>');
						} else {
							$(this).prepend('<td class="ddp_drag">&nbsp;</td>');
						}
					} else {
						return false;
					}
				});
			});
		}

		function Plugin(element, options) {
			this.options = $.extend(defaults, options);

			this.obj = $(element);

			this.init();
		}

		Plugin.prototype.init = function () {
			var obj = this.obj,
				token_name = this.options.token_name;
				token_value = this.options.php_token;

			route = getParmFromUrl('route');

			if (!$.inArray(route, this.routes))
				return;

			key = this.options.routes.indexOf(route);

			if (key == -1)
				return;

			ajax_url = this.options.route_url;
			action = this.options.actions[key];
			start = this.options.page == 0 || this.options.page == 1 ? 0 : (this.options.page * this.options.limit);

			$(obj).sortable({
				items: action[4],
				connectWith: 'tbody',
				handle: ".ddp_drag",
				placeholder: 'ui-sortable-placeholder',
				cursor: 'move',
				axis: 'y',
				disable: true,
				forcePlaceholderSize: true,
				helper: function(e, ui) {
					ui.find('td').each(function() {
						$(this).width($(this).width());
					});

					//Quick Product Edit
					if ($('tr[id*="quick"]').length) {
						$('tr[id*="quick"]').remove();
					}
					
					return ui;
				},
				stop: function(event, ui) {
					order = {};

					if ($(this).children().find('input[name$="[sort_order]"]').length > 0) {
						$('input[name$="[sort_order]"]', $(obj)).each(function(i) {
							$(this).val(i);
						});
					} else {
						$(this).children('tr').map(function(index, obj) {
							id = getParmFromUrl(action[2], $(this).find('a').last().attr('href'));

							if (id == '') {
								id = getParmFromUrl(action[2], $('a:nth-last-child(2)', $(this)).attr('href'));

								if (id == '') {
									id = getParmFromUrl(action[2], $('a:nth-last-child(2)', $(this)).attr('onclick'));
								}
							}

							if (action[3]) $(this).find('td:nth-child(' + action[3] + ')').text(start + index);

							if (id) {
								return order[id] = (start + index);
							}
						});
						
					}

					if (order && action[1]) {
						module = action[0];

						if (route == 'extension/extension' || route == 'marketplace/extension') {
							route2 = getParmFromUrl('route', $('select[name="type"]').val());

							var tmp = route2.split("/");

							module = tmp.pop();
						}

						$.ajax({
							url: 'index.php?route=' + ajax_url + '&' + token_name + '=' + token_value,
							type: 'post',
							dataType: 'json',
							data: { module: module, order: order },
							beforeSend: function() {
								$('.ddp').remove();
							},
							success: function(data) {
								if (data['error']) {
									if ($('div.content').length) {
										$('div.content').first().prepend('<div class="ddp warning">' + data['error'] + '</div>');
									} else {
										ui.item.parents('div.container-fluid').prepend('<div class="ddp alert alert-danger">' + data['error'] + '</div>');
									}
								}

								if (data['success']) {
									if ($('div.content').length) {
										$('div.content').first().prepend('<div class="ddp success">' + data['success'] + '</div>');
									} else {
										ui.item.parents('div.container-fluid').prepend('<div class="ddp alert alert-success">' + data['success'] + '</div>');
									}
								}
							}
						});
					}	
				}
			});
		};

		createCss();
		addHand();

		return this.each(function () {
            new Plugin(this, options);
        });
	};
})(jQuery);