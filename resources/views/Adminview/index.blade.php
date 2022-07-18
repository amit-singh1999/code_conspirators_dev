@extends('adminlayout.final')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">

@section('title', 'Users')
<!-- Page Content  -->
@section('content')
<!-- Page Content  -->
<div id="content" class="p-4 p-md-5 pt-5">
    <div>
        <table class="table " id="datatable">
            <thead>
                <tr>
                    <h1 style="margin:8px">Users</h1>
                </tr>
                <div class="row" style="float:right;margin-top: -27px">
                    <div style="float:right;margin: 15px"> <a href="{{url('/addUser')}}">
                            <button class="btn btn-success1" style="float:right; top:4px;">Add User</button>

                        </a></div>
                </div>
                <tr>
                    <!--<th scope="col">User Id</th>-->
                    <th scope="col">Name</th>
                    <th scope="col">Role</th>
                    <th scope="col">Login</th>
                    <th scope="col">Action</th>

                </tr>
            </thead>
            <tbody class="table_body_color">
                @foreach ($data as $userdata_info)
                
                <tr>
                    <td>{{$userdata_info['name']}} </th>
                    <td>{{$userdata_info['role']}} </th>
                    <td>

                        @if($userdata_info['login_details'][0] != 0)
                        @php
                        $datenew = date_create($userdata_info['login_details'][1]);


                        echo date_format($datenew,"Y/m/d H:i:s");
                        @endphp
                        {{"[".$userdata_info['login_details'][0]."]"}}

                        @else

                        {{"Not logged in yet"}}
                        @endif




                        </th>
                    <td><a href="{{url('/dashboard/user/edit/'.$userdata_info['id'])}}" class="link_color"><i class="fas fa-edit"></i></a> <a onclick="return confirm('Are you sure?')" href="{{url('/dashboard/user/delete/'.$userdata_info['id'])}}" class="link_color"><i class="far fa-trash-alt"></i></a> </th>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
@push('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>


<script>
    $(document).ready(function() {
        $('#datatable').DataTable();
    });
</script>

@endpush