@extends('adminlayout.final')
<!-- Page Content  -->
@section('content')
<style>
    .loginHistory {
        overflow-y: scroll;
        max-height: 200px !important;
    }
</style>
<div id="content" class="p-4 p-md-5 pt-5">
    <a href="{{url('/dashboard')}}" class="link_color"><i class="fas fa-long-arrow-alt-left"></i> <b>Back</b>
        </i></a>
    <div class="table_body_color">
        <form action="{{ route('userdetailUpdate',$userdata->id)}}" method="POST">
            @csrf
            <div class="row">
                <div class="form-group col-md-8 col-lg-8">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{$userdata->name}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="role">Role</label>
                            <select id="role" name="role" class="form-control">
                                <option value="Admin" {{$userdata->role == 'Admin' ? 'selected' : ''}}>Admin</option>
                                <option value="Strategist" {{$userdata->role == 'Strategist' ? 'selected' : ''}}>Strategist</option>
                                <option value="Operative" {{$userdata->role == 'Operative' ? 'selected' : ''}}>Operative</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email" value="{{$userdata->email}}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number" value="{{$userdata->phone}}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="project">Projects</label>
                            <input type="text" class="form-control" name="project" id="project" placeholder="Project" value="{{$userdata->project}}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-custom">UPDATE</button>
                </div>
                <div class="form-group col-md-4 col-lg-4">
                    <div class="form-group col-md-12">
                        <label for="project">Project</label>
                        <!-- <input type="text" class="form-control" name="project" id="project" placeholder="Project" value="{{$userdata->project}}"> -->
                    </div>
                    <div class="form-group col-md-12">
                        <label for="created">Created At</label>
                        <p>{{date('Y-m-d',strtotime($userdata->created_at))}}</p>
                        <!-- <input type="date" class="form-control" id="created" name="created" value="{{$userdata->created_at}}"> -->
                    </div>
                    <div class="form-group col-md-12">
                        <label for="login">Login History</label>
                        <div class="loginHistory">
                            @foreach($logins as $login)
                            <pre>{{date('Y-m-d',strtotime($login->created_at))}}</pre>
                            @endforeach
                        </div>
                        <!-- <input type="date" class="form-control" id="login" name="login"> -->
                    </div>
                </div>
            </div>

        </form>



    </div>

</div>
@endsection