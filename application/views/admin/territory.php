<input type="button"   class="btn btn-primary pull-right" value="Add" data-toggle="modal" data-target="#myModal">
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <?php echo form_open('admin/territory_add'); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Territory</h4>
            </div>
            <div class="modal-body">
                <div class="col-lg-3">    
                    <input type="text" class="form-control"  name="territory[]" placeholder="Division" maxlength="2" placeholder="Division" />
                </div>
                <div class="col-lg-3"> 
                    <input type="text" class="form-control"  name="territory[]" placeholder="Zone" maxlength="4" placeholder="Zone" />
                </div>
                <div class="col-lg-3"> 
                    <input type="text" class="form-control"  name="territory[]" placeholder="Territory" />
                </div> 
                <div class="col-lg-3"> 
                    <input type="text" class="form-control"  name="territory[]" placeholder="Territory" />
                </div><br/>
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

<table class="table table-bordered table-hover panel" id="datatable">
    <thead>
        <tr>
            <th> S.NO</th>
            <th>Territory</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($show)) {
            foreach ($show as $row) :
                ?>
                <tr>  
                    <td><?php echo $row->id; ?> 
                    <td><?php echo $row->Territory; ?>  
                    <td>  
                        <a class="fa fa-trash-o btn-danger btn-xs" class=""  onclick="delterr('<?php echo site_url('admin/terr_del?id=') . $row->id; ?>')"></a> 
                        <a class="fa fa-pencil  btn-success btn-xs " onclick="window.location = '<?php echo site_url('admin/update_terr?id=') . $row->id; ?>';"></a>
                    </td>
                </tr>
                <?php
            endforeach;
        }
        ?>
    </tbody>
</table>

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

    function delterr(url) {
        var r = confirm("Are You Sure You Want Delete");
        if (r == true) {
            window.location = url;

        }
        else {
            return false;
        }
    }
</script>
