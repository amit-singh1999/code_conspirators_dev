

@extends('layouts.app')

@section('content')
@include('layouts.headers.cards')
<div class="container-fluid mt--7" style="background-color:#DCDCDC">
    <div class="row">
        <div class="col-xl-12 mb-5 mb-xl-0">
            
            
            <div class="card bg-gradient-default shadow" style="margin-top:16px">
                        <div class="card-header bg-transparent">
                            <div class="row align-items-center">
                                <div class="col" style="padding-top:10px;padding-bottom:10px;">
                                    <h1><b>Support Tickets Archive</b></h1>
                                                 <div class="container" style="margin-top:-10px;margin-right:0px;" >
                                    <div   class="row" style="float:right;margin-top: -62px">
                                           <button  type="button" class="btn btn-success mt-4" data-toggle="modal" data-target="#exampleModalCenter">NEW REQUEST</button>	
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>                                    
                        <div class="card-body" style="padding-bottom: 10px;">
                            <div class="chart">
                                <?php	
                                $check =0;
                                foreach ($ticketstatus as $resp) { 
                                      if ($resp['stageId'] == 104 || $resp['status'] == 5) {
                                     $close_date = new \DateTime($resp['closedDate']);
                                    $close_date = $close_date->format('M d, Y');

                                     // $time = new \DateTime($resp['closedDate']
                                     $today_date = new \DateTime();
                                      $today_date = $today_date->format('M d, Y');

                     $diff = abs(strtotime($today_date) - strtotime($close_date));
                   if($diff<30)
                    continue;
                }
                if ($resp['stageId'] == 0 && $resp['status'] != 5)
                continue;
                $check =1;
                                ?>	
                                <div class="container" style="margin-left:0">	
                                    <div class="row">	
                                        <div class="d-inline">	
                                            <h3 class="d-inline"><?php echo $resp['title']; ?> </h3>
                                            <?php $time = new \DateTime($resp['createdDate']);
                                                $date = $time->format('M d, Y');
                                                $time = $time->format('H:i a');
                                            ?>
                                            <p class="d-inline"><?php echo "Submitted ".$date.' at '.$time; ?></p>		 
                                            <?php	
                                            if ($resp['stageId'] == 0 && $resp['status'] != 5) {	
                                                echo "<p class='d-inline' style='color:green'>New</p>";	
                                            } elseif ($resp['stageId'] == 102 && $resp['status'] != 5) {	
                                                echo "<p class='d-inline' style='color:green'>In Progress</p>";	
                                            } elseif ($resp['stageId'] == 104 || $resp['status'] == 5) {
                                                echo "<p class='d-inline' style='color:grey'>Resolved</p>";	
                                            } else {	
                                                echo "<p class='d-inline' style='color:Orange'>Archive</p>";	
                                            }	
                                            ?>
                                        </div>	
                                    </div>	
                                    <div style="margin-left:-15px">	
                                      <p> <?php echo $resp['description']; ?> </p>	
                                    </div>	
                                </div>	
                                <?php } 
                                if($check==0){
                                    echo "<p>You have no active Support Tickets. You can request assistance at the button below.</p>";
                                }

                                ?>	
                            </div>
                            <div></div>
                            <!--<a class="archive" href="{{ route('support') }}" style="font-size:16px">Support Ticket Archives</a>-->
                            <!--<button type="button" style="margin-bottom: 15px;" class="btn btn-primary btn ticket" data-toggle="modal" data-target="#exampleModalCenter">NEW REQUEST</button>	-->
                        </div>	
                </div>
                
                
                
            
            
           
           
           
                 <!-- Modal  raise ticket-->
               <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <form action="{{ route('ticketData') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">New Request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body ">

                            
                            <div style="margin-top:10px;" class="row">
                                <label for="exampleFormControlTextarea2" class="col-sm-4 col-form-label">Project</label>
                                <div class="col-sm-8"> 
                                
                                <select  class="form-select form-control" aria-label="Default select example" name="projectname">
                                <option selected>Select your project</option>
                                <?php
                                for ($i = 0; $i < count($projects->result); $i++) {
                                    for ($k = 0; $k < count($project); $k++) {
                                        if ($projects->result[$i]->ID == $project[$k]->project_id) {       ?>
                                            <option value="<?php echo $projects->result[$i]->NAME;  ?> "><?php echo $projects->result[$i]->NAME;  ?></option>
                                <?php  }
                                    }
                                }
                                ?>

                            </select>
                                
                                 </div>
                            </div>
                            <div style="margin-top:10px;" class="row">
                                <label for="exampleFormControlTextarea2" class="col-sm-4 col-form-label">Title</label>
                                <div class="col-sm-8"> <input type="text" name="issuetitle" class="form-control" placeholder="Title" /> </div>
                            </div>
                            <div style="margin-top:10px;" class="row">
                                <label for="exampleFormControlTextarea2" class="col-sm-4 col-form-label pt-0">Description</label>
                                <div class="col-sm-8 "> <textarea class="form-control rounded-0" id="exampleFormControlTextarea2" name="describeissue" rows="3 " placeholder="Describe your Request"></textarea></div>
                            </div>
                
                            <div style="margin-top:10px;" class="row">
                                <label for="exampleFormControlTextarea2" class="col-sm-4 col-form-label">File Attachment</label>
                                <div class="col-sm-8"> <input class="form-control" type="file" id="img" name="img" accept="image/*"></div>

                            </div>

                            <fieldset class="form-group" style="margin-top:10px;">
                                <div class="row">
                                    <legend class="col-form-label col-sm-4 pt-0">Select Issue</legend>
                                    <div class="col-sm-8">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="Default" name="flexRadioDefault" id="flexRadioDefault" checked="checked">
                                            <label class="form-check-label" for="flexRadioDefault">
                                                Default
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value=" Website is down" name="flexRadioDefault" id="flexRadioDefault1">
                                            <label class="form-check-label" for="flexRadioDefault1">
                                                Website is down
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="Website is running but there's an issue with the site" name="flexRadioDefault" id="flexRadioDefault2">
                                            <label class="form-check-label" for="flexRadioDefault2">
                                                Website is running but there's an issue with the site
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="I have a request for a new feature" name="flexRadioDefault" id="flexRadioDefault3">
                                            <label class="form-check-label" for="flexRadioDefault3">
                                                I have a request for a new feature
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="This issue is with a 3rd party service (Pardot ,Pharpspring ,Google ,etc)" name="flexRadioDefault" id="flexRadioDefault4">
                                            <label class="form-check-label" for="flexRadioDefault4">
                                                This issue is with a 3rd party service (Pardot, Sharpspring, Google, etc)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="This relates to an Ongoing Project" name="flexRadioDefault" id="flexRadioDefault5">
                                            <label class="form-check-label" for="flexRadioDefault5">
                                                This relates to an Ongoing Project
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="I'm having an issue with my Email Account" name="flexRadioDefault" id="flexRadioDefault6">
                                            <label class="form-check-label" for="flexRadioDefault6">
                                                I'm having an issue with my Email Account
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="Other" name="flexRadioDefault" id="flexRadioDefault7">
                                            <label class="form-check-label" for="flexRadioDefault7">
                                                Other
                                            </label>
                                        </div>


                                    </div>
                                </div>
                            </fieldset>

                            <div style="margin-top:10px;" class="row">
                                <label for="exampleFormControlTextarea2" class="col-sm-4 col-form-label">URL where the issue occurs</label>
                                <div class="col-sm-8 "> <input type="text" name="issueURL" class="form-control" placeholder=" Copy/Paste the URL from the address bar of your browser window   " /></div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" style="background-color:#c72027;text-align:center;border:unset;" class="btn btn-primary customcolorformoodle">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

            </div>
        </div>
    </div>
</div>
</div>
@include('layouts.footers.auth')
@endsection

@push('js')


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
// $('footer').css('position', 'absolute');
//  $('footer').css('width', '100%');
 
});
</script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush