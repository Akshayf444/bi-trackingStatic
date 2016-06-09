  <?php

echo form_open('User/Actilyse');
?><?php
$doctor_id=$this->input->get('Doctor_Id');?>
<input type="hidden" class="form-control"  value="<?php echo $doctor_id ; ?>"   name="Doctor_id" class="form-group">
<input type="hidden" class="form-control"  value="<?php echo $this->input->get('Hospital'); ?>"   name="Hospital" class="form-group">
                 <input type="hidden" class="form-control"  value="<?php  if(isset($show->Actilyse_id)){echo $show->Actilyse_id;} ?>"   name="Actilyse_id" class="form-group">
    <div class="row">
                    
                   <div class="col-lg-3">
             <label> No of Beds</label>
            <input type="text" class="form-control"  value="<?php if(isset($show->No_of_Beds)){ echo $show->No_of_Beds;} ?>" placeholder="No of Beds"  name="No_of_Beds" class="form-group">
        </div>    
                  <div class="col-lg-3">
         <label>AIS Patients Month</label>
            <input type="text" class="form-control"  value="<?php if(isset($show->AISpatients_Month)){ echo $show->AISpatients_Month;} ?>" placeholder="AIS patients Month"  name="AISpatients_Month" class="form-group">
        </div>
        
        
         <div class="col-lg-3">
             <label>Focus</label>
             <select name="Focus" class="form-control">
                 <option>
                     Select Option
                 </option>
                 <option value="Focus" <?php if(isset($show->Focus)&& $show->Focus=='Focus'){ echo 'selected' ; }?>>Focus</option>
                  <option value="Non-Focus" <?php if(isset($show->Focus)&& $show->Focus=='Non-Focus'){ echo 'selected' ; }?>>Non-Focus</option>
             </select>
            
        </div>
        <div class="col-lg-3">
              <label>Location</label>
            <input type="text" class="form-control"  value="<?php  if(isset($show->Location)){ echo $show->Location;} ?>" placeholder="Location"  name="Location" class="form-group">
        </div>
    </div> <br>
    
<div class="row">
    
        
        <div class="col-lg-3">
            
             <label>Segment</label>
             <select name="Segment" class="form-control">
                 <option>Select Option</option>
                 <option value="Gain" <?php if(isset($show->Segment)&& $show->Segment=='Gain'){ echo 'selected' ; }?>>Gain</option>
                  <option value="Build" <?php if(isset($show->Segment)&& $show->Segment=='Build'){ echo 'selected' ; }?>>Build</option>
                    <option value="Defend" <?php if(isset($show->Segment)&& $show->Segment=='Defend'){ echo 'selected' ; }?>>Defend</option>
             </select>
           
        </div>
         <div class="col-lg-3">
               <label>
     MSL Name
            </label>
            <input type="text" class="form-control"  value="<?php if(isset($show->MSL_Name)){ echo $show->MSL_Name;} ?>" placeholder="MSL Name"  name="MSL_Name" class="form-group">
        </div>
         <div class="col-lg-3">
             <label>
     Gain Project
            </label>
            <input type="text" class="form-control"  value="<?php if(isset($show->Gain_Project)){ echo $show->Gain_Project;} ?>" placeholder="Gain Project" name="Gain_Project" class="form-group">
        </div>
        <div class="col-lg-3">
            <label>
           Stroke Champion Name
            </label>
            <input type="text" class="form-control"  value="<?php if(isset($show->Stroke_Champion_Name)){ echo $show->Stroke_Champion_Name;} ?>" placeholder="Stroke Champion Name"  name="Stroke_Champion_Name" class="form-group">
        </div>
    </div> <br>
<div class="row">
    <div class="col-lg-3">
         <label>
             Spec of Stroke Champion
            </label>
            <input type="text" class="form-control"  value="<?php if(isset($show->Spec_of_Stroke_Champion)){ echo $show->Spec_of_Stroke_Champion;} ?>" placeholder="Spec of Stroke Champion" name="Spec_of_Stroke_Champion" class="form-group">
        </div>
        
        <div class="col-lg-3">
            <label>
              Neurologist 1
            </label>
            <input type="text" class="form-control"  value="<?php if(isset($show->Neurologist1)){ echo $show->Neurologist1;} ?>" placeholder="Neurologist1"  name="Neurologist1" class="form-group">
        </div>
         
         <div class="col-lg-3">
             <label>
              Cardiologist 1
            </label>
            <input type="text" class="form-control"  value="<?php if(isset($show->Cardiologist1)){ echo $show->Cardiologist1; }?>" placeholder="Cardiologist1" name="Cardiologist1" class="form-group">
        </div>
        <div class="col-lg-3">
            <label>
             Cardiologist 2
            </label>
            <input type="text" class="form-control"  value="<?php  if(isset($show->Cardiologist2)){  echo $show->Cardiologist2;} ?>" placeholder="Cardiologist2" name="Cardiologist2" class="form-group">
        </div>
