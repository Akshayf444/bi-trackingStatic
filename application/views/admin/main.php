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
<ul class="table-view ">
    <li class="table-view-cell table-view-divider"> Employee List</li>

    <!--        <li class="table-view-cell">
                Select Product
                <select class="form-control" id="product">
                    <option>Please Select</option>
                    <option>Actilyse</option>
                    <option>Pradaxa</option>
                    <option>Trajenta Family</option>              
                </select>
                <table class="table table-bordered" id="rx" style="display: none">
                    <tr>
                        <td></td>
                        <td>Sep</td>
                        <td>Oct</td>
                        <td>Nov</td>
                        <td>Dec</td>
                    </tr>
                    <tr>
                        <th>Expected Rx</th>
                        <td>10</td>
                        <td>20</td>
                        <td>30</td>
                        <td>40</td>
                    </tr>
                    <tr>
                        <th>Actual Rx</th>
                        <td>10</td>
                        <td>20</td>
                        <td>30</td>
                        <td>40</td>
                    </tr>
                </table>
            </li>   -->
    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal">
        Add Employee
    </button>


    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <h4 class="modal-title" id="myModalLabel">Add Employee</h4>
                </div>
                <div class="modal-body">
                    <?php echo form_open('admin/insert_emp') ?>

                    <div class="form-group">
                        <input type="text" class="form-control uname" placeholder="Enter Zone" name="zone"/>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control pword" placeholder=" Enter Name" name="name" />
                    </div>
                    <button class="btn btn-positive btn-block" type="submit" name="submit" >Sign In</button>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
    <div>
        <ul class="table-view-cell">
            <li class="table-view-cell">
                <table class="table table-borderedb">
                    <tr>
                        <th></th>
                        <th></th>
                        <th>First_Name</th>
                        <th>Middle_Name</th>
                        <th>Last_Name</th>
                        <th>Full_Name</th>
                        <th>Territory</th>
                        <th>Gender</th>
                        <th>Mobile</th>
                        <th>Email_ID</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Last_Login</th>
                        <th>Address_1</th>
                        <th>Address_2</th>
                        <th>City</th>
                        <th>State</th> 
                        <th>Division</th>  
                        <th>Product</th>
                        <th>Zone</th> 
                        <th>Region</th> 
                        <th>Profile</th>  
                        <th>Designation</th>  
                        <th>Created_By</th>  
                        <th>Created_Date</th> 
                        <th>Modified_By</th>
                        <th>Modified_Date</th> 
                        <th>Date_of_Joining</th>  
                        <th>DOB</th> 
                        <th>Reporting_To</th> 


                        <th>Action</th>

                    </tr>
                    <tr>
                        <?php
                        if (!empty($show)) {
                            foreach ($show as $row) :
                                ?><tr>  


                                <td><?php echo $row->First_Name; ?>
                                </td>  
                                <td><?php echo $row->Middle_Name; ?></td>  
                                <td><?php echo $row->Last_Name; ?></td> 
                                <td><?php echo $row->Full_Name; ?></td>  
                                <td><?php echo $row->Territory; ?></td>  
                                <td><?php echo $row->Gender; ?></td>  
                                <td><?php echo $row->Mobile; ?></td>  
                                <td><?php echo $row->Email_ID; ?></td> 
                                <td><?php echo $row->Username; ?></td>
                                <td><?php echo $row->Password; ?></td>
                                <td><?php echo $row->Last_Login; ?></td> 
                                <td><?php echo $row->Address_1; ?></td>  
                                <td><?php echo $row->Address_2; ?></td>  
                                <td><?php echo $row->City; ?></td>
                                <td><?php echo $row->State; ?></td>
                                <td><?php echo $row->Division; ?></td>
                                <td><?php echo $row->Product; ?></td>
                                <td><?php echo $row->Zone; ?></td>
                                <td><?php echo $row->Region; ?></td>  
                                <td><?php echo $row->Profile; ?></td>
                                <td><?php echo $row->Designation; ?></td>
                                <td><?php echo $row->Created_By; ?></td>  
                                <td><?php echo $row->Created_Date; ?></td> 
                                <td><?php echo $row->Modified_By; ?>
                                </td>  
                                <td><?php echo $row->Modified_Date; ?></td> 
                                <td><?php echo $row->Date_of_Joining; ?></td>  
                                <td><?php echo $row->DOB; ?></td> 
                                <td><?php echo $row->Reporting_To; ?></td> 


                                <td>  
                                    <a class="fa fa-trash-o" onclick="window.location = '<?php echo site_url('admin/delete_hq?id=') . $row->hq_id; ?>';"></a> 
                                    <a class="fa fa-pencil " onclick="window.location = '<?php echo site_url('admin/update_hq?id=') . $row->hq_id; ?>';"></a> </td>


                                <?php
                            endforeach;
                        }
                        ?>
                    </tr>
                </table>
            </li>
        </ul>

    </div>



