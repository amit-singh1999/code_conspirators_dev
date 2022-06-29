@extends('adminlayout.final')
@section('title', 'Sales Commission Report')
<!-- Page Content  -->
@section('content')
@include('adminlayout.reportmenu')
<!-- Page Content  -->
<?php if(isset($mainarr)) 
$newarr = (array)$mainarr//dd($newarr)?>
<div id="content" class="p-4 p-md-5 pt-5">
    <div>
        <h1>Sales Commission Report</h1>
    </div>
    <div>
         
        <h6><label for="start">Date Range</label></h6>
    
        <input class="custom-select col-3" type="date" id="FilterDate" name="FilterDate">
        <input class="custom-select col-3" type="date" id="FilterendDate" name="FilterendDate">

        <button type="submit" class="btn btn-success1" style="margin-left: 28px;" onclick="SalesTracking()">Generate
            Report
        </button>
        <button type="submit" class="btn btn-success1" style="margin-left: 28px;"
            onclick="DownloadCommisionReport()">Download Report
        </button>
    </div>


    <div style="margin-top: 50px;">
        <div>
            <div style="display: flex;justify-content: center;" id="NotfoundCard"> <i class="fa fa-spinner fa-spin"
                    id="spinnderLoading" style="font-size:48px;color:red;display:none"></i></div>

            <div class="card" Id="Nodataavailable" style="display:none">
                <div class="card-body">
                    No data Available
                </div>
            </div>

            <table class="table" id="tableDATA" style="display:none">
                <thead>
                    <tr>
                        <th scope="col" style='width:18%'>Name</th>
                        
                        <th scope="col" style='text-align:right; width:20%'>Invoice Amount</th>
                        <th scope="col" style='text-align:right; width:20%'>Commission</th>
                        <th scope="col" style='text-align:right; width:20%'>Action</th>
                    </tr>
                </thead>
                <tbody class="table_body_color" id="appendingTableBody">

                    <?php
                  //  foreach ($newarr as $value) { ?>

                    <tr>
                        <td scope="col"><?php //echo $value->RESPONSIBLE_NAME." ".$value->RESPONSIBLE_LAST_NAME;  ?>
                        </td>
                        <td scope="col">$ <?php //echo $value->Commission?>
                        </td>
                        <td scope="col"><?php // echo $value->ID ?>
                        </td>
                        <td scope="col">$<?php //  echo $value->PRICE ?>
                        </td>
                    </tr>

                    <?php  // }
                    ?>

                </tbody>
            </table>
            <!-- <div id="showbuttons" style="display:none">
                <button id="prevbutton" onclick="NextData('P')" class="link_color btn btn-success1" data-id="0">Previous
                </button>
                <button id="nextbutton" onclick="NextData('N')" data-id="50" class="link_color btn btn-success1">
                    Next</button>
            </div> -->



        </div>
    </div>
</div>
</div>
@endsection
@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
@include('home.Salestracking');

<script>

window.onload = function() {
   if(document.getElementById("FilterDate").value)
    SalesTracking()
}
 //   console.log("hey");

</script>
<script>
//setTimeout(function(){ alert("please select  date"); }, 3000);
</script>
@endpush