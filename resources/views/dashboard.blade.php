<?php
    $archievetoday = date('Y-m-d', strtotime("-30 days"));
?>
@extends('layouts.app')

@if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                
@include('event')
@section('content')
  @include('layouts.headers.cards') 
    
    <div class="container-fluid mt--7" style="background-color:#DCDCDC">
        <div class="row subal">
            <div class="col-xl-8 mb-5 mb-xl-0">
                <div class="icard-bottom card bg-gradient-default shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h1><b>Messages</b></h1>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body" style="padding-bottom: 10px;">
                        <div class="chart">
                                <?php
                                $check =0;
                                        for($i=0;$i<count($msg);$i++){
                                            if($msg[$i]){
                                                for($j=0;$j<count($msg[$i]);$j++){
                                                    $msg_date = new \DateTime($msg[$i][$j]['date']);
                                                    $msg_date = $msg_date->format('Y-m-d');
                                                    if($archievetoday < $msg_date){
                                                       // echo "<h3>".$msg_date."</h3><br>";
                                                    }
                                                    else{
                                                        break;
                                                    }
                                                    $check = 1;
                                                    $time1 = new \DateTime($msg[$i][$j]['date']);
                                                    $date = $time1->format('M d, Y');
                                                    
                                                     if (!empty($msg[$i][$j]['detailtext'])) {
                                                        echo "<p class='d-inline' style='font-size:16px'><b>".$msg[$i][$j]['detailtext']."</b></p>";
                                                     
                                                    }
                                                    
                                                     echo "<p  class='d-inline' style='color:#999999;font-size:12px'>". "    " .$date."</p><br><br>";
                                                 
                                                   
                                                    if (!empty($msg[$i][$j]['youtubelink'])) {
                                                    $link="https://";
                                                    $link=$link.$msg[$i][$j]['youtubelink'];
                                                    echo '<div class="iframe-container" style="margin-bottom:30px">
                                                       <iframe class="custom_image_sizing1" id="custom_image_sizing" src="'.$link.'" frameborder="0" style="overflow: hidden; height: 100%; width: 100%; position: absolute;" height="100%" width="100%"></iframe>
                                                    </div>';
                                                    }
                                                    if($msg[$i][$j]['file']){ 
                                                        $dir = '/files'."/".$msg[$i][$j]['file'];
                                                        ?>
                                                        <div class="iframe-container">
                                                            <iframe class="custom_image_sizing1" id='custom_image_sizing' src="{{asset($dir)}}" frameborder="0" style="overflow: hidden; height: 100%; width: 100%; position: absolute;" height="100%" width="100%"></iframe>
                                                        </div>
                                                        <br>
                                                    <?php
                                                    
                                                    }
                                                }
                                            }
                                        }
                                        if($check == 0){
                                            echo "<p>You have no active Messages at this time. Check back soon!</p>";
                                        }
                                    ?>
                                    <a class="archive" href="{{ route('message') }}" style="font-size:16px">Messages Archives</a>
                        </div>
                    </div>
                </div>
                <div class="card bg-gradient-default shadow icard-bottom">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h1><b>Active Projects</b></h1>
                                
                            </div>
                        </div>
                    </div>                  

                    <div class="card-body" style="padding-bottom: 10px;">
                        <div class="chart">
                       
                            <?php
                           // dd($projects);
                            $check =0;
                                for($i=0;$i<count($projects->result);$i++){
                                    
                                    for($k=0;$k<count($project);$k++){
                                if($projects->result[$i]->ID == $project[$k]['project_id'] && $projects->result[$i]->CLOSED !="Y"){
                                $check =1;
                                $today_date = new \DateTime();
                                $today_date = $today_date->format('M d, Y');
                               
                               $today_date_1 = strtotime($today_date);
                                $project_date_1 = strtotime($project[$k]['end_date']);
                            
                                if($project_date_1>$today_date_1)
                                {
                                   $bg ="green";
                                   $msg =" On Time";
                               }
                               else{
                                   $bg ="red";
                                   $diff = abs(strtotime($today_date) - strtotime($project[$k]['end_date']));
                                   $days = $diff/60/60/24;
                                  // $years = floor($diff / (365*60*60*24));
                                  // $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                                  // $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));


                                   $msg =" Project is ".$days." days late";
                               }
                               
                               
                               echo "<h3 class='d-inline'>".$projects->result[$i]->NAME."</h3><p class='d-inline'> Started ".$project[$k]['start_date']."</p><p class='d-inline' style='color:".$bg."'>".$msg."</p>";
                                $percent=0;
                                 $var=0;
                                 $var1=0;

                             //  dd($task_list);
                                   
                            for($j=0;$j<count($task_list->result->tasks);$j++){
                                
                                        
                          if($task_list->result->tasks[$j]->groupId == $project[$k]['project_id'])
                             {
                                  $project[$k]['project_id'].$task_list->result->tasks[$j]->groupId; 
                                 // echo "<h4>".date('m',strtotime($task_list->result->tasks[$j]->createdDate))."</h4>";  
                                 //
                                 if(date('m',strtotime($task_list->result->tasks[$j]->createdDate)) == date('m'))
                                            $var++;
                                if($task_list->result->tasks[$j]->status==5 )
                                 {
                                 if(date('m',strtotime($task_list->result->tasks[$j]->createdDate)) == date('m'))
                                   $var1++;
                                 }
                                                    
                                    }    
                                }
                                    if($var == 0){
                                        $percent ="100%";
                                    }
                                    else{
                                     $percent=$var1*100/$var;   
                                    }
                                       
                                    ?>
                                    <div  class="progress" style="height:20px">
                                        <div class="percent" style="width:<?php echo $percent."%"; ?>;background:<?php echo $bg ?>">
                                            <p style="width:max-content; margin:3px 15px;"></p>
                                        </div>
                                    </div>
                            
                            <?php
                            $b_status=$task_list->result->tasks[$i]->status;
                             $a=count($task_list->result->tasks);
                          
                             echo "Tasks created this month: (".$var.")"."<p style='float:right'>Due:".$project[$k]['end_date']."</p><br>";
                             echo  "Tasks completed this month: (".$var1.")"."<br><br>";
                                }    
                            }
                            }
                                if($check == 0){
                                            echo "<p>You have no active Projects at this time. Start something new!</p><a href='https://b24-5byepi.bitrix24.site/portal-new_project/' target='_blank'>Click Here</a><br>";
                                        }

                             ?>
                            <a class="archive" href="{{ route('project') }}" style="font-size:16px">Project Archives</a>
                            
                            <div class="container mt-5" >
                                <!--<h2 class="h2 text-center mb-5 border-bottom pb-3">Laravel FullCalender CRUD Events Example</h2>-->
                                <div class="spinner-border" id="calendar_loader"></div>
                                
                                <div style="margin-left:-15px;margin-right:-15px;" id='full_calendar_events'></div>
                                   <!-- modal of calendar starts here -->
                            <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body" id="modalselector">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                
                                            <h1 id="eventtitle"></h1>                              
                                            <div id="container"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- modal of calendar ends here -->
                            
                            </div>
                             <div class="modal fade" id="successModalhere" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body" id="modalselector">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                              <h1 id="eventtitle1"> </h1>   
                                              <h1 id="eventtitle2"> </h1>   
                                              <p id="eventdescription"> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="container imbar" style="margin-top:10px">
                                <div class="row">
                                    <div class="col">
                                     <a class="archive" href="{{ route('project') }}" style="font-size:16px;margin-left:-15px;">View All Upcoming Events</a>
                                    </div>
                                    <div class="col">
                                    <a href="https://codeconspirators.as.me/schedule"><button style="float:right;margin-right:-15px;" type="button" class="btn btn-success mt-4">SCHEDULE A CALL</button></a>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="card bg-gradient-default shadow">
                        <div class="card-header bg-transparent card-bar">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h1><b>Support Tickets</b></h1>
                                                 <div class="container" style="margin-top:-10px" >
                                    <div   class="row" style="float:right;margin-top:-30px!important">
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
                                   if($diff>30)
                                    continue;
                                    }
                                $check =1;
                                ?>	
                                <div class="container">	
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
                                    <div style="margin-left:-2px">	
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
                            <a class="archive" href="{{ route('support') }}" style="font-size:16px">Support Ticket Archives</a>
                            <!--<button type="button" style="margin-bottom: 15px;" class="btn btn-primary btn ticket" data-toggle="modal" data-target="#exampleModalCenter">NEW REQUEST</button>	-->
                        </div>	
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card shadow show-card-bar">
                            
                     <h2><b>Upcoming Events</b></h2>
                    <div class="card-body" >
                        <div class="chart">
                            <?php
                            $check =0;
                            $upcoming =1;
                                for($i=0;$i<count($meeting_details);$i++){
                                    for($j=0;$j<count($meeting_details[$i]);$j++){
                                        $check=1;
                                         if( $upcoming<4){
                                         echo $meeting_details[$i][$j]['name']."<br>";
                                         echo $meeting_details[$i][$j]['date_from']."<br>"; ?>
                                         <a target="_blank" href="<?php echo $meeting_details[$i][$j]['link']; ?>">Link</a><br></br>
                                      
                                        <?php $upcoming++;  } ?>
                                <?php } }
                                if($check == 0){
                                               echo '<p>You have no upcoming events.</p><a href="https://codeconspirators.as.me/schedule" target="_blank">Schedule a call!</a>';
                                    //  echo "<p class="row">You have no upcoming events.</p><a href='https://codeconspirators.as.me/' target='_blank'><p  class="row">Schedule a call!<p></a>";
                                } 
                            ?> 
                        </div>
                    </div>   
                </div>            
                <div class="card shadow show-card-bar">

                            
                        <h2><b>Due From You</b></h2>

                    <div class="card-body">
                        <div class="chart">
                            <?php
                                $check =0;
                                for($i=0;$i<count($project);$i++){
                                    for($j=0;$j<count($things->result->tasks);$j++){
                                    if($things->result->tasks[$j]->groupId == $project[$i]['project_id']){
                                        $check = 1;
                                       // print_r($things);
                                    echo "<p style='margin:0'>".$things->result->tasks[$j]->title."</p>";  ?>
                                    <p hidden  id="hello2" class="hello2"><?php echo $things->result->tasks[$j]->id ;?></p>
                                      <p hidden id="hello3"><?php echo $things->result->tasks[$j]->title; ?></p>
                                       <p hidden id="hello4"><?php echo $things->result->tasks[$j]->description; ?></p>
                                       <a class="btn-sm-active-suscribe" data-toggle="modal" data-target="#viewtaskmodal" href="#">View Task</a><br><br>
                                   <?php  }
                                    }
                                }
                                if($check == 0){
                                    echo "<p>Looks like we have everything we need for the moment! </p>";
                                }
                            ?>     
                        </div>
                    </div>
                </div>
                <div class="card shadow show-card-bar">

                    <h2><b>Open Invoices</b></h2>
                    <div class="card-body"  style="padding-bottom: 10px;">
                        
                           <div class="chart" >
                            <?php
                            if(!empty($invoice_result['data']['QueryResponse'])){
                                
                            if($invoice_result['data']):
                            $invoices =json_decode(json_encode($invoice_result['data']),true);
                            $invoices = $invoices['QueryResponse']['Invoice'];
                            $total = 0;
                            foreach($invoices as $invoice):
                           ?>
                        
                            <h4 style="margin:auto"> <?php echo $invoice['DocNumber'];?></h4>
                            <p style="display: inline"> Due:  <?php echo $invoice['DueDate'];?> </p>
                            <p style="display: inline-flex;margin-left:30px;margin-bottom:0">  
                                $<?php 
                                    echo $invoice['TotalAmt'];
                                    $total=$total+$invoice['TotalAmt'];
                                ?>
                            </p>
                            <a class="d-block" href="{{ $invoice['InvoiceLink'] }}" target="_blank">Click here to Pay</a><br>
                        <?php endforeach; ?>
                            
                            <h4 style="margin:auto">Total:$ <?php echo $total ; ?></h4>
                        <?php else: ?>
                            <p>You have no open invoices -- you ROCK!!</p>
                        <?php endif;   
                           }else
                           echo  '<p>You have no open invoices -- you ROCK!!</p>';
                          ?>
                        </div>
                        
                    </div>   
                </div>            
                <div class="card shadow show-card-bar">          
                    <h2><b>Resources</b></h2>
                    <div class="card-body">
                        <div class="chart">
                            <p>Resources are coming soon.</p>
                            
                        </div>
                    </div>   
                </div>            
                
            <!--<div class="card shadow show-card-bar">
                <h2><b>Links</b></h2>
                 <div class="card-body">
                    
                    <div class="spinner-border" id="Link_loader1"></div>
                    <div class="chart" style="display:none" id="link_willshow">
                        
                        <div class="row">
                            <div class="col">
                                <div class="circle_uptime   color_green"></div>
                            </div>
                            <div class="col" style="margin-top: 6px;margin-left: -107px;">
                                <div style="font-size: 35px;color: #66d329;font-weight: 600;">
                                    Up
                                </div>
                              
                                <div id="siteupSinceTime">
                                    
                                </div>
                            </div>
                        </div>
                        <div id="uptimerobotactive_different_days">
                        </div>
                    </div>
                    <!-- chart closed-->
                    
                <!--</div>
            </div>-->
            </div>
            </div>
            	
            <!-- Modal -->	
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <form action="{{ route('ticketData') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle"><h2>New Request</h2></h5>
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
                                        if ($projects->result[$i]->ID == $project[$k]['project_id']) {       ?>
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

                            <fieldset class="form-group" style="margin-top:10px;" >
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
                                            <input class="form-check-input" type="radio" value="This issue is with a 3rd party service (Pardot, Pharpspring, Google,etc)" name="flexRadioDefault" id="flexRadioDefault4">
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
                            <button type="submit" style="background-color:#c72027;text-align:center;" class="btn btn-primary customcolorformoodle">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
            <div class="modal fade" id="viewtaskmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('store.thingsweneed') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><h2>Due From You</h2></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body ">
                        <div class="row">
                            
                            <div class="col "> <input type="hidden" name="taskid" class="form-control" id="service_id" value="" placeholder="Enter name" readonly></div>
                        </div>
                         <div class="row">
                              <label for="" class="col-sm-4 col-form-label">Task</label>
                            <div class="col "> <div  id="taskname_detail" style="margin-top: 9px;" ></div>
                        </div>
                         <div class="row">
                              <label for="" class="col-sm-4 col-form-label">Task Description</label>
                            <div class="col "> <div  id="taskname_describe" style="margin-top: 9px;" ></div>
                        </div>
                        <div style="margin-top:10px;" class="row">
                            <label for="exampleFormControlTextarea2" class="col-sm-4 col-form-label">Comments</label>
                            <div class="col-sm-8"> <textarea class="form-control rounded-0" id="exampleFormControlTextarea2" name="taskcomment" rows="3 " placeholder="Add Comment"></textarea></div>
                        </div>
                        <div style="margin-top:10px;" class="row">
                            <label for="taskimg" class="col-sm-4 col-form-label">File Attachment</label>
                            <div class="col-sm-8 "> <input class="form-control" type="file" id="taskimg" name="imgforthingsweneed" accept="image/*" required></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" style="background-color:#c72027;text-align:center;border:unset" class="btn btn-primary customcolorformoodle">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
</div>        
    </div>
@include('layouts.footers.auth')
@endsection

@push('js')

<script>
    $('.custom_image_sizing1').on('load', function() {
        $('.custom_image_sizing1').contents().find('img').css("width", "100%");
        $('.custom_image_sizing1').contents().find('img').css("height", "100%");
        var images = $('.custom_image_sizing1').contents().find('img');
        console.log(images);
    });
</script>
<script>
    UptimeRobot();
    function UptimeRobot() {
        console.log("12");
        function convertHMS(value) {
            console.log("inside");
            const sec = parseInt(value, 10); // convert value to number if it's string
            let hours = Math.floor(sec / 3600); // get hours
            let minutes = Math.floor((sec - (hours * 3600)) / 60); // get minutes
            let seconds = sec - (hours * 3600) - (minutes * 60); //  get seconds
            // add 0 if value < 10; Example: 2 => 02
            if (hours < 10) {
                hours = "0" + hours;
            }
            if (minutes < 10) {
                minutes = "0" + minutes;
            }
            if (seconds < 10) {
                seconds = "0" + seconds;
            }
            return 'Since ' + hours + ' hrs ' + minutes + ' mins '; // Return is HH : MM : SS
        }
        $.ajax({
            type: 'GET',
            url: "/UptimeRobot",
            beforeSend: function() {
            },
            success: function(data) {
                var all_time_uptime_durations = data.result.all_time_uptime_durations;
                var custom_uptime_ratio = data.result.custom_uptime_ratio;
                var text = custom_uptime_ratio;
                var uptimedifferentdays = text.split("-");
                var htmldata = '';
                var inlineflexdiv = ' style="display:flex"';
                var Ptaginside_link_style =' style="margin-left: 10px;margin-top: -1px;"';
                var htaginside_link_style = ' style="font-size: 15px;color: #66d329;"';
                htmldata += "<div"+ inlineflexdiv +"><h4"+htaginside_link_style+">" + uptimedifferentdays[0] + "%" + "</h4><p"+Ptaginside_link_style + ">(last 24 hours)</p></div> ";
                htmldata += "<div "+ inlineflexdiv +"><h4"+htaginside_link_style+">" + uptimedifferentdays[1] + "%" + "</h4><p"+Ptaginside_link_style + ">(last 7 days)</p> </div>";
                htmldata += "<div "+ inlineflexdiv +"><h4 "+htaginside_link_style+">" + uptimedifferentdays[2] + "%" + "</h4><p"+Ptaginside_link_style + ">(last 30 days)</p> </div>";
                var subal = document.getElementById('uptimerobotactive_different_days');
                subal.innerHTML = htmldata;
                var uptime_alll_time = all_time_uptime_durations.split("-")[0];
                var alluptime_robot_time = document.getElementById('siteupSinceTime');
                alluptime_robot_time.innerHTML =convertHMS(uptime_alll_time) ;
                // console.log(convertHMS(uptime_alll_time));
                // console.log(data);
            },
            complete: function(data) {
                // $("#resource_loader").hide();
                console.log("after completet");
                $("#Link_loader1").hide();
                $("#link_willshow").show();

            }
        });
    }
</script>


<script>	
    $(document).ready(function() {	
        $('.btn-sm-active-suscribe').on('click', function() {
            var title = $(this).prev().prev().text();
             var data = $(this).prev().prev().prev().text();
             //alert(data);
            var describe = $(this).prev().text();	
            $('#taskname_detail').text(title);
            $('#taskname_describe').text(describe);
          $('#service_id').val(data);
           $('#viewtaskmodal').modal('show');	
           
        });	
    });	
</script>
<script>
        $(document).ready(function () {
            calendar_fetch();
        });
</script>
   <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush