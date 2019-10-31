<?php
/*
  Easy Sort Order with Drag'n Drop
  Premium Extension
  
  Copyright (c) 2013 - 2019 Adikon.eu
  http://www.adikon.eu/
  
  You may not copy or reuse code within this file without written permission.
*/
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-setting" id="button-save" class="btn btn-primary"><?php echo $button_save; ?></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<?php if ($links) { ?>
			<div class="btn-group manage-link">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></button>
				<ul class="dropdown-menu">
					<?php foreach ($links as $manage_link) { ?>
					<?php if ($manage_link['name']) { ?>
					<li><a href="<?php echo $manage_link['href']; ?>"><?php echo $manage_link['name']; ?></a></li>
					<?php } else { ?>
					<li class="divider"></li>
					<?php } ?>
					<?php } ?>
				</ul>
			</div>
			<?php } ?>
			<h1><?php echo $heading_title; ?></h1>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default panel-nav-tabs">
			<div class="panel-heading">
				<div class="pull-right">
					<select onChange="location.href = this.value">
						<?php foreach ($stores as $store) { ?>
						<?php if ($store['store_id'] == $filter_store_id) { ?>
						<option value="<?php echo $store['filter']; ?>" selected="selected"><?php echo $store['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $store['filter']; ?>"><?php echo $store['name']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
				<ul class="nav nav-tabs" id="general-tabs">
					<li class="active"><a href="#tab-setting" data-toggle="tab"><?php echo $tab_setting; ?></a></li>
					<li><a href="#tab-support" data-toggle="tab">Support</a></li>
				</ul>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
					<div class="tab-content">
						<div class="tab-pane active in" id="tab-setting">
							<div class="setting-name"><?php echo $caption_general; ?></div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_module_status; ?></label>
								<div class="col-sm-10">
									<select name="<?php echo $module_name; ?>[status]" class="form-control">
										<?php if ($status == 1) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label s_help"><?php echo $entry_access; ?><i><?php echo $help_access; ?></i></label>
								<div class="col-sm-10">
									<?php foreach ($users as $user) { ?>
									<?php if ($access && in_array($user['user_id'], $access)) { ?>
									<div class="checkbox">
										<label><input type="checkbox" name="<?php echo $module_name; ?>[access][]" value="<?php echo $user['user_id']; ?>" checked /> <?php echo $user['username']; ?></label>
									</div>
									<?php } else { ?>
									<div class="checkbox">
										<label><input type="checkbox" name="<?php echo $module_name; ?>[access][]" value="<?php echo $user['user_id']; ?>" /> <?php echo $user['username']; ?></label>
									</div>
									<?php } ?>
									<?php } ?>
									<?php if (isset($error['access'])) { ?>
									<div class="text-danger"><?php echo $error['access']; ?></div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label s_help"><?php echo $entry_sort; ?><i><?php echo $help_sort; ?></i></label>
								<div class="col-sm-10">
									<select name="<?php echo $module_name; ?>[sort]" class="form-control">
										<?php if ($sort == 1) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-support">
							<div class="row">
								<div class="col-sm-8 col-md-8">
									<div class="row">
										<div class="col-sm-8">
											<h4><b>You need help?</b></h4>
											<p>If you have any questions, idea or need help please feel free to contact us via ticket system</p>
										</div>
										<div class="col-sm-4 text-right">
											<a onclick="window.open('https://www.adikon.eu/login')" class="btn btn-warning btn-lg">Submit Ticket</a>
										</div>
									</div>
									<hr />
									<div class="row">
										<div class="col-sm-8">
											<h4><b>Need something customized?</b></h4>
											<p>Custom services, installations, custom theme integrations & updates and resolving conflicts with other third party extensions</p>
										</div>
										<div class="col-sm-4 text-right">
											<a onclick="window.open('http://www.adikon.eu/contact')" class="btn btn-info btn-lg">Get a Quote</a>
										</div>
									</div>
								</div>
								<div class="col-sm-4 col-md-4">
									<div class="panel-default">
										<div class="panel-body">
											<p><a onclick="window.open('http://www.adikon.eu')" class="btn-link">Official Website</a></p>
											<p><a onclick="window.open('http://www.opencart.com/index.php?route=marketplace/extension&filter_member=adikon')" class="btn-link">Our Modules</a></p>
											<p><a onclick="window.open('http://www.adikon.eu/support-i8/easy-sort-order-with-drag-and-drop-i30')" class="btn-link">Documentation</a></p>
										</div>
									</div>
								</div>
							</div>
							<script type="text/javascript">
							var mod_id = '4354';
							var domain = '<?php echo $bGljZW5zZV9kb21haW4; ?>';
							</script>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="col-sm-12 text-center">
			<br />Adikon.eu, All Rights Reserved.
		</div>
	</div>
<script type="text/javascript"><!--
<?php if ($filter_tab_show) { ?>
$('#general-tabs a[href="#tab-<?php echo $filter_tab_show; ?>"]').tab('show');
<?php } ?>

$('#button-save').on('click', function(e) {
	$('#form-setting').submit();
});
//--></script>
</div>
<?php echo $footer; ?>