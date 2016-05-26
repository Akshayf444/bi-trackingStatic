<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    <div class="panel panel-default">
        <div class="panel-body ">
            <?php
            $attribute = array('method' => 'get');
            echo form_open('Admin/reporting_change',$attribute);
            ?>
            <div class="col-sm-2 col-md-2"  >
                <input type="text" class="form-control" name="id" >

            </div>
            <button type="submit" class="btn btn-primary">view</button>
            </form>
        </div>
    </div>
</div>
<div class="col-lg-10 col-sm-10 col-md-10 col-xs-10">
    <div class="table-responsive">
        <?php if (!empty($show)) { ?>
            <table class="table table-bordered table-hover " id="datatable">

                <thead>
                    <tr style="background-color: #428BCA">
                        <th>Full Name</th>
                        <th>Territory</th>
                        <th>Mobile</th>
                        <th>Zone</th> 
                        <th>Profile</th>
                        <th>Reporting_To</th>
                        <th>Reporting_ID</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                  
                    foreach ($show as $row) :
                        
                       
                     echo form_open('Admin/change_profile');?>
                  
                <input type="hidden" value="<?php echo $row->VEEVA_Employee_ID?>" name="veeva_id">
                     
                    <tr>  
                            <td><?php echo $row->Full_Name; ?></td>  
                            <td><?php echo $row->Territory; ?> </td> 
                            <td><?php echo $row->Mobile; ?></td>  
                            <td><?php echo $row->Zone; ?></td>
                            <td>Profile:<select  class="form-control" name="Profile" id="profile" >
                                    <option value="-1">Select  Profile</option>
                                    <?php echo $Profile ?>
                                </select> </td>

                            <td> Reporting_To :<select  class="form-control" name="Reporting_To" id="reporting_to" >
                                    <option value="-1">Select Reporting_To</option>
                                    <?php echo $Reporting_To ?>
                                </select></td>

                                <td><input type="text" name="reporting_veeva_id"  value="<?php echo $row->Reporting_VEEVA_ID ?>" class="form-control"></td>
                            <td>   <button class="btn btn-primary" type="submit">Change Reporting </button> 
                            </td>   </tr>
                        <?php
                    endforeach;
                }
                else {
                    echo 'Data is not available';
                }
                ?>

            </tbody>

        </table>
    </div>
</div>
<script type="text/javascript">
    $('#profile').change(function () {
        var profile = $(this).val();
        $.ajax({
            url: '<?php echo site_url('admin/ajax_data') ?>',
            data: {profile: profile},
            type: 'POST',
            success: function (data) {
                $('#reporting_to').html(data);    //please note this, here we're focusing in that input field
            }
        });
    });

</script>