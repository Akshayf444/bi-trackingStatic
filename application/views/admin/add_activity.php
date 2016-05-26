<?php echo form_open('admin/add_activity'); ?>
<div class="col-lg-10 col-sm-10 col-md-10 col-xs-10">
    Name:  <input type="text" class="form-control" value="" name="Activity_Name" placeholder="Enter Activity_Name"/>
    Division:<input type="text" class="form-control" value="" name="Division" placeholder="Enter Division "/>
    Product:<select  class="form-control" name="Product_ID" >
        <option value="-">Select Product</option>
        <?php echo $Product ?>
    </select>   
    <div class="row">
        <button class="btn btn-success pull-right">Submit</button>
    </div>
</div>
</form>
