<?php if ($error_warning) { ?>
<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>
<div class="alert alert-warning" id="forbidden-alert" hidden="true"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php if ($payment_methods) { ?>
<p><?php echo $text_payment_method; ?></p>
<?php foreach ($payment_methods as $payment_method) { ?>
<?php if ($payment_method['code'] != "stripe") { ?>
<div class="radio">
  <label>
    <?php if ($payment_method['code'] == $code || !$code) { ?>
    <?php $code = $payment_method['code']; ?>
    <input type="radio" class="payment_method" name="payment_method" id="<?php echo $payment_method['code']; ?>" value="<?php echo $payment_method['code']; ?>" checked="checked" />
    <?php } else { ?>
    <input type="radio" class="payment_method" name="payment_method" id="<?php echo $payment_method['code']; ?>" value="<?php echo $payment_method['code']; ?>" />
    <?php } ?>
    <?php echo $payment_method['title']; ?>
    <?php if ($payment_method['terms']) { ?>
    (<?php echo $payment_method['terms']; ?>)
    <?php } ?>
  </label>
</div>
<?php } ?>
<?php } ?>
<div class ="radio">
  <label>
      <input type="radio" id="bama-cash" class="additional_payment_method" name="payment_method" value="<?php echo 'BAMA Cash'; ?>" />BAMA Cash
  </label>
</div>
<div class ="radio">
  <label>
    <input type="radio" id="dd" class="additional_payment_method" name="payment_method" value="<?php echo 'DD'; ?>" />Dining Dollars
  </label>
</div>  
<?php } ?>
<div id="payment_id" hidden="true">
<p><strong><?php echo "Enter your CWID#"; ?></p>
<p>
    <input type="text" value="<?php echo $payment_id; ?>"  class="payment_id" name="payment_id" class="form-control" maxlength ="19" />
</p>
</div>
<p><strong><?php echo $text_comments; ?></strong></p>
<p>
  <textarea name="comment" rows="8" class="form-control"><?php echo $comment; ?></textarea>
</p>
<?php if ($text_agree) { ?>
<div class="buttons">
  <div class="pull-right"><?php echo $text_agree; ?>
    <?php if ($agree) { ?>
    <input type="checkbox" name="agree" value="1" checked="checked" />
    <?php } else { ?>
    <input type="checkbox" name="agree" value="1" />
    <?php } ?>
    &nbsp;
    <input type="text" name="order_type" value="web" hidden> 
    <input type="button" value="<?php echo $button_continue; ?>" id="button-payment-method" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>
<?php } else { ?>
<div class="buttons">
  <div class="pull-right">
    <input type="text" name="order_type" value="web" hidden>
    <input type="button" value="<?php echo $button_continue; ?>" id="button-payment-method" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>
<?php } ?>

<!-- Custom added : Script to show unique id field on select of new additional payment option -->
<script>
$(document).ready(function(){
    $(".additional_payment_method").change(function(){
        $("#payment_id").show();
        //$(".payment_id").val("");
        $(".payment_id").attr("required", "true");
    });
  
    $(".payment_method").change(function(){
        $("#payment_id").hide();
        //$(".payment_id").val("");
    });
});
</script>
<script>
$(document).ready(function(){
    $("#dd").change(function(){
        $.ajax({
            url: 'index.php?route=checkout/payment_method/forbidden_check',
            dataType: 'json',
            type: 'get',
            data: { 
                "web_call": 1,
                "newFlow": 1
            },
            success: function(json) {
                var alert = json['error']['warning'];
                $("#forbidden-alert").show();
                $("#forbidden-alert").html(alert);
                if(alert.substr(0, 5) == "Sorry" ) {
                    $('#button-payment-method').attr("disabled", true);
                }
            }   
        });
    });
  
});
</script>

<script>
$(document).ready(function(){
    $("#bama-cash").change(function(){
        $('#button-payment-method').attr("disabled", false);
        $("#forbidden-alert").hide();
    }); 
});
</script>
<script>
$(document).ready(function(){
    $("#cod").change(function(){
        $('#button-payment-method').attr("disabled", false);
        $("#forbidden-alert").hide();
    }); 
});
</script>
<script>
$(document).ready(function(){
    $("#pp_pro").change(function(){
        $('#button-payment-method').attr("disabled", false);
        $("#forbidden-alert").hide();
    }); 
});
</script>
