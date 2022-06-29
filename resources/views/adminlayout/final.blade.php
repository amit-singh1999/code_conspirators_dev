<!DOCTYPE html>
<html lang="en">

<head>
    <title>CC - @yield('title') </title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
   
    <link rel="stylesheet" href="{{ asset('adminstyle') }}/css/style.css" />
     <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.css"
    />
    
</head>

<body>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <div>
        @include('adminlayout.topnavbar')
    </div>
    <div class=" d-flex align-items-stretch" style="margin-top:-41px">
        <nav id="sidebar">
            <div class="custom-menu">
                <button type="button" id="sidebarCollapse" class="btn btn-custom">
                    <i class="fa fa-bars"></i>
                    <span class="sr-only">Toggle Menu</span>
                </button>
            </div>
            <div class="p-4 pt-5">
                <!-- <h1><a href="index.html" class="logo">ccportal</a></h1> -->
                <ul class="list-unstyled components mb-5">
                    <li>
                        <a href="{{url('/home')}}">Client Portal</a>
                    </li>
                    <li>
                        <a href="{{url('/dashboard')}}">Dashboard</a>
                    </li>
                    <li>
                        <a href="https://estimator.codeconspirators.com/" target="_blank" >Estimator</a>


                        
                    </li>
                    
                    <li>
                        <a href="https://lnkshrnk.com/admin/login.php" rel="noopener noreferrer" target="_blank">LnkShrnk</a>
                    </li>
                    <li>
                        <a href="#AdminSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Admin</a>

                        <ul class="collapse list-unstyled" id="AdminSubmenu">
                            <li>
                                <a href="{{url('/dashboard')}}">Users</a>
                            </li>
                             
                            <ul class="collapse list-unstyled" id="AdminSubmenu">
                             <li>
                              <a href="{{url('/dashboard/timeTracking')}}">Reporting</a>
                            </li>
                             <!--<li>
                              <a href="{{url('/dashboard/salesCommisionReport')}}">Report: Sales Commission</a>
                            </li>
                             <li>
                                    <a href="{{url('/dashboard/performanceBonusReport')}}">Report: Performance Bonus</a>
                            </li>
         -->
                             <li>
                              <a href="{{url('/dashboard/CaseStudy')}}">Case Studies</a>
                            </li>
                            <li>
                              <a href="{{url('/admin')}}">Proposals</a>
                            </li>
                            <li>
                              <a href="{{url('/dashboard/settings')}}">Settings</a>
                            </li>
                            <li>
                              <a href="{{url('/dashboard/payments')}}">Payments</a>
                            </li>
                                <li>
                                    <a href="{{url('/dashboard/projectStatus')}}">Project Status</a>
                                </li>
                            </ul>
                            
                        </ul>
                    </li>
                </ul>

            </div>
        </nav>

        @yield('content')

    </div>
    <!-- @include('layouts.footers.auth') -->
    <div>
        @include('adminlayout.adminfooter')
    </div>
     <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    <script src="{{ asset('adminstyle') }}/js/jquery.min.js"></script>
    <script src="{{ asset('adminstyle') }}/js/popper.js"></script>
    <script src="{{ asset('adminstyle') }}/js/bootstrap.min.js"></script>
    <script src="{{ asset('adminstyle') }}/js/main.js"></script>
    
    <script>
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
    </script>
    
     @stack('scripts')
    


</body>

</html>