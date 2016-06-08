  <?php

echo form_open('User/Actilyse');
?><?php
$doctor_id=$this->input->get('Doctor_Id');?>
<div class="row">
     <input type="hidden" class="form-control"  value="<?php echo $doctor_id ; ?>"   name="Doctor_id" class="form-group">
                 <input type="hidden" class="form-control"  value="<?php  if(isset($show->Actilyse_id)){echo $show->Actilyse_id;} ?>"   name="Actilyse_id" class="form-group">
        <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php if(isset($show->Multi_Super_speciality)){ echo $show->Multi_Super_speciality;} ?>" placeholder="Multi Super speciality"  name="Multi_Super_speciality" class="form-group">
        </div>
         <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php if(isset($show->No_of_Beds)){ echo $show->No_of_Beds;} ?>" placeholder="No of Beds"  name="No_of_Beds" class="form-group">
        </div>
         <div class="col-lg-3">
            <input type="text"  class="form-control"  value="<?php if(isset($show->No_of_Emergency_Beds)){ echo $show->No_of_Emergency_Beds;} ?>" placeholder="No of Emergency Beds" name="No_of_Emergency_Beds" class="form-group">
        </div>
        <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php  if(isset($show->CT_Scan)){ echo $show->CT_Scan;} ?>" placeholder="CT Scan"  name="CT_Scan" class="form-group">
        </div>
</div><br>
    <div class="row">
    
        
        <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php if(isset($show->MRI_Facility)){ echo $show->MRI_Facility;} ?>" placeholder="MRI Facility"  name="MRI_Facility" class="form-group">
        </div>
         <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php if(isset($show->CT_Scan24)){  echo $show->CT_Scan24;} ?>" placeholder="CT Scan24"  name="CT_Scan24" class="form-group">
        </div>
         <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php if(isset($show->Focus)){ echo $show->Focus; }?>" placeholder="Focus"  name="Focus" class="form-group">
        </div>
        <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php  if(isset($show->Location)){ echo $show->Location;} ?>" placeholder="Location"  name="Location" class="form-group">
        </div>
    </div> <br>
    
<div class="row">
    
        
        <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php if(isset($show->Segment)){ echo $show->Segment;} ?>" placeholder="Segment" name="Segment" class="form-group">
        </div>
         <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php if(isset($show->MSL_Name)){ echo $show->MSL_Name;} ?>" placeholder="MSL Name"  name="MSL_Name" class="form-group">
        </div>
         <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php if(isset($show->Gain_Project)){ echo $show->Gain_Project;} ?>" placeholder="Gain Project" name="Gain_Project" class="form-group">
        </div>
        <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php if(isset($show->Stroke_Champion_Name)){ echo $show->Stroke_Champion_Name;} ?>" placeholder="Stroke Champion Name"  name="Stroke_Champion_Name" class="form-group">
        </div>
    </div> <br>
<div class="row">
    <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php if(isset($show->Spec_of_Stroke_Champion)){ echo $show->Spec_of_Stroke_Champion;} ?>" placeholder="Spec of Stroke Champion" name="Spec_of_Stroke_Champion" class="form-group">
        </div>
        
        <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php if(isset($show->Neurologist1)){ echo $show->Neurologist1;} ?>" placeholder="Neurologist1"  name="Neurologist1" class="form-group">
        </div>
         
         <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php if(isset($show->Cardiologist1)){ echo $show->Cardiologist1; }?>" placeholder="Cardiologist1" name="Cardiologist1" class="form-group">
        </div>
        <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php  if(isset($show->Cardiologist2)){  echo $show->Cardiologist2;} ?>" placeholder="Cardiologist2" name="Cardiologist2" class="form-group">
        </div>
</div><br>
<div class="row">
    <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php if(isset($show->Emergency_Head)){ echo $show->Emergency_Head;} ?>" placeholder="Emergency Head"  name="Emergency_Head" class="form-group">
        </div>
        
        <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php if(isset($show->Radiology_Head)){ echo $show->Radiology_Head;} ?>" placeholder="Radiology Head"  name="Radiology_Head" class="form-group">
        </div>
         
         <div class="col-lg-3">
            <input type="text"class="form-control"  value="<?php if(isset($show->Intensivist1)){ echo $show->Intensivist1;} ?>" placeholder="Intensivist1"  name="Intensivist1" class="form-group">
        </div>
        <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php if(isset($show->Intensivist2)){ echo $show->Intensivist2;} ?>" placeholder="Intensivist2" name="Intensivist2" class="form-group">
        </div>
</div><br>

<div class="row">
    <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php if(isset($show->AISpatients_Month)){ echo $show->AISpatients_Month;} ?>" placeholder="AIS patients Month"  name="AISpatients_Month" class="form-group">
        </div>
        
        <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php if(isset($show->Ambulance_service)){ echo $show->Ambulance_service;} ?>" placeholder="Ambulance service" name="Ambulance_service" class="form-group">
        </div>
         
         <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php if(isset($show->Stroke_Protocol)){ echo $show->Stroke_Protocol;} ?>" placeholder="Stroke Protocol" name="Stroke_Protocol" class="form-group">
        </div>
        <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php  if(isset($show->Thrombolysing_Unit)){echo $show->Thrombolysing_Unit;} ?>" placeholder="Thrombolysing Unit"  name="Thrombolysing_Unit" class="form-group">
        </div>
</div><br>
<div class="row">
    <div class="col-lg-3">
            <input type="text" class="form-control"  value="<?php if(isset($show->No_of_doctors_in_stroke_team)){ echo $show->No_of_doctors_in_stroke_team ;}?>" placeholder="No of doctors in stroke team"  name="No_of_doctors_in_stroke_team" class="form-group">
        </div>
        
        <div class="col-lg-3">
            <input type="text"  class="form-control"  value="<?php if(isset($show->Approach)){  echo $show->Approach;} ?>" placeholder="Approach" name="Approach" class="form-group">
        </div>
    
         </div>
<br>

    <div class="col-lg-12">
        
        <input type="submit" class="btn  btn-success" value="Save"> </div>
    </div>
        
</form>  
        
        
        
    
