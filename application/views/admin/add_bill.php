<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Assemblify Dashboard | <?php if($page==1){echo "Edit";}else {{echo "Add bill";}} ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo  site_url('application/views')?>/styles.css">

</head>
<body onload ="getTermlegislators()"> 
<div class="container">
<nav class="navbar navbar-expand-xl navbar-dark shadow-sm p-3 mb-5 bg-secondary rounded">
    <a class="navbar-left" href="#">
        <a href="<?php echo site_url('bills/getBills/all/all'); ?>"><img src="<?php echo site_url('application/views/logo.svg'); ?>" class="logo" alt=""></a>
  </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbars" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse float-right" id="navbars">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
            <a class="nav-link" style="<?php if($page==1){echo 'color:white';}?>" href="<?php echo site_url('admin/dashboard');?>"> Manage Bills</a>
          </li>
            <li class="nav-item">
            <a class="nav-link" style="<?php if($page!=1){echo 'color:white';}?>" href="<?php echo site_url('admin/dashboard/addBill');?>">Add Bill</a>
          </li>
              <li class="nav-item">
            <a class="nav-link" href="<?php echo site_url('admin/dashboard/addLegistlator');?>">Add Legistlator</a>
          </li>
            <li class="nav-item">
            <a class="nav-link" href="<?php echo site_url('admin/dashboard/managelegislators');?>">Manage legislators</a>
          </li>         
        </ul>
      </div>
    <a href="<?php echo site_url('auth/logout');?>" class="btn btn-light">Logout</a>
</nav>
</div>
    
<div class="container" >
<!--<form method ="post"> -->
<?php echo form_open_multipart(''); ?>
    <div class ="row">
    <div class ="col-md-2">
            <div class="form-group">
                <label><b>Chamber</b></label>
                 <select class ="form-control" name="origin" id="origin" onchange="getTermlegislators()">
                     <option value="House" <?php if($page==1){if($bill['bill_origin']=='House') {echo 'selected' ;}} ?>>House</option>
                      <option value="Senate" <?php if($page==1){if($bill['bill_origin']=='Senate') {echo 'selected' ;}} ?>>Senate</option>
                </select>
            </div>
        </div>

        <div class ="col-md-2">
            <div class="form-group">
                <label><b>Type</b></label>
                 <select class ="form-control" name="type" id="type">
                     <option value="Member" <?php if($page==1){if($bill['bill_type']=='Member') {echo 'selected' ;}} ?>>Member</option>
                      <option value="Private" <?php if($page==1){if($bill['bill_type']=='Private') {echo 'selected' ;}} ?>>Private</option>
                     <option value="Executive" <?php if($page==1){if($bill['bill_type']=='Executive') {echo 'selected' ;}} ?>>Executive</option>
                </select>
            </div>
        </div>

        <div class ="col-md-2">
            <div class="form-group">
                <label><b>Transmitted</b></label>
                 <select class ="form-control" name="trans" id="trans">
                     <option value="False" <?php if($page==1){if($bill['bill_trans']=='false') {echo 'selected' ;}} ?>>False</option>
                      <option value="True" <?php if($page==1){if($bill['bill_trans']=='true') {echo 'selected' ;}} ?>>True</option>
                </select>
            </div>
        </div>

         <input type="hidden" id="billImagename" name="billImagename" value="<?php if($page==1) {echo $bill['bill_imagename'];}?>">
         
        <div class ="col-md-4">
            <div class="form-group">
                <label><b>Assembly</b></label>
                <select class ="form-control" name="billTerm" id="billTerm"  onchange="getTermlegislators()">
                <?php  foreach($info as $rep):?>
                      <option value="<?php echo $rep?>" <?php if($page==1){if($bill['bill_term']==$rep) {echo 'selected' ;}}?>>
                          <?php echo $rep?>
                      </option>
                  <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class ="col-md-2">
            <div class="form-group">
                <label><b>Status</b></label>
                 <select class ="form-control" name="billStatus" id="billStatus">
                     <option value="In consideration">In Consideration</option>
                      <option value="Passed">Passed</option>
                     <option value="Thrown out">Thrown Out</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class ="row">
        <div class ="col-md-4">
            <div class="form-group"> 
                <label><b>Sponsor</b></label>
                <select class ="form-control" name="billSponsor" id="billSponsor">  
                </select>
            </div>
        </div>
        <div class ="col-md-4">
            <div class="form-group">
                <label><b>Number</b></label> <input type = "text" class ="form-control" name="billNumber" value="<?php if($page==1){echo $bill['bill_number'] ;}?>" required/>
            </div>
        </div>
        <div class ="col-md-4">
            <div class="form-group">
                <label><b>Full Text</b></label>
                <input type = "text" class ="form-control" name="billFulltext" value="<?php if($page==1){echo $bill['bill_fulltext'] ;}?>" required/>
            </div>
        </div>
    </div>
    
    <div class ="row">
        <div class="col-md-6">
            <div class="form-group">
                <label><b>Question</b></label><textarea rows="4" cols="50" maxlength="200" class ="form-control" name="billQuestion" value=" "required><?php if($page==1){echo $bill['bill_question'] ;}?></textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label><b>Title</b></label><textarea rows="4" cols="50" maxlength="250" class ="form-control" name="billTitle" value="" required><?php if($page==1){echo $bill['bill_title'] ;}?></textarea>
            </div>
        </div>
    </div>
    
    <div class ="row">
        <div class ="col-md-4">
           <div class="form-group">
               <label><b>Tag 1</b></label>
                <select class ="form-control" name="billTag1" id="billTag1" required ></select>
            </div>
        </div>
        <div class ="col-md-4">
            <div class="form-group">
                <label><b>Tag 2</b></label>
                <select class ="form-control" name="billTag2" id="billTag2"></select>
            </div>
        </div>
        <div class ="col-md-4">
            <div class="form-group">
                <label><b>Tag 3</b></label>
                <select class ="form-control" name="billTag3" id="billTag3"></select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12 form-group">
            <label><b>Official Summary</b></label>
            <textarea class ="form-control" name="billSummary" id="summary" rows='6' value="" required> <?php if($page==1){echo $bill['bill_summary'] ;}?></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label><b>Key Points</b></label>
                
            <textarea class ="form-control" name="key_points" id="key_points" rows='6' value=""> <?php if($page==1){echo $bill['bill_key_points'] ;}?></textarea>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label><b>First Reading</b></label>
                <input type = "date" class ="form-control" name="billFirstreading" value="<?php if($page==1){echo $bill['bill_firstreading'] ;}?>"required/>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label><b>Second Reading</b></label>
                <input type = "date" class ="form-control" name="billSecondreading"/>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label><b>Reffered to:</b></label>
                <select class ="form-control" name="billCommittee" id="billCommittee">
                  <?php foreach($committees as $com):?>
                      <option value="<?php echo $com['id']?>">
                          <?php echo $com['com_name']?>
                      </option>
                  <?php endforeach;?>
                </select>
            </div>
        </div>
    </div>
    
    <div class ="row">
        <div class="col-md-3">
            <div class="form-group">
                <label><b>Report out of Committee</b></label>
                <input type = "date" class ="form-control" name="billReportoutofcommittee" value="<?php if($page==1){echo $bill['bill_committeereport'] ;}?>"/>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label><b>Third Reading</b></label>
                <input type = "date" class ="form-control" name="billThirdreading"/>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label><b>Remarks</b></label>
                <input type ="text" class ="form-control" name="billRemarks"/>
            </div>
        </div>
    </div>    
    
    <div class="form-group">
        <label><b>Bill Image</b></label><br/>
        <input type="file" name="billimage" id="billimage"/>
        <img id="image_upload_preview" src="<?php if($page==1){echo site_url('application/uploads/').$bill['bill_img'].'?dummy=8484744';}else{echo 'http://placehold.it/100x100';}?>" alt="your image" /> 
    </div>    
        
