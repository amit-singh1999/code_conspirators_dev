@extends('adminlayout.final')
@section('title', 'Time Report')
<!-- Page Content  -->
@section('content')
<!-- Page Content  -->
<div id="content" class="p-4 p-md-5 pt-5">
    <div >
        <h1><label for="start">Project Status Report</label></h1>
        <form method="post" action="{{route('Gerenate-TimereportPDF')}}" id="TimereportPDfgeneretaall" style="display:none">
            
          @csrf 
          <input class="form-control form-control-lg" id="ArraytopasSincontroller" Name="arrarOFuserID"   type="text" placeholder=".form-control-lg" value="">    
        </form>
        


    <h6 style="margin-top:8px"><label for="start">Filters</label></h6>
    </div>
   
    <div style="display: flex;flex-direction: row;flex-wrap: wrap;gap: 12px;">
        <h6 style="margin-top:8px">Start Date:</h6>

    <input class="custom-select col-3" type="date" id="Timetrackingdatestart" name="trip-start" value="{{ old('trip-start') }}">
    <div>
     <h6 style="margin-top:8px">OR</h6>
    </div>
           <div>

            <select class="custom-select col-16" id="slctstrategist">
                <option>Select Strategist</option>
                <option>Evan Koteles</option>
                <option>Gianni Rand</option>
                <option>Aadil</option>
            </select>
           </div>

    </div>
<br>
    <br>

    <div>
        <table class="table" id="tableDATA">
            <thead>
            <tr>
                <th scope="col">Client</th>
                <!-- <th scope="col">User Email</th> -->
                <th scope="col">Project</th>
                <th scope="col">Strategist</th>
                <th scope="col">Budget($)</th>
                <th scope="col">Budget(Hrs)</th>
                <th scope="col">Time Logged</th>
                <th scope="col">Outsourced ($)</th>
                <th scope="col">Margin</th>
                <th scope="col">Tasks</th>
                <th scope="col">Open Tasks</th>

            </tr>
            <tr>
   <?php // echo date(DATE_ATOM,mktime(0,1,0));
   ?>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>

            </tr>
            </thead>
            <tbody class="table_body_color" id="appendingTableBody">
            </tbody>
        </table>

    </div>
    <div style="margin-top: 50px;">
        <div>
            <div style="display: flex;justify-content: center;" id="NotfoundCard"> <i class="fa fa-spinner fa-spin" id="spinnderLoading" style="font-size:48px;color:red;display:none"></i></div>
            <div class="card" Id="Nodataavailable" style="display:none">
                <div class="card-body">
                    No data Available
                </div>
            </div>
            <table class="table" style="display:none" id="tableDATA">
                <thead>
                    <tr>
                        <th scope="col">User</th>
                        <!-- <th scope="col">User Email</th> -->
                        <th scope="col">Hours</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody class="table_body_color" id="appendingTableBody">
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script>
window.onload = function() {
   if(document.getElementById("Timetrackingdatestart").value)
    getdataTimeTracking();
}
 //   console.log("hey");
</script>
@include('home.Timetracking')
@include('home.TImeTrackingDownloadAsPDf_Ajax_Call')
<script>
//  setTimeout(function(){ alert("please select  date"); }, 3000);
</script>


@endpush