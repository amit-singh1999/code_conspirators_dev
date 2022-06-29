@extends('adminlayout.final')
@section('title', 'Edit Case Study')
<!-- Page Content  -->
@section('content')
<!-- Page Content  -->
<div id="content" class="p-4 p-md-5 pt-5">
            <a  href="{{url('/dashboard/CaseStudy')}}" class="link_color"><i class="fas fa-long-arrow-alt-left"></i> <b>Back</b>
            </i></a>

       @if (Session::has('message'))
       <div class="alert alert-info">{{ Session::get('message') }}</div>
       @endif
        <form action="{{ route('casestudy.update',$editdata->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
      
            
            <div class="row">
            <div class="col">
              <input type="text" name="ClientName" class="form-control border_color_ofinputBox" placeholder="Enter Client Name"  value="{{$editdata->Clientname}}">
            </div>
            
            <div class="col">
              <input type="text" name="Casestudyname" class="form-control border_color_ofinputBox" placeholder="Enter Case StudyName" value="{{$editdata->caseStudyName}}">
            </div>
            </div>
            <div class="row">
              <div style="margin-top:75px; flex: 0 0 40%;" class="col col-xs-12 col-sm-12 col-md-12 mt-12">
                     <div class="form-group border_color_ofinputBox">
                        <input type="file"  class="custom-file-input" id="customFile1" name="Client_logo">
                        <label style="margin-left:16px;margin-right:16px;" class="custom-file-label" id="image" name="Client_logo" for="customFile">Choose File</label>
                      </div>
                      </div>
                        <div class="col" style="margin-top:39px; margin-left:105px;" > <p>Current Image</p>  @if($editdata->Client_company_logo)
                                     <img  height="200" src="{{ asset('ClientLogo/'. $editdata->Client_company_logo) }}" alt="" border="0"/> 
                                     @endif
                      </div>
                      </div>
                       
                       <div class="row" id="preview"></div>

            
             
             <div class="row">
             <div class="col-xs-12 col-sm-12 col-md-12 mt-12" style="margin-top:39px">
                <div class="form-group">
                    <textarea id="summernote" name="description" >{{$editdata->description}}</textarea>
                </div>
                 <div style="width: 101%;
                 margin-left: -2px;">
            
               
              <input type="text" name="moreinfo" class="form-control border_color_ofinputBox" value="{{$editdata->moreinfo}}">
             </div>
             <button type="submit" class="btn btn-success1 rounded case_study_button_position">Update Case Study</button>
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



<script>
function imagePreview(fileInput) {
    if (fileInput.files && fileInput.files[0]) {
        var fileReader = new FileReader();
        fileReader.onload = function(event) {
            console.log("changed");
            $("#preview").css('margin-top', '-140px');
            $("#preview").css('margin-left', '2px')
            $('#preview').html('<p class="spacer">Preview </p> <img src="' + event.target.result + '"  height="200"/>');
             $(".spacer").css('margin-right', '20px')
        };
        fileReader.readAsDataURL(fileInput.files[0]);
    }
}
$("#customFile1").change(function() {
    console.log("changed");
    imagePreview(this);
});
</script>

@endpush