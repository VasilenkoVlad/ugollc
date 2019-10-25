<?php echo $header; ?><?php echo $column_left;?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-google-base" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-google-base" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-data-feed"><?php echo $store_circle_radius; ?>
            </label>
            <div class="col-sm-10">
              <input type="text" value="<?php echo isset( $boundry_details['store_radius'] )? $boundry_details['store_radius']:'';   ;?>" class="form-control" id="input-data-field-radius" name="store_radius">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-data-feed"><?php echo $store_latitude; ?>
            </label>            

              <div class="col-sm-10">
                <input type="text"  value="<?php echo isset( $boundry_details['latitude'] )? $boundry_details['latitude']:'';   ;?>" class="form-control" id="input-data-field-latitude" name="latitude">
              </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-data-feed"><?php echo $store_longitude; ?>
            </label>            

              <div class="col-sm-10">
                <input type="text"  value="<?php echo isset( $boundry_details['longitude'] )? $boundry_details['longitude']:'';   ;?>" class="form-control" id="input-data-field-longitude" name="longitude">
              </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if ( $boundry_details['status'] ) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>