@extends('adminlayout.final')
@section('title', 'Add User')

        <!-- Page Content  -->
@section('content')
    
    <div id="content" class="p-4 p-md-5 pt-5">
        <form  action="{{ route('createuser')}}" method="POST">
             <!--<div class="container">-->
                <!--<div class="row">-->
                    <div class="row">
                        
                            @csrf
                              <div class="col" style="margin-right: 21px;">
                                  <div class="row">
                                <h6><label for="foruserid">User ID</label></h6>
                                <input type="text" class="form-control" name="userID" data-userid="" id="Userhere"  placeholder="please select User">
                                <!--<small id="Userhere" class="form-text text-muted">Please go to user section and select user there</small>-->
                                </div>
                                <div class="row" id="seachbyemailiddata">
                                        
                                </div>
                             
                             </div>
                            
                             
                             
                              <div class="col">
                                    <div class="row">
                                <h6><label for="project">Project Name</label></h6>
                                <input type="text" class="form-control"  name="projectname" id="Projecthere" placeholder="Please select project">
                                  </div>
                                   <div class="row"   id="seachbyprojectnamedata">
                                        
                             </div>
                                <input type="hidden" class="form-control" name="usersearchid"  id="usersearchid"  >
                             
                             </div>
                             
                             
                             
                             
                           
                       
                     </div>
                     <br>
                     <button type="submit" class="btn btn-success1">Submit</button>
                     </form>
                     
                     
                <!--</div>-->
            <!--</div>-->
            
                        <!--<form>-->
                        <!--  <div class="row">-->
                        <!--    <div class="col">-->
                        <!--      <input type="text" class="form-control" placeholder="First name">-->
                        <!--    </div>-->
                        <!--    <div class="col">-->
                        <!--      <input type="text" class="form-control" placeholder="Last name">-->
                        <!--    </div>-->
                        <!--  </div>-->
                        <!--</form>-->
    </div>
            
@endsection

@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script type="text/javascript">
     function myFunction(key) {
         console.log(key);
            console.log(key.innerHTML);
            document.getElementById("usersearchid").value =key.innerHTML;
            document.getElementById("Userhere").value=key.nextSibling.innerHTML;
              if(1){
                     console.log("empp");
                     document.getElementById("seachbyemailiddata").style. display = "none"; 
                 }
                    else{
                     console.log("empp n");
                     document.getElementById("seachbyemailiddata").style. display = "block"; 
                }
         
           
          
            
    }
     function myFunProject(key) {
     
            console.log(key.innerHTML);
            document.getElementById("Projecthere").value=key.innerHTML;
             if(1){
                     console.log("empp");
                     document.getElementById("seachbyprojectnamedata").style. display = "none"; 
                 }
                    else{
                     console.log("empp n");
                     document.getElementById("seachbyprojectnamedata").style. display = "block"; 
                }
    }
    
         $(document).ready(function() {
        
        $("body").on("click", "td.searchbyname", function() {
            
            document.getElementById("Userhere").value=$( this ).text();
            document.getElementById("usersearchid").value=$( this ).prev().text();     
                  if(1){
                     console.log("empp");
                     document.getElementById("seachbyemailiddata").style. display = "none"; 
                 }
                    else{
                     console.log("empp n");
                     document.getElementById("seachbyemailiddata").style. display = "block"; 
                }
        

            });
            $("body").on("click", "td.searchprojectbyname", function() {
            
            document.getElementById("Projecthere").value=$( this ).next().text();
            //document.getElementById("usersearchid").value=$( this ).prev().text();     
                 if(1){
                     console.log("empp");
                     document.getElementById("seachbyprojectnamedata").style. display = "none"; 
                 }
                    else{
                     console.log("empp n");
                     document.getElementById("seachbyprojectnamedata").style. display = "block"; 
                }
        

            });
        
            $('#Userhere').keyup(function(event) {
                // event.preventDefault();
                 var j = $(this).val();
                 if(j==''){
                     console.log("empp");
                     document.getElementById("seachbyemailiddata").style. display = "none"; 
                 }
                 else{
                     console.log("empp n");
                     document.getElementById("seachbyemailiddata").style. display = "block"; 
                 }
                console.log(j);
                $.ajax({
                    type: 'GET',
                    url: "{{url('/SearchcontactByEmail')}}",
                    data: {
                        Useremail: j
                    },
                    success: function(data) {
                         $("#seachbyemailiddata").html(data);
                        
                    }
                });
            });
          
        
            $('#Projecthere').keyup(function(event) {
                event.preventDefault();
                var projectname = $(this).val();
                console.log(projectname);
                 if(projectname==''){
                     console.log("empp");
                     document.getElementById("seachbyprojectnamedata").style. display = "none"; 
                 }
                 else{
                     console.log("empp n");
                     document.getElementById("seachbyprojectnamedata").style. display = "block"; 
                 }
                $.ajax({
                    type: 'GET',
                    url: "{{url('/SearchProjectbyprojectName')}}",
                    data: {
                        Projectname: projectname
                    },
                    success: function(data) {
                        $("#seachbyprojectnamedata").html(data);
                      
                    }
                });
            });
            
        });
  </script>
@endpush