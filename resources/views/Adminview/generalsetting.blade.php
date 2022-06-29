@extends('adminlayout.final')
@section('title', 'Settings')
<!-- Page Content  -->
@section('content')



<!-- Page Content  -->
<div id="content" class="p-4 p-md-5 pt-5">

    <div>
        <h1><label for="start">General Settings</label></h1>

        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
       @endif
         <form action="{{ route('savesettings') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="padding-left:21px">
                 <div class="row">
                      <div class="col-xs-12">
                        <h4>Message On Invoice</h4><br>
                             <div class="form-group">
                                                <textarea rows="4" cols="80" name="msgoninv">{{$msgoninv ?? '' }}</textarea>
                            </div>
                        </div>
                 </div>
              
                <button type="submit" class="btn btn-success1 rounded case_study_button_position">Save Settings</button>
                </div>
                
            </form>     

    
</div>
</div>

@endsection


@push('scripts')
 <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script>

    $(document).ready(function(){
          $(".alert").delay(2000).slideUp(300);
    });
</script>

@endpush