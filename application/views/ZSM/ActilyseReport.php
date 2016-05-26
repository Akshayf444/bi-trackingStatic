<div class="panel panel-default">
    <div class="panel-body ">
        <?php echo form_open('Report/actilyse_report'); ?>

        <div class="col-sm-2 col-md-2"  >
            <select name="Zone" class="form-control">
                <option value="-1">Select Zone </option>
                <?php echo $zone; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Fetch</button>


    </div>
    
</div>
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 ">
    <table class="table table-bordered table-hover panel" id="datatable">
        <thead>
            <tr>
                <th>ASM</th>
                <th>BDM</th>
                <th>Hospital Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
          
            if (!empty($show)) {
                foreach ($show as $row) :
                    ?><tr>  
                        <td><?php  echo $row->Reporting_To; ?></td>  
                        <td><?php echo $row->Full_Name; ?> </td> 
                        <td><?php echo $row->NAME; ?></td>  
                        <td>  
                            
                            <a class="fa fa-eye btn-success btn-xs" onclick="window.location = '<?php echo site_url('Report/data_show?id=') . $row->VEEVA_Account_ID  ?>';"></a>                                 
                        </td>
                        
                    </tr>
                    <?php
                endforeach;
            }
            ?>
        </tbody>
    </table>
</div>