</div><br>
<div class="row">
    <div class="col-lg-3">
         <label>
              Emergency Head
            </label>
            <input type="text" class="form-control"  value="<?php if(isset($show->Emergency_Head)){ echo $show->Emergency_Head;} ?>" placeholder="Emergency Head"  name="Emergency_Head" class="form-group">
        </div>
        
        <div class="col-lg-3">
             <label>
              Radiology Head
            </label>
            <input type="text" class="form-control"  value="<?php if(isset($show->Radiology_Head)){ echo $show->Radiology_Head;} ?>" placeholder="Radiology Head"  name="Radiology_Head" class="form-group">
        </div>
         
         <div class="col-lg-3">
                <label>
                Intensivist 1
            </label>
            <input type="text"class="form-control"  value="<?php if(isset($show->Intensivist1)){ echo $show->Intensivist1;} ?>" placeholder="Intensivist1"  name="Intensivist1" class="form-group">
        </div>
        <div class="col-lg-3">
            <label>
                Intensivist 2
            </label>
            <input type="text" class="form-control"  value="<?php if(isset($show->Intensivist2)){ echo $show->Intensivist2;} ?>" placeholder="Intensivist2" name="Intensivist2" class="form-group">
        </div>
</div><br>
<div class="row">
    <div class="col-lg-3">
         <label>No of doctors in stroke team</label>
            <input type="text" class="form-control"  value="<?php if(isset($show->No_of_doctors_in_stroke_team)){ echo $show->No_of_doctors_in_stroke_team ;}?>" placeholder="No of doctors in stroke team"  name="No_of_doctors_in_stroke_team" class="form-group">
        </div>
        
        <div class="col-lg-3">
            <label>Approach</label>
             <select name="Approach" class="form-control">
                 <option>Select Option</option>
                 <option value="Stroke Center" <?php if(isset($show->Approach)&& $show->Approach=='Stroke Center'){ echo 'selected' ; }?>>Stroke Center</option>
                  <option value="Individual" <?php if(isset($show->Approach)&& $show->Approach=='Individual'){ echo 'selected' ; }?>>Individual</option>
                   
             </select>
          
        </div>
    <div class="col-lg-3">
            <label> Ambulance Service</label><br>
              
             <input type="radio" name="Ambulance_service" value="Yes"<?php if(isset($show->Ambulance_service) && $show->Ambulance_service=='Yes'){ echo "checked"  ;} ?>   >Yes
             <input type="radio" name="Ambulance_service" value="No"<?php if(isset($show->Ambulance_service) && $show->Ambulance_service=='No'){ echo "checked"  ;} ?>  >No
                                                     </div>    
                       <div class="col-lg-3">
              <label> CT Scan 24</label> <br>
            <input type="radio" value="Yes"<?php if(isset($show->CT_Scan24) && $show->CT_Scan24=='Yes'){ echo'Checked'  ;} ?>  name="CT_Scan24" >Yes
             <input type="radio" value="No"<?php if(isset($show->CT_Scan24) && $show->CT_Scan24=='No'){ echo'Checked'  ;} ?> name="CT_Scan24" >No
                                                      </div> 
    
</div><br>

<div class="row">
    
        
          
         
         <div class="col-lg-3">
             <label> Stroke Protocol</label> <br>
             <input type="radio" value="Yes"<?php if(isset($show->Stroke_Protocol) && $show->Stroke_Protocol=='Yes'){ echo'Checked'  ;} ?>  name="Stroke_Protocol" >Yes
             <input type="radio" value="No"<?php if(isset($show->Stroke_Protocol) && $show->Stroke_Protocol=='No'){ echo'Checked'  ;} ?> name="Stroke_Protocol" >No
                                                     </div>   
          
        <div class="col-lg-3">
            <label> Thrombolysing Unit</label> <br>
             <input type="radio" value="Yes"<?php if(isset($show->Thrombolysing_Unit) && $show->Thrombolysing_Unit=='Yes'){ echo'Checked'  ;} ?>  name="Thrombolysing_Unit" >Yes
             <input type="radio" value="No"<?php if(isset($show->Thrombolysing_Unit) && $show->Thrombolysing_Unit=='No'){ echo'Checked'  ;} ?> name="Thrombolysing_Unit" >No
                                                     </div>   
         <div class="col-lg-3">
                     <label>Multi Super Speciality</label><br>
            <input type="radio" value="Yes"<?php if(isset($show->Multi_Super_speciality) && $show->Multi_Super_speciality=='Yes'){ echo'Checked'  ;} ?>  name="Multi_Super_speciality" >Yes
             <input type="radio" value="No"<?php if(isset($show->Multi_Super_speciality) && $show->Multi_Super_speciality=='No'){ echo'Checked'  ;} ?> name="Multi_Super_speciality" >No
            
            
        </div>
                 <div class="col-lg-3">
                     <label> MRI Facility</label><br>
            <input type="radio" value="Yes"<?php if(isset($show->MRI_Facility) && $show->MRI_Facility=='Yes'){ echo'Checked'  ;} ?>  name="MRI_Facility" >Yes
             <input type="radio" value="No"<?php if(isset($show->MRI_Facility) && $show->MRI_Facility=='No'){ echo'Checked'  ;} ?> name="MRI_Facility" >No
                                                     </div>     
</div><br>
<div class="row">
     
                  
         
        <div class="col-lg-3">
            <label> CT Scan</label><br>
            <input type="radio" value="Yes"<?php if(isset($show->CT_Scan) && $show->CT_Scan=='Yes'){ echo'Checked'  ;} ?>  name="CT_Scan" >Yes
             <input type="radio" value="No"<?php if(isset($show->CT_Scan) && $show->CT_Scan=='No'){ echo'Checked'  ;} ?> name="CT_Scan" >No
                                         </div>
</div> 



    <div class="col-lg-12">
        
        <input type="submit" class="btn  btn-success pull-right" value="Save"> </div>
    
        
</form>  
        
        
        
    
