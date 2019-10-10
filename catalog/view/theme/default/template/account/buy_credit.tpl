<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <p><?php echo $text_description; ?></p>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
       <div class="form-group">
          <label class="col-sm-2 control-label" for="input-amount"><span data-toggle="tooltip" data-html="true" title="<?php echo $help_amount; ?>"><?php echo $entry_amount; ?></span></label>
          <div class="col-sm-10">
            <?php if ($buy_credit_type == 'free') { ?>
            <input type="text" name="amount" value="<?php echo $amount; ?>" id="input-amount" class="form-control" size="5" />
            <?php if ($error_amount) { ?>
            <div class="text-danger"><?php echo $error_amount; ?></div>
            <?php } ?>
            <?php } else { ?>
            <select name="amount" class="form-control">
            <?php foreach ($amounts as $amount): ?>
            <option value="<?php echo $amount['amount'] ?>"><?php echo $amount['price'] ?></option>
            <?php endforeach; ?>
            <?php } ?>
            </select>
          </div>
        </div>
        <div class="buttons clearfix">
          <div class="pull-right">
              I have read and agree with the <a href="#" data-toggle="modal" data-target="#basicModal"> Terms & Conditions </a>
            <?php if ($agree) { ?>
            <input type="checkbox" name="agree" value="1" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="agree" value="1" />
            <?php } ?>
          </div>
        </div>
        <div class="buttons clearfix">
          <div class="pull-left"><a href="<?php echo $continue; ?>" class="btn btn-default"><?php echo $button_shopping; ?></a></div>
          <div class="pull-right"><input type="submit" value="<?php echo $button_buy; ?>" class="btn btn-primary" /></div>
        </div>
      </form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>

<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Terms & conditions for purchasing UGO credit</h4>
      </div>
      <div class="modal-body">
        <?php echo $text_agree; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>