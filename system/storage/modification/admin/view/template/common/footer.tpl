<footer id="footer"><?php echo $text_footer; ?><br /><?php echo $text_version; ?></footer></div>

			<script type="text/javascript"><!--
			function dragNdropPositionInvoke(obj) {
				if (typeof $.fn.dragNdrop_position === 'function') {
					obj.dragNdrop_position({route_url: '<?php echo $dragndrop_save_url; ?>', token_name: '<?php echo $token_name; ?>', php_token: '<?php echo $token_value; ?>', page: '<?php echo $page; ?>', limit: '<?php echo $limit_admin; ?>'});
				}
			}

			$('[class^="dd_sortable_list_"]').each(function (i) {
				dragNdropPositionInvoke($(this));
			});

			if ($('#extension').length > 0) {
				$(document).ajaxSuccess(function(event, xhr, settings) {
					var extension_type_url = settings.url;

					if (extension_type_url.indexOf('extension/extension/payment') != -1 || extension_type_url.indexOf('extension/extension/shipping') != -1 || extension_type_url.indexOf('extension/extension/total') != -1) {
						if (xhr.responseText.indexOf("error") <= 0) {
							dragNdropPositionInvoke($('[class^="dd_sortable_list_"]'));
						}
					}
				});
			}
			//--></script>
			
</body></html>
