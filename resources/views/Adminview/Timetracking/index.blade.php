@extends('adminlayout.final')
@section('title', 'Time Report')
<!-- Page Content  -->
@section('content')
<!-- Page Content  -->
@include('adminlayout.reportmenu')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<div id="content" class="p-4 p-md-5 pt-5">
    <div>
        <h1><label for="start">Time Report</label></h1>
        <form method="post" action="{{route('Gerenate-TimereportPDF')}}" id="TimereportPDfgeneretaall" style="display:none">

            @csrf
            <input class="form-control form-control-lg" id="ArraytopasSincontroller" Name="arrarOFuserID" type="text" placeholder=".form-control-lg" value="">
        </form>



        <h6><label for="start">Date Range</label></h6>
    </div>

    <div style="display: flex;flex-direction: row;flex-wrap: wrap;gap: 10px;">
        <input class="custom-select col-3" type="date" id="Timetrackingdatestart" name="trip-start" value="{{ old('trip-start') }}">
        <div>
        </div>
        <input class="custom-select col-3" type="date" id="Timetrackingdatend" name="trip-end" value="{{ old('trip-end') }}">
        <div>
            <button type="submit" class="btn btn-success1" onclick="getdataTimeTracking()">Generate Report </button>
        </div>
        <div id="DownloadTimeTrackingresultasPDfButtOn">

        </div>

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
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
@endsection
@push('scripts')
<script>
    window.onload = function() {
        if (document.getElementById("Timetrackingdatestart").value)
            getdataTimeTracking();
    }
    //   console.log("hey");
</script>

<script>
    //     $(document).ready( function () {

    // } );
</script>
@include('home.Timetracking')
@include('home.TImeTrackingDownloadAsPDf_Ajax_Call')

// setTimeout(function(){ alert("please select date"); }, 3000);



@endpush