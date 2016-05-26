<style>
    .table-view .table-view-cell {
        background-position: 0px 100%;
    }
    .col-xs-9, .col-xs-3{
        padding: 0px;
    }
    .table-view-cell {
        padding: 11px 12px 11px 15px;
    }
</style>
<div class="panel panel-default">
    
    <div class="panel-body ">
        
        <a class="btn btn-primary " onclick="window.location = '<?php echo site_url('admin/add_doc'); ?>';"><i class="fa fa-plus-circle"></i> Add Doctor</a>
          <input type="button"   class="btn btn-primary " value="Import CSV" data-toggle="modal" data-target="#myModal1">

              
        <a download="doc.csv" class="btn btn-success" href="<?php echo asset_url() ?>docsample.csv" >Sample File For CSV Upload</a>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <table class="table table-bordered panel" id="datatable">
            <thead>
                <tr>
                    <th>Full_Name</th> 
                    <th>Specialty</th> 
                    <th>City</th> 
                    <th>State</th> 
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($show)) {
                    foreach ($show as $row) :
                        ?><tr>  
                            <td><?php echo $row->Account_Name; ?></td> 
                            <td><?php echo $row->Specialty; ?></td>
                            <td><?php echo $row->City; ?></td>
                            <td><?php echo $row->State; ?></td>
                            <td>  
                                <a class="fa fa-trash-o btn-danger btn-xs" class=""  onclick="deletedoc('<?php echo site_url('admin/doc_del?id=') . $row->Account_ID; ?>')"></a> 
                                <!--                                <a class="fa fa-trash-o" href ="<?php
                                // echo site_url('admin/doc_del?id=') . $row->Account_ID;
                                ;
                                ?>"></a> -->
                                <a class="fa fa-pencil btn-success btn-xs " href="<?php echo site_url('admin/update_doc?id=') . $row->Account_ID; ?>"></a> </td></tr>


                        <?php
                    endforeach;
                }
                ?>
            </tbody>
        </table>
        <div class ="row">
            <div class="result">
                <div class="col-lg-12" style="clear: both; margin-bottom: 5px;">
                    <?php
                    echo $html
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>
<script>


    var oTable = $('#datatable').dataTable({
        "bPaginate": false,
        "bInfo": false,
        "info": false,
        "columnDefs": [
            {
                "visible": false
            }
        ]
    });

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
            echo form_open('Admin/doc_csv', $attribute);
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