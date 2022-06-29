@extends('adminlayout.final')
        <!-- Page Content  -->
        @section('content')

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5 pt-5">
            <div>

                <table class="table ">
                    <thead>
                        <tr>
                            <th scope="col"> Id</th>
                            <th scope="col">User Id</th>
                            <th scope="col">Ip Address</th>
                            <th scope="col">Device</th>
                        </tr>
                    </thead>
                    <tbody>           
                            @foreach ($user_login_data as $userdata_login_info)
                            <tr>
                            <th scope="row">{{$userdata_login_info->id}}  </th>
                            <td>{{$userdata_login_info->user_id}}  </th>
                            <td>{{$userdata_login_info->ip_address}}  </th>
                            <td>{{$userdata_login_info->device}}  </th>
        
                            </tr>
                         @endforeach 
                    </tbody>
             
                </table>
            </div>

        </div>

        @endsection
        
    
 