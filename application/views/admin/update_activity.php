<div class="row">
    <?php echo form_open('admin/update_act'); ?>
    <?php
    if (!empty($rows)) {
        ?>
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <input type="hidden" class="form-control" value="<?php echo $rows['Activity_id']; ?>" name="Act_id" placeholder="Enter Activity_Name"/>
            Name:  <input type="text" class="form-control" value="<?php echo $rows['Activity_Name']; ?>" name="Activity_Name" placeholder="Enter Activity_Name"/>
            Division:<input type="text" class="form-control" value="<?php echo $rows['Division']; ?>" name="Division" placeholder="Enter Division "/>
            Product:<select  class="form-control" name="Product_ID" >
                <option value="-1">Select Product</option>
                <?php echo $Product ?>
            </select>   
        <?php } ?>
        <div class="row">
            <button class="btn btn-success pull-right">Submit</button>
        </div>
    </div>
</form>
</div>