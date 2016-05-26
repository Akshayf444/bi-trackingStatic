<div class="panel panel-default">
    <div class="panel-body ">
        <?php
        $attribute = array('method' => 'GET');
        echo form_open('admin/emp_docmaster', $attribute);
        ?>
        <div class="col-lg-3" style="padding: 0">
            <select name="id" class="chosen-select btn btn-default" data-placeholder="Choose BDM"  tabindex="1">
                <option value="-1">Select Employee  </option>
                <?php echo $Name; ?>
            </select>
        </div>
        <div class="col-lg-2">
            <button type="submit" class="btn btn-primary">Fetch</button>
            <input type="submit" name="Export" value="Export" class="btn btn-success " >
        </div>
        </form>

        <?php
        $attribute = array('method' => 'POST');
        echo form_open('admin/assigndoctorlist', $attribute);
        ?>
        <div class="col-lg-2">
            <b>Assign Doctor List To</b>
        </div>
        <div class="col-lg-3">                 
            <select name="newveevaid" class="chosen-select btn btn-default" data-placeholder="Choose BDM"  tabindex="1">
                <option value="-1">Select Employee  </option>
                <?php echo $Name; ?>
            </select>
            <input type="hidden" name="oldveevaid" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
        </div>
        <div class="col-lg-2">
            <button type="submit" class="btn btn-primary">Assign</button>
        </div>
        </form>

    </div>
</div>
<div class="panel panel-default">
    <div class="panel-body ">
        <a download="empdoc.csv" class="btn btn-success" href="<?php echo asset_url() ?>empdoc.csv" > Sample File for CSV Upload</a>
        <input type="button"   class="btn btn-primary " value="Import CSV" data-toggle="modal" data-target="#myModal1">
    </div>
</div>

<table class="table table-bordered table-hover panel" id="datatable" >
    <thead>
        <tr>
            <th>Employee Name</th>
            <th>Zone</th>
            <th>Territory</th>
            <th>Doctor Code</th>
            <th>Doctor Name</th>
            <th>Specialty</th>
            <th>Individual Type</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $counter = 0;
        if (!empty($show)) {
            foreach ($show as $row) :
                ?><tr>  
                    <td><?php echo $row->Full_Name; ?></td>
                    <td><?php echo $row->Zone; ?></td>
                    <td><?php echo $row->Territory; ?></td>
                    <td><?php echo $row->Account_ID; ?></td>  
                    <td><?php echo $row->NAME; ?></td>  
                    <td><?php echo $row->Specialty; ?></td> 
                    <td><?php echo $row->Individual_Type; ?></td> 
                    <td> 
                        <a class="fa fa-trash-o btn-danger btn-xs" class=""  onclick="deletedoc('<?php echo site_url('admin/empdoc_del?id=') . $row->Account_ID . '&emp_id=' . $row->VEEVA_Employee_ID; ?>')"></a> 
                        <a class="fa fa-pencil btn-success btn-xs" onclick="window.location = '<?php echo site_url('admin/update_doc?id=') . $row->Account_ID; ?>';"></a>
                    </td>
                </tr>

                <?php
            endforeach;
        }
        ?>
    </tbody>
</table>

<script>




    function deletedoc(url) {
        var r = confirm("Are you sure you want to delete");
        if (r == true)
        {
            window.location = url;

        }
        else
        {
            return false;
        }
    }


</script>
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Import CSV</h4>
            </div> 
            <?php
            $attribute = array('enctype' => 'multipart/form-data', 'name' => 'form1', 'id' => 'form1');
            echo form_open('Admin/empdoc_csv', $attribute);
            ?>
            <div class="modal-body">
                <input type="hidden" name="hide" id="csv1" class="form-control" />

                <div class="form_group">
                    Choose your file: <br /> 
                    <input name="csv" type="file" id="csv" class="form-control" />


                </div>

                <br>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>

                </div>
            </div>
            </form>
        </div>
    </div>
</div>