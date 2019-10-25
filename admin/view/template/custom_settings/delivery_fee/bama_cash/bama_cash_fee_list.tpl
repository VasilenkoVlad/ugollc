<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
      <div class="pull-right"><button class="btn btn-primary" data-original-title="Add new delivery fee" id="add_new_fee"><i class="fa fa-plus"></i></button></div>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } else if ($success) { ?>
     <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="alert alert-success" hidden="" id='customSuccess'>yttyt
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <div class="alert alert-danger" hidden="" id='customError'>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
    </div>
    <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="tab_logic">
              <thead>
                <tr>
                  <td class="text-right"><?php echo $column_cart_amount_range_type; ?></td>
                  <td class="text-right"><?php echo $column_cart_amount_range_1; ?></td>
                  <td class="text-right"><?php echo $column_cart_amount_range_2; ?></td>
                  <td class="text-right"><?php echo $column_fee_type; ?></td>
                  <td class="text-right"><?php echo  $column_delivery_fee; ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if (isset($cod)) { ?>
                    <?php foreach ($cod as $cd) { ?>  
                        <tr id=<?php echo 'delivery_fee_'.$cd['delivery_fee_id']; ?>>
                            <form name=<?php echo 'edit_fee_'.$cd['delivery_fee_id']; ?> method="post">
                                <td class="text-right">
                                    <select name='range_type' id=<?php echo 'rangeType_'.$cd['delivery_fee_id']; ?> required="">
                                        <option  <?php if ($cd['cart_amount_range_criteria'] == 'below'){ echo 'selected'; } ?> value='below'>Below</option>
                                        <option <?php if ($cd['cart_amount_range_criteria'] == 'above'){ echo 'selected'; } ?> value = 'above'>Above</option>
                                        <option <?php if ($cd['cart_amount_range_criteria'] == 'in_between'){ echo 'selected'; } ?> value = 'in_between'>In Between</option>
                                    </select>
                                </td>
                                <td class="text-right"><input type = "text" name="cart_amount_1" id=<?php echo 'cartAmount1_'.$cd['delivery_fee_id']?> maxlength = "5" value="<?php echo $cd['cart_amount1']; ?>"  required=""></td>
                                <td class="text-right"><input type = "text" name="cart_amount_2" id=<?php echo 'cartAmount2_'.$cd['delivery_fee_id']?> maxlength = "5" value="<?php echo $cd['cart_amount2']; ?>" ></td>
                                <td class="text-left">
                                    <select name="fee_type" id=<?php echo 'feeType_'.$cd['delivery_fee_id']; ?> required="">
                                        <option <?php if ($cd['fee_type'] == 'P'){ echo 'selected'; } ?> value="F"><?php echo $text_fix_amount; ?></option>
                                        <option <?php if ($cd['fee_type'] == 'P'){ echo 'selected'; } ?> value="P"><?php echo $text_percent; ?></option>
                                    </select>
                                </td>
                                <td class="text-left"><input type = "text" name="basic_fee" id=<?php echo 'basicFee_'.$cd['delivery_fee_id']; ?> maxlength = "5" value = "<?php echo $cd['basic_fee']; ?>" required=""></td>
                                <input type='hidden' value="<?php echo $cd['speedy_delivery_fee']; ?>" name="speedy_delivery_fee" id="<?php echo 'speedyDeliveryFee_'.$cd['delivery_fee_id']; ?>" >
                                <input type='hidden' value="<?php echo $cd['edit']; ?>" name="edit_url" id="<?php echo 'edit_'.$cd['delivery_fee_id']; ?>" >
                                <td class="text-right"><button type="button" id=<?php echo $cd['delivery_fee_id']; ?> class="btn btn-success" data-toggle="tooltip" title="Save" onclick="editFee(this.id)"><i class="fa fa-save"></i></button> <a href="<?php echo $cd['edit_detail']; ?>" data-toggle="tooltip" title="Edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a> <a href="<?php echo $cd['delete']; ?>" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>
                          </form> 
                        </tr>
                    <?php  } ?>
                <?php } ?>
              </tbody>
            </table>
          </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
    </div>
    <div class="alert alert-success" hidden="" id='customRangeSuccess'>yttyt
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <div class="alert alert-danger" hidden="" id='customRangeError'>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
          <h3 class="panel-title" style="margin-top: 13px"><i class="fa fa-list"></i> <?php echo "Delivery Range Fee"; ?></h3>
        <div class="pull-right"><button class="btn btn-primary" data-original-title="Add new range fee" id="add_new_range_fee"><i class="fa fa-plus"></i></button></div>
      </div>
    </div>
     <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="tab_logic2">
              <thead>
                <tr>
                  <td class="text-right"><?php echo $range_type; ?></td>
                  <td class="text-right"><?php echo $column_range_1; ?></td>
                  <td class="text-right"><?php echo $column_range_2; ?></td>
                  <td class="text-right"><?php echo  $column_delivery_fee; ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                   <?php if (isset($delivery_range_fee)) { ?>
                    <?php foreach ($delivery_range_fee as $drf) { ?>  
                     <tr id=<?php echo 'delivery_range_fee_'.$drf['delivery_range_fee_id']; ?>>
                            <form name=<?php echo 'edit_range_fee_'.$drf['delivery_range_fee_id']; ?> method="post">
                            <td class="text-right">
                                <select name='range_type' id=<?php echo 'range_Type_'.$drf['delivery_range_fee_id']; ?> required="">
                                    <option <?php if ($drf['range_type'] == 'below'){ echo 'selected'; } ?> value='below'>Within</option>
                                    <option <?php if ($drf['range_type'] == 'above'){ echo 'selected'; } ?> value = 'above'>Beyond</option>
                                    <option <?php if ($drf['range_type'] == 'in_between'){ echo 'selected'; } ?> value = 'in_between'>In Between</option>
                                </select>
                            </td>
                            <td class="text-right"><input type = "text" name="range_1" id=<?php echo 'range1_'.$drf['delivery_range_fee_id']?> maxlength = "3" value="<?php echo $drf['range_1']; ?>"  required=""></td>
                            <td class="text-right"><input type = "text" name="range_2" id=<?php echo 'range2_'.$drf['delivery_range_fee_id']?> maxlength = "3" value="<?php echo $drf['range_2']; ?>" ></td>
                            <td class="text-left"><input type = "text" name="fee" id=<?php echo 'fee_'.$drf['delivery_range_fee_id']; ?> maxlength = "5" value = "<?php echo $drf['fee']; ?>" required=""></td>
                            <input type='hidden' value="<?php echo $drf['edit_range']; ?>" name="edit_url" id="<?php echo 'edit_range'.$drf['delivery_range_fee_id']; ?>" >
                            <td class="text-right"><button type="button" id='<?php echo $drf['delivery_range_fee_id']; ?>' class='btn btn-success' data-toggle="tooltip" title="Save" onclick="editRangeFee(this.id)"><i class="fa fa-save"></i></button> <a href="<?php echo $drf['delete_range']; ?>" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>
                      </form> 
                    </tr>
                    <?php } ?>
                   <?php } ?>
              </tbody>
            </table>
          </div>
      </div>
  </div>
</div>
    </div>
  </div>
</div>
<?php echo $footer; ?>

<script type="text/javascript">
$(document).ready(function() {
    $("#add_new_fee").click(function() {
    $('tr').find('input').prop('disabled',true)
    $('#tab_logic').append('<tr id="delivery_fee_'+0+'"></tr>');
    $('#delivery_fee_' + 0).html("<form name='add_fee_"+ 0 +"' method='post'><td class='text-right'><select name='range_type' id='rangeType_"+0+"' required=''><option value='below'>Below</option><option value = 'above'>Above</option><option value = 'in_between'>In Between</option></select></td><td class='text-right'><input type = 'text' name='cart_amount_1' id='cartAmount1_"+0+"' maxlength = '5' required=''></td><td class='text-right'><input type = 'text' name='cart_amount_2' id='cartAmount2_"+0+"' maxlength = '5'></td><td class='text-left'><select name='fee_type' id='feeType_"+0+"' required=''><option value='F'><?php echo $text_fix_amount; ?></option><option value='P'><?php echo $text_percent; ?></option></select></td><td class='text-left'><input type = 'text' name='basic_fee' id='basicFee_"+0+"' maxlength = '5' required=''></td><input type='hidden' value='<?php echo $add; ?>' name='add_url' id='add_"+0+"'><td class='text-right'><button type='button' data-toggle='tooltip' title='Save' id='"+ 0 +"' class='btn btn-success' onclick='addFee(this.id)'><i class='fa fa-save'></i></button> <a href='<?php echo $cd['edit']; ?>' id='edit_"+0+"' data-toggle='tooltip' title='<?php echo $button_edit; ?>' class='btn btn-primary' disabled='true'><i class='fa fa-pencil'></i></a> <a href='<?php echo $cd['delete']; ?>' data-toggle='tooltip' title='Delete' class='btn btn-danger' id= 'delete_"+0+"' disabled='true'><i class='fa fa-trash'></i></a></td></form>");
  });
    $("#add_new_range_fee").click(function() {
    $('tr').find('input').prop('disabled',true)
    $('#tab_logic2').append('<tr id="delivery_range_fee_'+0+'"></tr>');
    $('#delivery_range_fee_' + 0).html("<form name='add_range_fee_"+0+"' method='post'><td class='text-right'><select name='range_type' id='range_Type_"+0+"' required=''><option value='below'>Within</option><option value ='above'>Beyond</option><option value='in_between'>In Between</option></select></td><td class='text-right'><input type = 'text' name='range_1' id='range1_"+0+"' maxlength = '3' required=''></td><td class='text-right'><input type = 'text' name='range_2' id='range2_"+0+"' maxlength = '3'></td><td class='text-left'><input type = 'text' name='fee' id='fee_"+0+"' maxlength = '5' required=''></td><input type='hidden' value='<?php echo $add_range; ?>' name='add_range_url' id='add_range_"+0+"'><td class='text-right'><button type='button' data-toggle='tooltip' title='Save' id='"+0+"' class='btn btn-success' onclick='addRangeFee(this.id)'><i class='fa fa-save'></i></button> <a href='<?php echo $drf['delete']; ?>' data-toggle='tooltip' title='Delete' class='btn btn-danger' id= 'delete_"+0+"' disabled='true'><i class='fa fa-trash'></i></a></td></form>");
   });
});


function editFee(id) {
  var cart_amount_1       = $('#cartAmount1_'+id).val();
  var cart_amount_2       = $('#cartAmount2_'+id).val();
  var range_type          = $('#rangeType_'+id).val();
  var fee_type            = $('#feeType_'+id).val();
  var basic_fee           = $('#basicFee_'+id).val();
  var speedy_delivery_fee = $('#speedyDeliveryFee_'+id).val();
  var edit_url            = $('#edit_'+id).val();
  if(cart_amount_1 == "") {
      $('#customSuccess').hide();
        $('#customError').show();
        $('#customError').html("Enter Cart Amount 1 <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customError").fadeOut(3000);
    }else if(range_type == "in_between" && cart_amount_2 == ""){
        $('#customSuccess').hide();
        $('#customError').show();
        $('#customError').html("Enter Cart Amount 2 <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customError").fadeOut(3000);
    }else if((range_type == "below" || range_type == "above") && cart_amount_2 != ""){
        $('#customSuccess').hide();
        $('#customError').show();
        $('#customError').html("Cart amount 2 must be empty <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customError").fadeOut(3000);
    } else if(parseFloat(cart_amount_1) >= parseFloat(cart_amount_2)){
        $('#customSuccess').hide();
        $('#customError').show();
        $('#customError').html("Invalid cart amount 2 <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customError").fadeOut(3000);
    }else if(basic_fee == ""){
        $('#customSuccess').hide();
        $('#customError').show();
        $('#customError').html("Enter Basic Fee <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customError").fadeOut(3000);
    }else {
        var postData = {
                "cart_amount_1":cart_amount_1,
                "cart_amount_2":cart_amount_2,
                "range_type" : range_type,
                "basic_fee" :basic_fee,
                "fee_type" : fee_type
            };
        var k = postData['cart_amount_1'];
        if(edit_url != ""){
            $.ajax({
                url: edit_url, 
                data:postData,
                type: "POST", 
                dataType: 'json',
                success: function(result) {
                    $('#customError').hide();
                    $('#customSuccess').show();
                    $('#customSuccess').html("Delivery Fee saved successfully. <button type='button' class='close' data-dismiss='alert'>&times;</button>");
                    $("#customSuccess").fadeOut(3000);
                },
                error:function(e){
                    $('#customError').hide();
                    $('#customSuccess').show();
                    $('#customSuccess').html("Delivery Fee saved successfully. <button type='button' class='close' data-dismiss='alert'>&times;</button>");
                    $("#customSuccess").fadeOut(3000);
              
                }
            }); 
        } 
    }
    return false;
} 

function editRangeFee(id) {
  var range_1       = $('#range1_'+id).val();
  var range_2       = $('#range2_'+id).val();
  var range_type    = $('#range_Type_'+id).val();
  var fee           = $('#fee_'+id).val();
  var edit_url      = $('#edit_range'+id).val();
  if(range_1 == "") {
      $('#customRangeSuccess').hide();
        $('#customRangeError').show();
        $('#customRangeError').html("Enter Range 1 <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customRangeError").fadeOut(3000);
    }else if(range_type == "in_between" && range_2 == ""){
        $('#customRangeSuccess').hide();
        $('#customRangeError').show();
        $('#customRangeError').html("Enter Range 2 <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customRangeError").fadeOut(3000);
    }else if((range_type == "below" || range_type == "above") && range_2 != ""){
        $('#customRangeSuccess').hide();
        $('#customRangeError').show();
        $('#customRangeError').html("Range 2 must be empty <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customRangeError").fadeOut(3000);
    } else if(parseFloat(range_1) >= parseFloat(range_2)){
        $('#customRangeSuccess').hide();
        $('#customRangeError').show();
        $('#customRangeError').html("Invalid range 2 <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customRangeError").fadeOut(3000);
    }else if(fee == ""){
        $('#customRangeSuccess').hide();
        $('#customRangeError').show();
        $('#customRangeError').html("Enter Fee <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customRangeError").fadeOut(3000);
    }else {
        var postData = {
                "range_1":range_1,
                "range_2":range_2,
                "range_type" : range_type,
                "fee" : fee,
                "payment_type_id" : 3
            };
        if(edit_url != ""){
            $.ajax({
                url: edit_url, 
                data:postData,
                type: "POST", 
                dataType: 'json',
                success: function(result) {
                    $('#customRangeError').hide();
                    $('#customRangeSuccess').show();
                    $('#customRangeSuccess').html("Delivery Range Fee saved successfully. <button type='button' class='close' data-dismiss='alert'>&times;</button>");
                    $("#customRangeSuccess").fadeOut(3000);
                },
                error:function(e){
                    $('#customRangeError').hide();
                    $('#customRangeSuccess').show();
                    $('#customRangeSuccess').html("Delivery Range Fee saved successfully. <button type='button' class='close' data-dismiss='alert'>&times;</button>");
                    $("#customRangeSuccess").fadeOut(3000);
              
                }
            }); 
        } 
    }
    return false;
} 

function addRangeFee(id) {
  var range_1       = $('#range1_'+id).val();
  var range_2       = $('#range2_'+id).val();
  var range_type    = $('#range_Type_'+id).val();
  var fee           = $('#fee_'+id).val();
  var add_range_url = $('#add_range_'+id).val();
  $('#'+id).attr("disabled", true); 
 if(range_1 == "") {
      $('#customRangeSuccess').hide();
        $('#customRangeError').show();
        $('#customRangeError').html("Enter Range 1 <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customRangeError").fadeOut(3000);
        $('#'+id).attr("disabled", false);
    }else if(range_type == "in_between" && range_2 == ""){
        $('#customRangeSuccess').hide();
        $('#customRangeError').show();
        $('#customRangeError').html("Enter Range 2 <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customRangeError").fadeOut(3000);
        $('#'+id).attr("disabled", false);
    }else if((range_type == "below" || range_type == "above") && range_2 != ""){
        $('#customRangeSuccess').hide();
        $('#customRangeError').show();
        $('#customRangeError').html("Range 2 must be empty <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customRangeError").fadeOut(3000);
        $('#'+id).attr("disabled", false);
    } else if(parseFloat(range_1) >= parseFloat(range_2)){
        $('#customRangeSuccess').hide();
        $('#customRangeError').show();
        $('#customRangeError').html("Invalid range 2 <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customRangeError").fadeOut(3000);
        $('#'+id).attr("disabled", false);
    }else if(fee == ""){
        $('#customRangeSuccess').hide();
        $('#customRangeError').show();
        $('#customRangeError').html("Enter Fee <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customRangeError").fadeOut(3000);
        $('#'+id).attr("disabled", false);
    }else {
        var postData = {
                "range_1":range_1,
                "range_2":range_2,
                "range_type" : range_type,
                "fee" : fee,
                "payment_method_id" : 3
            };
        if(add_range_url != ""){
            $.ajax({
                url: add_range_url, 
                data:postData,
                type: "POST", 
                dataType: 'json',
                success: function(result) {
                    $('#customRangeError').hide();
                    $('#customRangeSuccess').show();
                    $('#customRangeSuccess').html("Delivery Range Fee saved successfully. <button type='button' class='close' data-dismiss='alert'>&times;</button>");
                    $("#customRangeSuccess").fadeOut(3000);
                    location.reload();
                },
                error:function(e){
                    $('#customRangeError').hide();
                    $('#customRangeSuccess').show();
                    $('#customRangeSuccess').html("Delivery Range Fee saved successfully. <button type='button' class='close' data-dismiss='alert'>&times;</button>");
                    $("#customRangeSuccess").fadeOut(3000);
                    location.reload();
                }
            }); 
        }
    }
    return false;
} 

function addFee(id) {
  var cart_amount_1 = $('#cartAmount1_'+id).val();
  var cart_amount_2 = $('#cartAmount2_'+id).val();
  var range_type    = $('#rangeType_'+id).val();
  var fee_type      = $('#feeType_'+id).val();
  var basic_fee     = $('#basicFee_'+id).val();
  var add_url       = $('#add_'+id).val();
  $('#'+id).attr("disabled", true); 
  if(cart_amount_1 == "") {
        $('#customSuccess').hide();
        $('#customError').show();
        $('#customError').html("Enter Cart Amount 1 <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customError").fadeOut(3000);
        $('#'+id).attr("disabled", false);
    }else if(range_type == "in_between" && cart_amount_2 == ""){
        $('#customSuccess').hide();
        $('#customError').show();
        $('#customError').html("Enter Cart Amount 2 <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customError").fadeOut(3000);
        $('#'+id).attr("disabled", false);
    }else if((range_type == "below" || range_type == "above") && cart_amount_2 != ""){
        $('#customSuccess').hide();
        $('#customError').show();
        $('#customError').html("Cart amount 2 must be empty <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customError").fadeOut(3000);
        $('#'+id).attr("disabled", false);
    } else if(parseFloat(cart_amount_1) >= parseFloat(cart_amount_2)){
        $('#customSuccess').hide();
        $('#customError').show();
        $('#customError').html("Invalid cart amount 2 <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customError").fadeOut(3000);
        $('#'+id).attr("disabled", false);
    }else if(basic_fee == ""){
        $('#customSuccess').hide();
        $('#customError').show();
        $('#customError').html("Enter Basic Fee <button type='button' class='close' data-dismiss='alert'>&times;</button>");
        $("#customError").fadeOut(3000);
        $('#'+id).attr("disabled", false);
    }else {
        var postData = {
                "cart_amount_1":cart_amount_1,
                "cart_amount_2":cart_amount_2,
                "range_type" : range_type,
                "basic_fee" :basic_fee,
                "fee_type" : fee_type,
                "delivery_fee_type" : "Cart Amount",
                "payment_method_id" : 3
            };
       var k = postData['cart_amount_1'];
        if(add_url != ""){
            $.ajax({
                url: add_url, 
                data:postData,
                type: "POST", 
                dataType: 'json',
                success: function(result) {
                    $('#customError').hide();
                    $('#customSuccess').show();
                    $('#customSuccess').html("Delivery Fee saved successfully. <button type='button' class='close' data-dismiss='alert'>&times;</button>");
                    $("#customSuccess").fadeOut(3000);
                    location.reload();
                },
                error:function(e){
                    $('#customError').hide();
                    $('#customSuccess').show();
                    $('#customSuccess').html("Delivery Fee saved successfully. <button type='button' class='close' data-dismiss='alert'>&times;</button>");
                    $("#customSuccess").fadeOut(3000);
                    location.reload();
                }
            }); 
        }
    }
    return false;
} 
</script>