@extends('adminlayout.final')
@section('title', 'Create Proposal Template')
<!-- Page Content  -->
@section('content')
<!-- Page Content  -->
<div id="content" class="p-4 p-md-5 pt-5">
    <div class="container-fluid mt--7" >
        <div class="row">
            <div class="col-xl-12 mb-5 mb-xl-0">
                <div class="" style="margin-bottom:6%">
                    <div class="">
                        <div class="row align-items-center">
                            <div class="col">
                                <h1>Create Proposal</h1>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="chart">

                            <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">

                                @csrf
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <input type="file" class="custom-file-input" id="customFile" name="name">
                                            <label style="margin-left:16px;margin-right:16px;" class="custom-file-label" name="name" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <strong>Detail:</strong>

                                            <textarea class="form-control" style="height:150px" name="detail" placeholder="Detail"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                        <button type="submit" class="btn btn-success1">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection