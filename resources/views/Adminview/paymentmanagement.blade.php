@extends('adminlayout.final')
@section('title', 'Users')
        <!-- Page Content  -->
        @section('content')
        <style>
            .switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: default;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: green;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

#searchbytype{
        border: 1px solid #aaa;
     border-radius: 3px; 
    /* padding: 5px; */
    background-color: transparent;
    padding: 4px;
}

        </style>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>
        <?php  //dd($newarray);  die() ?>
       
        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5 pt-5">
            <div>
                <h1>Payments</h1>
        <!-- Custom Filter -->
               <table>
                 <tr>
                  <!-- <td>
                     <input type='date' id='searchBydate' placeholder='Search by Date'>
                   </td>
                  --> <td>
                     <select id='searchbytype'>
                       <option value=''>-- Select type--</option>
                       <option value='unarchive'>Unarchive</option>
                       <option value='archive'>Archive</option>
                       <option value='credit'>Credit</option>
                       <option value='notcredit'>Not Credit</option>
                       <option value='active'>Active</option>
                       <option value='archive'>Not Active</option>
                     
                     </select>
                   </td>
                 </tr>
               </table>
                <div class="row" style="float:right;margin-top: -27px">
                                  <div style="float:right;margin: 15px">  <a href="{{url('/dashboard/payments/addretainer')}}">
                                     <button class="btn btn-success1" style="margin-right:60px;">Add Line Item</button>
                            
                                    </a></div>
                        </div>
            
            
                <table class="table" id="datatable" >
                  <!--    <thead><h1 style="margin:8px">Payments</h1></tr></thead>
                  -->  
            <!--            <div class="row" style="float:right;margin-top: -27px">
                                  <div style="float:right;margin: 15px">  <a href="{{url('/dashboard/payments/addretainer')}}">
                                     <button class="btn btn-success1" style="margin-right:60px;">Add Line Item</button>
                                    </a></div>
                                </div>
            -->           <thead>
                        <tr>
                            <!--<th scope="col">User Id</th>-->
                            <th scope="col">Company Name</th>
                            
                            <th scope="col">Product Name</th>
                            
                            <th scope="col">Credit</th>
                            <th scope="col">Active</th>
                            <th scope="col">Rate</th>
                            <th scope="col">Last Billed</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">type</th>
                            
                            <th scope="col">Action</th>
                           
                        </tr>
                    </thead>
                    <tbody class="table_body_color">           
                           <?php 
                           if(count($newarray)>0)
                           {
                           foreach($newarray as $key => $newdata){ 
                             //  dd($newdata["prdct"]);
                           if(count($newdata["prdct"])==1)
                           {
                           ?>
                                    
                          
                            <tr>
                            <td> {{ $newarray[$key]["company_title"] }}  </td>
                            
                            <td> {{ $newarray[$key]["prdct"][0]["PRODUCT_NAME"] }} </td>
                            <td><label class="switch">
                                <input type="checkbox" name="Active" <?php echo ($newarray[$key]["credit"]=="active") ? 'checked' : ''; ?> disabled>
                                <span class="slider round"></span>
                                </label>
                            </td>
                            
                            <td><label class="switch">
                                        <input type="checkbox" name="Active" <?php echo ($newarray[$key]["active"]=="active") ? 'checked' : ''; ?> disabled>
                                        <span class="slider round"></span>
                                </label>
                            </td>
                            <td>${{ $newarray[$key]["prdct"][0]["PRODUCT_PRICE"] }}  </td>
                            <td>{{ $newarray[$key]["lastbilled"]  }} </td>
                            <td>1    </td>
                           <td>{{ $newarray[$key]["type"] }}    </td>
                            <td style="width:78px;" ><a href="{{url('/dashboard/payments/edit/'.$newarray[$key]["id"].'/')}}" class="link_color" ><i title="edit" class="fas fa-edit"></i></a>
            <a href="{{url('/dashboard/payments/sendnow/'.$newarray[$key]["id"].'/')}}" class="link_color" > <i onclick="return confirm('Are you sure?')"  title="send invoice now" class="fas fa-send" aria-hidden="true"></i> </a>
                            </td>
                            
                            </tr>
                          <?php }
                          else
                          { 
                          //dd($prdct);
                          ?>
                              
                              <tr>
                            <td> {{ $newarray[$key]["company_title"] }}  </td>
                           
                            <td>{{ $newarray[$key]["prdct"]["PRODUCT_NAME"]   }} </td>
                                                        <td><label class="switch">
                                <input type="checkbox" name="Active" <?php echo ($newarray[$key]["credit"]=="active") ? 'checked' : ''; ?> disabled>
                                <span class="slider round"></span>
                                </label>
                            </td>
                            
                            <td><label class="switch">
                                        <input type="checkbox" name="Active" <?php echo ($newarray[$key]["active"]=="active") ? 'checked' : ''; ?> disabled>
                                        <span class="slider round"></span>
                                </label>
                            </td>
                             <td>${{ $newarray[$key]["prdct"]["PRODUCT_PRICE"] }} </td>
                            <td>{{ $newarray[$key]["lastbilled"]  }} </td>
                            <td>1    </td>
                           <td>{{ $newarray[$key]["type"] }}     </td>
                            <td  style="width:78px;"><a href="{{ url('/dashboard/payments/edit/'.$newarray[$key]["id"].'/')}}" class="link_color" ><i title="edit" class="fas fa-edit"></i></a> <!--<a href="{{url('/dashboard/user/edit/')}}" class="link_color" ><i title="reset" class="fas fa-refresh" aria-hidden="true"></i></a>-->  <a href="{{url('/dashboard/payments/sendnow/'.$newarray[$key]["id"].'/')}}" class="link_color" > <i onclick="return confirm('Are you sure?')"  title="send invoice now" class="fas fa-send" aria-hidden="true"></i> </a>
                            </td>                            </tr>
                         <?php
                         
                                
                            }
                          }
                          
                          } ?>
                             

                    </tbody>
                </table>
                
            </div>

        </div>
@endsection
@push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.js"></script>
    <script>
        $(function() {
           $('#datatable').DataTable({
                
                
                   order: [[0, 'desc']],
           })
        });
    </script>
    
@endpush
    