<input class="btn btn-secondary" name="save" id="save" class="form-control" type="submit" value="Save"/>
<div class="container">
    <div id="load_data"></div>
    <div id="load_data_message">
    </div></div>
    </div>
    
    
</body>
    
    <script type="text/javascript">
    function updateTags()
    {
        var tag1 =""+<?php if ($page==1){echo '"'.$bill['bill_tag1'].'"';} else echo '""';?>;
        getTags(tag1, 'billTag1', 'tag1');
        var tag2 =""+<?php if ($page==1){echo '"'.$bill['bill_tag2'].'"';} else echo '""';?>;
        getTags(tag2, 'billTag2', 'tag2');
        var tag3 =""+<?php if ($page==1){echo '"'.$bill['bill_tag3'].'"';} else echo '""';?>;
        getTags(tag3, 'billTag3','tag3');
    }
    
    function getTags(selected, tag_id, column){
        $.ajax({
        url:"<?php echo base_url(); ?>admin/dashboard/getOptionsFromInfo/",
        method:"POST",
        data:{selected: selected,column:column},
        cache: false,
        success:function(data){
            if(data == '')
          {
            $('#load_data_message').html('<h3>No Tags Found</h3>');
            action = 'active';
            console.log("no"+chamber);
          }
          else
          {
            console.log(data);
            console.log('data');
               $('#'+tag_id).html("");
             $('#'+tag_id).append(data);
            action = 'inactive';}
        }})}
        
        
    function getTermlegislators()
    {
        var term = $("#billTerm option:selected" ).text();
        term= $.trim(term);
        var sponsor =""+<?php if ($page==1){echo '"'.$bill['bill_sponsor'].'"';} else echo '""'?>;
        var chamber ="";
        var e = document.getElementById("origin");
        chamber = e.options[e.selectedIndex].value;
        
        console.log("its:"+chamber);
        
        $.ajax({
        url:"<?php echo base_url(); ?>admin/dashboard/getTermlegislators/",
        method:"POST",
        data:{term: term, sponsor: sponsor, chamber:chamber},
        cache: false,
        success:function(data){
            if(data == '')
          {
            $('#load_data_message').html('<h3>No More Result Found</h3>');
            action = 'active';
              console.log("no"+chamber);
          }
          else
          {
            console.log(data);
            console.log('data');
               $('#billSponsor').html("");
             $('#billSponsor').append(data);
            action = 'inactive';}
        }})}
        
        function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#image_upload_preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#billimage").change(function () {
        readURL(this);
    });
        $(document).ready(function() {
        var access = '<?php echo $access; ?>';
        $('form *').prop('disabled', true);
    
        if(access=="all"){
           $('form *').prop('disabled', false);
        }
        else if (access=="summary"){
            $('#summary').prop('disabled', false);
            $('#save').prop('disabled', false);
        }
        else if(access=="media"){
            console.log("inside");
            $('#billimage').prop('disabled', false);
            $('#save').prop('disabled', false);
            $('#billImagename').prop('disabled', false);
        }
        
        updateChamber();
            updateTrans();
            updateTags();
    });
        function updateChamber(){
        if($('#s').is(':checked')||$('#h').is(':checked')) {} else {$('#h').attr('checked','checked');}
    }
         function updateTrans(){
        if($('#trans_true').is(':checked')||$('#trans_false').is(':checked')) {} else {$('#trans_false').attr('checked','checked');}
    } 
    </script>
</html>
