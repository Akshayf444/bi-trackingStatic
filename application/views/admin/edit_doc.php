<?php
$attribute = array('id' => 'valid');
echo form_open('admin/update_doc?id=' . $rows['Account_ID'], $attribute);
?>

<div class="col-lg-10 col-sm-10 col-md-10 col-xs-10">
    <input type="hidden" class="form-control" value="<?php echo $rows['Account_ID']; ?>" name="Account_ID" placeholder="Account_ID" />
    <div class="col-lg-4">
        Salutation: <input type="text" class="form-control" value="<?php echo $rows['Salutation']; ?>" name="Salutation" placeholder="Salutation" /> </div>
    <div class="col-lg-4">
        First Name:  <input type="text" class="form-control" value="<?php echo $rows['First_Name']; ?>" name="First_Name" placeholder="Enter First Name"/> </div>

    <div class="col-lg-4">
        Last Name:<input type="text" class="form-control" value="<?php echo $rows['Last_Name']; ?>" name="Last_Name" placeholder="Enter Last Name"/> </div>
    <div class="col-lg-4">
        Account Name:<input type="text" class="form-control" value="<?php echo $rows['Account_Name']; ?>" name="Account_Name" placeholder="Enter Account Name"/> </div>
    <div class="col-lg-4">
        Record Type:<input type="text" class="form-control" value="<?php echo $rows['Record_Type']; ?>" name="Record_Type" placeholder="Enter Record_Type"/> </div>
    <div class="col-lg-4">
        Specialty:<select  class="form-control" name="Specialty" >
            <option value=" Select Specialty">Select Specialty</option>
            <?php echo $specialty ?>
        </select> 
    </div>
    <div class="col-lg-4">
        Specialty2:<input type="text" class="form-control" value="<?php echo $rows['Specialty_2']; ?>" name="Specialty2" placeholder="Enter Specialty2"/> </div>
    <div class="col-lg-4">
        Specialty3:<input type="text" class="form-control" value="<?php echo $rows['Specialty_3']; ?>" name="Specialty3" placeholder="Enter Specialty3"/> </div>
    <div class="col-lg-4">
        Specialty4:<input type="text" class="form-control" value="<?php echo $rows['Specialty_4']; ?>" name="Specialty4" placeholder="Enter Specialty4"/> </div>
    <div class="col-lg-4">
        Individual Type:<select  class="form-control" name="Individual_Type" >
            <option value=" Select Individual Type">Select Individual Type</option>
            <?php echo $IndividualType ?>
        </select> 
    </div>
    <div class="col-lg-4">
        Email ID:<input type="text" class="form-control" value="<?php echo $rows['Email']; ?>" id="email" name="Email_ID" placeholder="Enter Email_ID"/> </div>
    <div class="col-lg-4">

        Gender:<select name="Gender" class="form-control" >
            <option value="Female" <?php
            if ($rows['Gender'] == 'Female') {
                echo 'selected';
            }
            ?>>Female</option>


            <option value="Male" <?php
            if ($rows['Gender'] == 'Male') {
                echo 'selected';
            }
            ?>>Male</option>
        </select>
    </div>

    <div class="col-lg-4">
        Mobile:<input type="text" class="form-control" value="<?php echo $rows['Mobile']; ?>" name="Mobile" placeholder="Enter Mobile"/> </div>
    <div class="col-lg-4">
        City:  <input type="text" class="form-control" value="<?php echo $rows['City']; ?>" name="City" placeholder="Enter City"/> </div>
    <div class="col-lg-4">
        State:  <input type="text" class="form-control" value="<?php echo $rows['State']; ?>" name="State" placeholder="Enter State"/> </div>
    <div class="col-lg-4">
        Pincode:  <input type="text" class="form-control" value="<?php echo $rows['Pin_Code']; ?>" name=" Pincode" placeholder="Enter Pincode"/> </div>
    <div class="col-lg-4">
        Address:  <input type="text" class="form-control" value="<?php echo $rows['Address']; ?>" name="Address" placeholder="Enter Address"/> </div>
    <div class="col-lg-12">
        <br/>
        <button class="btn btn-success" type="submit">Submit</button>
    </div>
</div>
</form>