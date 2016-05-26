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
<form class="card">
    <ul class="table-view " >
        <li class="table-view-cell table-view-divider">Planning</li>
        <li class="table-view-cell">
            <span class="pull-right">Total Expected Rx : 200</span>
        </li>
        <li class="table-view-cell">
            Select Product
            <select class="form-control">
                <option>Please Select</option>
                <option>Actilyse</option>
                <option>Pradaxa</option>
                <option>Trajenta Family</option>              
            </select>

        </li>
<!--        <li class="table-view-cell">-->
<!--            Select Doctor
            <select class="form-control doctor">
                <option>Please Select</option>
                <option>Yogesh Kanse</option>             
                <option>Naresh Ghadi</option>             
            </select>  -->
            <table class="table table-bordered" id="rx" style="display: none">
                <tr>
                    <td></td>
                    <td>Sep</td>
                    <td>Oct</td>
                    <td>Nov</td>
                    <td>Dec</td>
                </tr>
                <tr>
                    <th>Delta</th>
                    <td>10%</td>
                    <td>20%</td>
                    <td>30%</td>
                    <td>40%</td>
                </tr>
                <tr>
                    <th>Rx</th>
                    <td>10</td>
                    <td>20</td>
                    <td>30</td>
                    <td>40</td>
                </tr>
            </table>
        </li>
        <div class=""> 
            <table class="table table-bordered">
                <tr>
                    <th>Dr Name</th>
                    <th>Expected Rx For Jan</th>
                    <th>Delta</th>
                </tr>
                <tr>
                    <td>Abc</td>
                    <td>

                        <input type="text" >

                    </td>
                    <td><input type="text" value="10%"></td>
                </tr>
                <tr>
                    <td>dR REDDY</td>
                    <td>

                        <input type="text" >

                    </td>
                    <td><input type="text" value="10%"></td>
                </tr>
                <tr>
                    <td>PQR</td>
                    <td>

                        <input type="text" >

                    </td>
                    <td><input type="text" value="10%"></td>
                </tr>
            </table>
            

        </div>
        <!--        <li class="table-view-cell">
        
                    Activities <br>
                    <div class="col-xs-12">
                        <input type="checkbox" > Activity 1
                    </div>
                    <div class="col-xs-12">
                        <input type="checkbox" > Activity 2
                    </div>
                    <div class="col-xs-12">
                        <input type="checkbox" > Activity 3
                    </div>
                </li>-->
        <li class="table-view-cell">
            <br/>
            <button class="btn btn-lg btn-positive">Submit</button>
            <br/>
        </li>
    </ul>
</form>
<script>
    $(".doctor").change(function () {
        $("#rx").show();
    });
</script>

