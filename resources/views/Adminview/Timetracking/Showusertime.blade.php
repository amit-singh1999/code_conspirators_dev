@extends('adminlayout.final')
<!-- Page Content  -->
@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">

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
            <table class="table" id="table_id">
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
@push('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>


<script>
    $(document).ready(function() {
        $('#table_id').DataTable();
    });
</script>

@endpush