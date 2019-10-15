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
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($success) { ?>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_speedy_delivery; ?></h3>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="tab_logic">
              <thead>
                <tr>
                  <td class="text-right"><?php echo 'Additional Delivery Fee'; ?></td>
                  <td class="text-right"><?php echo 'Time Slot'; ?></td>
                 <td class="text-right"><?php echo  'Action'; ?></td>
                </tr>
              </thead>
              <tbody>  
        <?php 
              if(isset($speedy_delivery_fee)) {
              foreach($speedy_delivery_fee as $fee) {
              if($fee['time_slot'] == '8 AM - 7 PM') {
        ?>
        <tr id=<?php echo 'speed_delivery_fee_'.$fee['speedy_delivery_fee_id']; ?>>
        <form action="<?php echo $fee['edit']; ?>" method="post" enctype="multipart/form-data" id="form-layout" class="form-horizontal">
          <div class="form-group">
            <td class="text-right"><input type="text" name="speedy_delivery_fee" value=<?php echo $fee['fee']; ?> placeholder="0.00" id="input-name" class="form-control" maxlength="5"/></td>
            <td class="text-right">
                 <select name='time_slot' required="" disabled="">
                   <option  value ='8 AM - 7 PM' selected="">8 AM - 7 PM</option>
                    <option  value ='7 PM - 12 AM'>7 PM - 12 AM</option>
                    <option  value = '12 AM - 8 AM'>12 AM - 8 AM</option>
                </select>
            </td>
            <input type="hidden" name="Speedy_delivery_id" value="<?php echo $fee['speedy_delivery_fee_id']; ?>">
            <td class="text-right"><button data-toggle="tooltip" title="Save" class="btn btn-success"><i class="fa fa-save"></i></button></td>
          </div>
        </form>
        </tr>    
        <?php } else if($fee['time_slot'] == '7 PM - 12 AM') { ?>
        <tr id=<?php echo 'speed_delivery_fee_'.$fee['speedy_delivery_fee_id']; ?>>
         <form action="<?php echo $fee['edit']; ?>" method="post" enctype="multipart/form-data" id="form-layout" class="form-horizontal">
          <div class="form-group">
              <td class="text-right"><input type="text" name="speedy_delivery_fee" value=<?php echo $fee['fee']; ?> placeholder="0.00" id="input-name" class="form-control" maxlength="5"/></td>
             <td class="text-right">
               <select name='time_slot' required="" disabled="">
                    <option  value ='8 AM - 7 PM'>8 AM - 7 PM</option>
                    <option  value ='7 PM - 12 AM' selected = "">7 PM - 12 AM</option>
                    <option  value = '12 AM - 8 AM'>12 AM - 8 AM</option>
                </select>
            </td>
            <input type="hidden" name="Speedy_delivery_id" value="<?php echo $fee['speedy_delivery_fee_id']; ?>">            
            <td class="text-right"><button data-toggle="tooltip" title="Save" class="btn btn-success"><i class="fa fa-save"></i></button></td>
          </div>
        </form>
        </tr>    
        
        <?php
            } else { ?>
        <tr id=<?php echo 'speed_delivery_fee_'.$fee['speedy_delivery_fee_id']; ?>>
         <form action="<?php echo $fee['edit']; ?>" method="post" enctype="multipart/form-data" id="form-layout" class="form-horizontal">
          <div class="form-group">
              <td class="text-right"><input type="text" name="speedy_delivery_fee" value=<?php echo $fee['fee']; ?> placeholder="0.00" id="input-name" class="form-control" maxlength="5"/></td>
             <td class="text-right">
               <select name='time_slot' required="" disabled="">
                    <option  value ='8 AM - 7 PM'>8 AM - 7 PM</option>
                    <option  value ='7 PM - 12 AM' >7 PM - 12 AM</option>
                    <option  value = '12 AM - 8 AM'selected = "">12 AM - 8 AM</option>
                </select>
            </td>
            <input type="hidden" name="Speedy_delivery_id" value="<?php echo $fee['speedy_delivery_fee_id']; ?>">            
            <td class="text-right"><button data-toggle="tooltip" title="Save" class="btn btn-success"><i class="fa fa-save"></i></button></td>
          </div>
        </form>
        </tr>
        <?php     } ?>
      
        <?php
          }
        }
        
        ?> 
        </tbody>
      </table>  
      </div>
    </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>