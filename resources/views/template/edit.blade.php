@extends('adminlayout.final')
@section('title', 'Edit Proposal Template')
<!-- Page Content  -->
@section('content')
<!-- Page Content  -->
<div id="content" class="p-4 p-md-5 pt-5">

    <a  href="{{url('/admin')}}" class="link_color"><i style="margin-left:17px" class="fas fa-long-arrow-alt-left"></i> <b>Back</b>
    </i></a>

    <div class="container-fluid mt--7" >
        <div class="row">
            <div class="col-xl-12 mb-5 mb-xl-0">
                <div class="card bg-gradient-default shadow" style="margin-bottom:6%">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2><b>Edit Here</b></h2>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="chart">
                            <form action="{{route('admin.update',$edit->id)}}" method="POST" enctype="multipart/form-data">
                                @method('PATCH')
                                @csrf
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <input type="file" class="custom-file-input" id="customFile" name="name" value={{$edit->name}}>
                                            <label style="margin-left:16px;margin-right:16px;" class="custom-file-label" name="name" for="customFile">{{$edit->name}}</label>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <strong>Detail:</strong>
                                            <textarea class="form-control" style="height:150px" name="detail" value={{$edit->detail}}>{{$edit->detail}}</textarea>
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