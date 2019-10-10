<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-store-credit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <button type="submit" form="form-store-credit" data-toggle="tooltip" title="<?php echo $button_save_stay; ?>" input type="hidden" id="save_stay" name="save_stay" value="1" class="btn btn-info"><i class="fa fa-save"></i> <i class="fa fa-refresh"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-store-credit" class="form-horizontal">
          <ul class="nav nav-tabs" id="">
            <li class="active"><a href="#tab-settings" data-toggle="tab"><i class="fa fa-cogs"></i>&nbsp;<?php echo $tab_settings; ?></a></li>
            <li><a href="#tab-email" data-toggle="tab"><i class="fa fa-envelope-o fa-fw">&nbsp;</i><?php echo $tab_email; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-settings">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><span data-toggle="tooltip" title="<?php echo $help_status; ?>"><?php echo $entry_status; ?></span></label>
                <div class="col-sm-10">
                  <select name="buy_credit_status" id="input-status" class="form-control">
                    <?php if ($buy_credit_status) { ?>
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
                <label class="col-sm-2 control-label" for="input-send-email"><span data-toggle="tooltip" title="<?php echo $help_send_email; ?>"><?php echo $entry_send_email; ?></span></label>
                <div class="col-sm-10">
                  <select name="buy_credit_send_email" id="input-send-email" class="form-control">
                    <?php if ($buy_credit_send_email) { ?>
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
                <label class="col-sm-2 control-label" for="input-order-status"><span data-toggle="tooltip" title="<?php echo $help_order_status; ?>"><?php echo $entry_order_status; ?></span></label>
                <div class="col-sm-10">
                  <select name="buy_credit_order_status_id" id="input-order-status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $buy_credit_order_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-image"><span data-toggle="tooltip" title="<?php echo $help_image; ?>"><?php echo $entry_image; ?></span></label>
                <div class="col-sm-10">
                  <a href="" id="thumb-thumb" data-toggle="image" class="img-thumbnail"><img src="<?php echo $buy_credit_thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="buy_credit_image" value="<?php echo $buy_credit_image; ?>" id="input-image" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-default"><span data-toggle="tooltip" title="<?php echo $help_default; ?>"><?php echo $entry_default; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="buy_credit_default" value="<?php echo $buy_credit_default; ?>" placeholder="<?php echo $entry_default; ?>" id="input-default" class="form-control" />
                  <?php if ($error_default) { ?>
                  <div class="text-danger"><?php echo $error_default; ?></div>
                  <?php } ?>
                </div>
              </div>         
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-min"><span data-toggle="tooltip" title="<?php echo $help_min; ?>"><?php echo $entry_min; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="buy_credit_min" value="<?php echo $buy_credit_min; ?>" placeholder="<?php echo $entry_min; ?>" id="input-min" class="form-control" />
                  <?php if ($error_min) { ?>
                  <div class="text-danger"><?php echo $error_min; ?></div>
                  <?php } ?>
                </div>
              </div>         
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-max"><span data-toggle="tooltip" title="<?php echo $help_max; ?>"><?php echo $entry_max; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="buy_credit_max" value="<?php echo $buy_credit_max; ?>" placeholder="<?php echo $entry_max; ?>" id="input-max" class="form-control" />
                  <?php if ($error_max) { ?>
                  <div class="text-danger"><?php echo $error_max; ?></div>
                  <?php } ?>
                </div>
              </div>         
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-type"><span data-toggle="tooltip" title="<?php echo $help_type; ?>"><?php echo $entry_type; ?></span></label>
                <div class="col-sm-10">
                  <select name="buy_credit_type" id="input-type" class="form-control">
                	<option value="free" <?php if($buy_credit_type == 'free') { echo " selected"; }?> ><?php echo $text_free; ?></option>
                    <option value="fixed" <?php if($buy_credit_type == 'fixed') { echo " selected"; }?> ><?php echo $text_fixed; ?></option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-fixed"><span data-toggle="tooltip" title="<?php echo $help_fixed; ?>"><?php echo $entry_fixed; ?></span></label>
                <div class="col-sm-10">
                  <textarea name="buy_credit_fixed" rows="5" placeholder="<?php echo $entry_fixed; ?>" id="input-fixed" class="form-control"><?php echo $buy_credit_fixed; ?></textarea>
                  <?php if ($error_fixed) { ?>
                  <div class="text-danger"><?php echo $error_fixed; ?></div>
                  <?php } ?>                
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-use"><span data-toggle="tooltip" title="<?php echo $help_use_credit; ?>"><?php echo $entry_use_credit; ?></span></label>
                <div class="col-sm-10">
                  <select name="buy_credit_use" id="input-use" class="form-control">
                	<option value="never" <?php if($buy_credit_use == 'never') { echo " selected"; }?> ><?php echo $text_use_never; ?></option>
                    <option value="cart" <?php if($buy_credit_use == 'cart') { echo " selected"; }?> ><?php echo $text_use_cart; ?></option>
                  </select>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-email">
              <ul class="nav nav-tabs" id="language-email">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#tab-language-email-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="tab-language-email-<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-subject-<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_subject; ?>"><?php echo $entry_subject; ?></span></label>
                    <div class="col-sm-10">
                      <input type="text" name="buy_credit_email_subject_<?php echo $language['language_id']; ?>" value="<?php echo isset(${'buy_credit_email_subject_' . $language['language_id']}) ? ${'buy_credit_email_subject_' . $language['language_id']} : ''; ?>" placeholder="<?php echo $entry_subject; ?>" id="input-subject-<?php echo $language['language_id']; ?>" class="form-control" />
                    </div>
                  </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-email-msg-<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_message; ?>"><?php echo $entry_message; ?></span><h4><small><?php echo $help_message_desc; ?></small></h4></label>
                  <div class="col-sm-10">
                    <textarea name="buy_credit_email_msg_<?php echo $language['language_id']; ?>" placeholder="<?php echo $entry_message; ?>" id="input-email-msg-<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo !empty(${'buy_credit_email_msg_' . $language['language_id']}) ? ${'buy_credit_email_msg_' . $language['language_id']} : $send_credit_default_msg; ?></textarea>
                  </div>
                </div>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
  <link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
  <script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>  
  <script type="text/javascript"><!--
$('#language-email a:first').tab('show');
//--></script></div>
<?php echo $footer; ?>