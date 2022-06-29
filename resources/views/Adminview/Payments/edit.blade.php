@extends('adminlayout.final')
@section('title', 'Edit Case Study')
<!-- Page Content  -->
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
  cursor: pointer;
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
  background-color: #2196F3;
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

.spacer{
    margin-left:32px;
}


</style>

@section('content')

<!-- Page Content  -->
<div id="content" class="p-4 p-md-5 pt-5">
    <a href="{{url('/dashboard/payments')}}" class="link_color"><i class="fas fa-long-arrow-alt-left"></i> <b>Back</b>
        </i></a>
    <br><br>

    @if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
    <form action="{{route('payments.update',$editdata["id"]) }}" method="POST" enctype="multipart/form-data">
        @csrf


        <div class="row">
            <label>Company Name:</label>

            <div class="col spacer">
                <input type="text" name="Company_title" value="{{ $editdata["company_title"] }}" >
            </div>
        </div>
        <br>

        <div class="row">
            <label>Product Name:</label>
            <?php 
            if(!array_key_exists("0",$editdata["prdct"]))
            {
            $editdata["prdct"] = array_fill(0, 1, $editdata["prdct"]);
                
            }
                ?>

            <div class="col spacer" style="margin-left:48px">
                <textarea name="Product_name" row="12" col="262">{{$editdata["prdct"][0]["PRODUCT_NAME"]}}</textarea>
            </div>
        </div>
        <br>

        <div class="row">
            <label>Credit:</label>

            <div class="col spacer" style="margin-left:100px">
                <label class="switch">
                    <input type="checkbox" name="Credit" <?php echo ($editdata["credit"]=="active") ? 'checked' : ''; ?>>
                    <span class="slider round"></span>
                </label>

            </div>
            <label>Active:</label>

            <div class="col spacer" style="margin-left:100px">
                <label class="switch">
                    <input type="checkbox" name="Active" <?php echo ($editdata["active"]=="active") ? 'checked' : ''; ?>>
                    <span class="slider round"></span>
                </label>

            </div>
        </div>
        <br>

        <div class="row">
            <label>Rate:</label>

            <div class="col spacer" style="margin-left:111px">
              <input type="text" name="Product_price" value="$ <?php echo (isset($editdata["prdct"][0]["PRODUCT_PRICE"])) ?$editdata["prdct"][0]["PRODUCT_PRICE"] : $editdata["prdct"]["PRODUCT_PRICE"];  ?>" >
            </div>
            <label style="margin-right:57px">Quantity:</label>

            <div class="col spacer" >
                <input type="text" name="Product_qty" value="1" >
            </div> 
            
        </div>
        <br>
        
        <div class="row">
            <label>Last Billed:</label>

            <div class="col spacer" style="margin-left:72px">
                
            {{ $editdata["lastbilled"] }} 
            </div>
            
        </div>
        
        <br>
        <div class="row">
            <input type = "hidden" value="{{$editdata["id"]}}" name="itemid">    
            <button type="submit" class="btn btn-success1 rounded case_study_button_position" name="Update" value="update">Update Record</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <button type="submit" class="btn btn-success1 rounded case_study_button_position" name="Archive" value="archive">Archive Record</button>
</div>
</div>
</form>

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
