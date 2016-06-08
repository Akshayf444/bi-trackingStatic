<!--<link href="http://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" rel="Stylesheet" type="text/css">
<script src="<?php //echo asset_url(); ?>js/jquery.dataTables.min.js" type="text/javascript"></script>-->
<?php $this->load->view('User/ProductList', array('site_url' => 'User/Priority')); ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
        <span class="pull-right">
            Sort By
            <select class="form-control" id="TableSort">
                <option value="1">Select Filter</option>
                <option value="2">Dependency/Rx For Last Month</option>
                <option value="3">BI Market Share</option>
                <option value="7">Planned <?php
                    if ($this->Product_Id == '1') {
                        echo "Vials";
                    } else {
                        echo "Rx";
                    }
                    ?> Of Present Month</option>
            </select>
        </span>
    </div>
</div>
<?php echo form_open('User/Priority?Product_Id=' . $this->Product_Id); ?>

<!--    <div class="panel panel-default">
        <div class="panel-heading">Set Priority</div>-->
<?php
echo isset($doctorList) ?
        '<div class="row">
            <div class="col-lg-12 col-md-12 ">' . $doctorList . '</div></div>
            <div class="row">
            <div class="col-lg-12 col-xs-12">    
            <button type="submit" id="Save" class="btn btn-primary">Save</button>
        </div></div>' : '<h2>Rx/Vials Planning Not Completed</h2>'
?>
<input type="hidden" id="Status" name="Status" value="Draft">


</form>
<style>
    #datatable_filter{
        display: none;
    }
    table.dataTable tbody tr {
        background-color: transparent;
    }

    .row{
        margin-bottom: 10px;
    }
</style>
<script>
    $('document').ready(function () {
        var oTable = $('#datatable').dataTable({
            "destroy": true,
            "bPaginate": false,
            "bInfo": false,
            "info": false,
            "columnDefs": [
                {
                    "targets": [7],
                    "visible": false
                }
            ]
        });
    });


    $('#TableSort').on('change', function () {
        var selectedValue = $(this).val();
        oTable.fnSort([[selectedValue, 'desc']]); //Exact value, column, reg
    });
    oTable.fnSort([[7, 'desc']]); //Exact value, column, reg

    $("#Submit").click(function () {
        $("#Status").val('Submitted');
    });
    function deleteEmp(url) {
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