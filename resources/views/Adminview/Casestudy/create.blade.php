@extends('adminlayout.final')
@section('title', 'Create Case Study')
<!-- Page Content  -->
@section('content')
<!-- Page Content  -->
<div id="content" class="p-4 p-md-5 pt-5">
         <a  href="{{url('/dashboard/CaseStudy')}}" class="link_color"><i class="fas fa-long-arrow-alt-left"></i> <b>Back</b>
            </i></a>

         <!--@if($errors->any())-->
         <!--    @foreach ($errors->all() as $error)-->
         <!--        <div>{{$error}}</div>-->
         <!--    @endforeach-->
         <!--@endif-->
 
       @if (Session::has('message'))
       <div class="alert alert-info">{{ Session::get('message') }}</div>
       @endif
        <form action="{{ route('savecasestudy') }}" method="POST" enctype="multipart/form-data">
            @csrf
          <div class="row">
             <div class="col">
              @if($errors->has('ClientName'))
                <span class="text-danger">{{ $errors->first('ClientName') }}</span>
              @endif
              
              <input type="text" name="ClientName" class="form-control border_color_ofinputBox" placeholder="Enter Client Name">
            </div>
            
            <div class="col">
              @if($errors->has('Casestudyname'))
                <span class="text-danger">{{ $errors->first('Casestudyname') }}</span>
              @endif
              <input type="text" name="Casestudyname" class="form-control border_color_ofinputBox" placeholder="Enter Case StudyName">
             </div>
        
              <div class="col-xs-12 col-sm-12 col-md-12 mt-12" style="margin-top:39px">
                   
                        @if($errors->has('Client_logo'))
                        <div style="margin-top:-26px">
                        <span class="text-danger">{{ $errors->first('Client_logo') }}</span>
                         </div> 
                        @endif
                      
                       
                     <div class="form-group border_color_ofinputBox">
                      
                        <input type="file" class="custom-file-input" id="customFile" name="Client_logo">
                        <label style="margin-left:16px;margin-right:16px;" class="custom-file-label" name="Client_logo" for="customFile">Choose file</label>
                   </div>
             </div>
             <div class="col-xs-12 col-sm-12 col-md-12 mt-12" style="margin-top:39px">
                <div class="form-group">
                    @if($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                   @endif
                    <textarea id="summernote" name="description"></textarea>
                </div>
                <div style="width: 101%;
                margin-left: -2px;">
            
               
              <input type="text" name="moreinfo" class="form-control border_color_ofinputBox" placeholder="Enter more information link">
             </div>
                
                
             <button type="submit" class="btn btn-success1 rounded case_study_button_position">Add case Study</button>
             </div>
          </div>
        </form>

</div>
@endsection


@push('scripts')
 <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.js"></script>
    <script>
      $("#summernote").summernote({
        placeholder: "Enter Description here",
        tabsize: 2,
        height: 100,
        toolbar: [
          ["style", ["style"]],
          ["font", ["bold", "underline", "clear"]],
          ["color", ["color"]],
          ["para", ["ul", "ol", "paragraph"]],
          ["table", ["table"]],
          ["insert", ["link", "picture", "video"]],
          ["view", ["fullscreen", "codeview"]],
        ],
      });
    </script>


@endpush