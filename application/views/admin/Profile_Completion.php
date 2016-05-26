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
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"> Profile Completion Status </h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-2">
        <h5>Zone</h5>
        <select name="filter" class="form-control">
            <option>Abc</option>
            <option>sas</option>
            <option>fgdf</option>
        </select>
    </div>
    <div class="col-lg-2">
        <h5>Region</h5>
        <select name="filter" class="form-control">
            <option>South</option>
            <option>North</option>
            <option>East</option>
        </select>
    </div>
    <div class="col-lg-2">
        <h5>Product</h5>
        <select name="filter" class="form-control">
            <option>A</option>
            <option>B</option>
            <option>C</option>
        </select>
    </div>
    <div class="col-lg-2">
        <h5>Employee</h5>
        <select name="filter" class="form-control">
            <option>Bdm</option>
            <option>Asm</option>
            <option>Zsm</option>
        </select>
    </div>
    <div class="col-lg-2">
        <h5></h5>
        <input type="submit" style="    margin-top: 23px;" class="btn btn-success"></input>
    </div>
</div>
<div class="row">
    <div class="col-lg-10 col-sm-10 col-md-10 col-xs-10">
        <div class="table-responsive">
            <table class="table table-bordered table-hover ">

                <tr>
                    <th>Employee Name</th>
                    <th>Doctor Count</th>

                    <th>Compleated Dr Profile</th>

                </tr>

            </table>
        </div>
    </div>
</div>


