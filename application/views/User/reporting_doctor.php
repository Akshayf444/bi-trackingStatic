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
<form action="" method="post" class="card" >
     <ul class="table-view " >
        <li class="table-view-cell table-view-divider">Reporting Doctor</li>
        <li class="table-view-cell">
            Select Product
            <select class="form-control">
                <option>Please Select</option>
                <option>Actilyse</option>
                <option>Pradaxa</option>
                <option>Trajenta Family</option>              
            </select>

        </li>
           <li class="table-view-cell">
             <table class="table table-bordered" id="item" >
                    <tr>
                        <th>Doctor Name</th>
                        <th>RX For Jan</th>
                    </tr>
                    <tr>
                        <td>SSDF</td>
                        <td><input type="text" class="form-control" name="doc_name" placeholder=""></td>
                    </tr>
                </table>
           </li>
            <li class="table-view-cell">
            <br/>
            <button class="btn btn-lg btn-positive">Submit</button>
            <br/>
        </li>
     </ul>
                 
            </form>
        
    </div>
</div>

