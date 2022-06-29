@extends('layouts.app')

@section('content')
  @include('layouts.headers.cards') 
    
    <div class="container-fluid mt--7" style="background-color:#DCDCDC">
        <div class="row">
            <div class="col-xl-8 mb-5 mb-xl-0">
                <div class="card bg-gradient-default shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2>Message</h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="chart">
                        <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/theme2.jpg" style="width:100%;height:50%;" >
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card shadow" style="height:250px;">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                            
                                <h2 class="mb-0">Upcoming Event</h2><br>
                    
                            </div>
                        </div>
                    </div>
                    <div class="card-body"(>
                        <div class="chart">

                        <?php
                                for($i=0;$i<count($project_meeting->result);$i++){
                                    echo  $project_meeting->result[$i]->NAME."<br>";
                                    echo  $project_meeting->result[$i]->DATE_FROM. "<br>";
                                }
                            ?> 


                         

                        </div>
                    </div>   
                </div>            
                <div class="card shadow" style="height:250px;margin-top:5%;">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                            
                                <h2 class="mb-0">Things We Need</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <?php
                                for($i=0;$i<count($project->result);$i++){
                                    for($j=0;$j<count($task_list->result->tasks);$j++){

                                    if($task_list->result->tasks[$j]->groupId == $project->result[$i]->ID)
                                    echo "<h4>".$task_list->result->tasks[$j]->title."</h4>"; 
                                    //echo  $task_list->result->tasks[$i]->title."<br>";
                                    //echo  $task_list->result->tasks[$i]->status."<br>";
                                   
                                    }
                                }
                            ?>     
                        </div>
                    </div>
                </div>            
            </div>
            
            <div class="col-xl-8 mb-5 mb-xl-0">
                <div class="card bg-gradient-default shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class><h2>Active Project</h2>
                                
                            </div>
                        </div>
                    </div>
                  

                    <div class="card-body">
                        <div class="chart">
                       
                            <?php
                                $percent=0;
                                 $var=0;
                                 $var1=0;
                                for($i=0;$i<count($project->result);$i++){
                               echo $project->result[$i]->NAME."<br>";
                                   
                            for($j=0;$j<count($task_list->result->tasks);$j++){
                                        
                          if($task_list->result->tasks[$j]->groupId == $project->result[$i]->ID)
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
                                        $$percent = "100%";
                                    }
                                    else{
                                     $percent=$var1*100/$var;
                                    }

                        
                                    ?>
                                    <div  class="progress" style="height:20px">
                                        <div class="percent" style="width:<?php echo $percent."%"; ?>;background:green">
                                            <p style="width:max-content; margin:3px 15px;"></p>
                                        </div>
                                    </div>
                            
                            <?php
                            $b_status=$task_list->result->tasks[$i]->status;
                             $a=count($task_list->result->tasks);
                             //echo $a;
                            
                            /* for($num=0;$num=$a;)
                             {
                           if($b_status==5){  
                               $num++;
                               echo "task complete this month ( $num".")"."<br>";
                           }
                        }
                           */
                          
                             echo "task created this month: (".$var.")"."<br>";
                             echo  "task completed this month: (".$var1.")"."<br>";

                            }
                             ?>
                             
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card shadow" style="height:250px;">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                            
                                <h2 class="mb-0">Open Invoices</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                        </div>
                    </div>   
                </div>            
                <div class="card shadow" style="height:250px;margin-top:5%;">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                            
                                <h2 class="mb-0">Resource</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                        </div>
                    </div>   
                </div>     
                                </div>
                <div class="col-xl-8 mb-5 mb-xl-0">
                <div class="card bg-gradient-default shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class><h2>Support Tickets</h2>
                            </div>
                        </div>
                    </div>
                                

                    <div class="card-body">
                        <div class="chart">
                        <h4 style='display:inline' >Site Slow</h4> <h5 style='display:inline'> Submitted Oct 15, 2020 at 4:37pm</h5> <h5 style='color:green;display:inline'> Active</h5>
                        <h4>Ticket content displayed here</h4>
                        <h4 style='display:inline'>Forgot Password</h4> <h5 style='display:inline'>Submitted Oct 17, 2020 at 2:12pm Resolved</h5>
                        <h4>Ticket content displayed here</h4>
                        </div>
                        <button style="float:right" class="btn btn-danger">REQUEST TICKET</button>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card shadow" style="height:250px;">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                            
                                <h2 class="mb-0">Open Invoices</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <h4 style="margin:auto">AFUMC-SUP-2010-219</h4>
                        <p>Due: 10/31 $175.00</p>
                        <h4 style="margin:auto">AFUMC-SUP-2009-137</h4>
                        <p>Due: 9/30 $112.50</p>
                        <h4 style="margin:auto">Total: $287.50</h4>
                        </div>
                    </div>   
                </div>            
                

                
                       
            
        
    <!--    <div class="row mt-5">
            <div class="col-xl-8 mb-5 mb-xl-0">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Page visits</h3>
                            </div>
                            <div class="col text-right">
                                <a href="#!" class="btn btn-sm btn-primary">See all</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
-->    <!-- Projects table -->
<!--                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Page name</th>
                                    <th scope="col">Visitors</th>
                                    <th scope="col">Unique users</th>
                                    <th scope="col">Bounce rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        /argon/
                                    </th>
                                    <td>
                                        4,569
                                    </td>
                                    <td>
                                        340
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-up text-success mr-3"></i> 46,53%
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        /argon/index.html
                                    </th>
                                    <td>
                                        3,985
                                    </td>
                                    <td>
                                        319
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-down text-warning mr-3"></i> 46,53%
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        /argon/charts.html
                                    </th>
                                    <td>
                                        3,513
                                    </td>
                                    <td>
                                        294
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-down text-warning mr-3"></i> 36,49%
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        /argon/tables.html
                                    </th>
                                    <td>
                                        2,050
                                    </td>
                                    <td>
                                        147
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-up text-success mr-3"></i> 50,87%
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        /argon/profile.html
                                    </th>
                                    <td>
                                        1,795
                                    </td>
                                    <td>
                                        190
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-down text-danger mr-3"></i> 46,53%
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
            <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Social traffic</h3>
                            </div>
                            <div class="col text-right">
                                <a href="#!" class="btn btn-sm btn-primary">See all</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                    -->    <!-- Projects table -->
                    <!--    <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Referral</th>
                                    <th scope="col">Visitors</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        Facebook
                                    </th>
                                    <td>
                                        1,480
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">60%</span>
                                            <div>
                                                <div class="progress">
                                                <div class="progress-bar bg-gradient-danger" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        Facebook
                                    </th>
                                    <td>
                                        5,480
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">70%</span>
                                            <div>
                                                <div class="progress">
                                                <div class="progress-bar bg-gradient-success" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 70%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        Google
                                    </th>
                                    <td>
                                        4,807
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">80%</span>
                                            <div>
                                                <div class="progress">
                                                <div class="progress-bar bg-gradient-primary" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        Instagram
                                    </th>
                                    <td>
                                        3,678
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">75%</span>
                                            <div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        twitter
                                    </th>
                                    <td>
                                        2,645
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2">30%</span>
                                            <div>
                                                <div class="progress">
                                                <div class="progress-bar bg-gradient-warning" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div> -->
            </div>
        </div> 

        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush