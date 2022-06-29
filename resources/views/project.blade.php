@extends('layouts.app')

@section('content')
  @include('layouts.headers.cards') 
  <div class="container-fluid mt--7" style="background-color:#DCDCDC">
        <div class="row">
            <div class="col-xl-12 mb-5 mb-xl-0">
                <div class="card bg-gradient-default shadow" style="margin-bottom:6%">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2><b>Project Archives</b></h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    <div class="chart">

                        <?php
                            $check =0;
                                for($i=0;$i<count($projects->result);$i++){
                                    
                                    for($k=0;$k<count($project);$k++){
                                if($projects->result[$i]->ID == $project[$k]['project_id'] ){
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
                                   $years = floor($diff / (365*60*60*24));
                                   $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                                   $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));


                                   $msg =" Project is ".$days." days late";
                               }
                               
                               
                               echo "<h3 class='d-inline'>".$projects->result[$i]->NAME."</h3><p class='d-inline'> Started ".$project[$k]['start_date']."</p><p class='d-inline' style='color:".$bg."'>".$msg."</p>";
                                $percent=0;
                                 $var=0;
                                 $var1=0;

                                   
                            for($j=0;$j<count($task_list->result->tasks);$j++){
                                        
                          if($task_list->result->tasks[$j]->groupId == $project[$k]['project_id'])
                             {
                                 // echo "<h4>".$task_list->result->tasks[$j]->title."</h4>"; 
                                 $var++;
                                if($task_list->result->tasks[$j]->status==5 )
                                 {
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
                          
                             echo "Tasks created this month: (".$var.")"."<p style='float:right'>Due: ".$project[$k]['end_date']."</p><br>";
                             echo  "Tasks completed this month: (".$var1.")"."<br><br>";
                                }    
                            }
                            }
                                if($check == 0){
                                            echo "<h4>You have no active Projects at this time. Start something new!</h4><a href='https://b24-5byepi.bitrix24.site/portal-new_project/' target='_blank'>Click Here</a>";
                                        }

                             ?>

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
//  $('footer').css('bottom', '0');
//  $('footer').css('width', '100%');
 
});
</script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush