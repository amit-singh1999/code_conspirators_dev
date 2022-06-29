@extends('adminlayout.final')
<!-- Page Content  -->
@section('content')

<?php
function convertMinutesToDecimal($minutes)
{
    if($minutes==0) return 0;
   $hours =  $minutes / 60;
   return round($hours,2);
}
foreach ($newResponse as $value) { 
    $name=  $value['name'];
    break;
}

?>
 @section('title', $name)
<!-- Page Content  -->
<div id="content" class="p-4 p-md-5 pt-5">
        <div>
            <!--<a href="{{url('/dashboard/timeTracking')}}">  -->
            <!--<i class="fa fa-long-arrow-left" style="font-size:48px;color:red"></i> -->
            <!--</a>-->
            
            <a onclick="history.go(-1);"  href="#" class="link_color" ><i class="fas fa-long-arrow-alt-left"></i> <b>Back</b>  </i></a>
            
        </div>
        <div style="margin-top: 50px;">
       
        <div>
            <table class="table">
                  <thead><tr><h2>{{  $name }}</h2></tr></thead>
                <thead>
                    <tr>
                        <th scope="col">Project Name</th>
                        <th scope="col">Task Name</th>
                        <th scope="col">Hours</th>
                        <th scope="col">Date Start</th>
                    </tr>
                </thead>
                <tbody class="table_body_color">
                    <?php
                    foreach ($newResponse as $value) { ?>
                        <?php echo "<tr>"; ?>
                        
                        <td> <?php echo $value['projectname']; ?> </td>
                        <td> <?php echo $value['TaskName']; ?> </td>
                        <td> <?php echo convertMinutesToDecimal($value['MINUTES']);?></td>
                        <td> <?php echo  date("Y-m-d H:i", strtotime($value['DATE_START']));  ?></td>
                        <td> <?php echo "</tr>"; ?>
                        <?php  } ?>
                        
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection