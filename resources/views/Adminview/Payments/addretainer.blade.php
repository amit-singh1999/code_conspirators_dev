@extends('adminlayout.final')
@section('title', 'Add Line Item')
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

input:checked+.slider {
    background-color: #2196F3;
}

input:focus+.slider {
    box-shadow: 0 0 1px #2196F3;
}

input:checked+.slider:before {
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

.spacer {
    margin-left: 32px;
}
.lblitem{
    width:13%;
}

.select-checkbox option::before {
  content: "\2610";
  width: 1.3em;
  text-align: center;
  display: inline-block;
}
.select-checkbox option:checked::before {
  content: "\2611";
}

</style>
@section('content')
<?php
//$pr = json_encode($productarr); 
//dd($pr);
?>
<!-- Page Content  -->
<div id="content" class="p-4 p-md-5 pt-5">
    <a href="{{url('/dashboard/payments')}}" class="link_color"><i class="fas fa-long-arrow-alt-left"></i> <b>Back</b>
        </i></a>
    <br><br>

    @if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
    <form action="{{route('lineitem.save') }}" method="POST" enctype="multipart/form-data">
        @csrf


        <div class="row">
            <label for="company" class="lblitem">Company Name:</label>

            <div class="col spacer">
                <input type="text" class="form-control" name="companyname" data-userid="" id="Companyhere" style="width:40%" placeholder="Please select Company">
            </div>
        </div>
          <div class="row" id="seachbycompanyname">

            </div><br>
        

        <div class="row">
            <label for="project" class="lblitem">Project Name</label>

            <div class="col spacer">

            <input type="text" class="form-control" name="projectname" id="Projecthere"
                placeholder="Please select Project" style="width:40%">
                </div>
                </div>
            <div class="row" id="seachbyprojectnamedata">

            </div><br>
            
       <?php 
       $html="";
       foreach($productarr as $key => $product)
                       {
                          // dd($product);
                          $html.= '<optgroup label="'.$key.'">';   
                          for($i=0; $i<count($product); $i++)
                          $html.=  '<option value="'.$product[$i]["ID"].'">'.$product[$i]["NAME"]." &nbsp;&nbsp;&nbsp;$".$product[$i]["PRICE"].'</option>' ;
                           $html.='</optgroup>';
                       }
                       ?>
            <div class="row">
                <label for="product" class="lblitem">Product Name</label>
                 <div class="col spacer" >
                    <select name="product_id" class="form-control select-checkbox"  id ="product_id" style="width:40%"  data-mdb-filter="true">
                        <option>Please Select</option>     
                            <?php echo $html; ?>
                      <optgroup label="Custom Item">
                          <option value="000" id="otheritem">Other</option>
                          
                      </optgroup>
                       
                              
                                                          
                    </select>
                </div>
                
                </div>
                <br>
                    <div class="row" id="customproduct" style="display:none">
                <label for="product" class="lblitem">Custom Product </label>
                <div class="col spacer">

                <textarea name="customproduct" rows="5" cols="45" style="margin-left:135px" ></textarea>
                    </div>
                      <br>
                    </div>
             
          

            <div class="row">
                <label  class="lblitem">Rate: $</label>

                <div class="col spacer">
                    <input type="number" class="form-control" name="product_price"  id="rate"  style="width:40%">
                </div>
            </div>    <br>
              <div class="row">
                <label class="lblitem">Type:</label>

                <div class="col spacer" >
                    <select name="item_type" class="form-control"  style="width:40%">
                        <option>Please Select</option>     
                        <option name="" value="monthly">Monthly</option>
                        <option name="" value="onetime">One time</option>
                       
                              
                                                          
                    </select>
                </div>
            </div>  <br>
            <div class="row">
                <label class="lblitem">Quantity:</label>

                <div class="col spacer">
                    <input type="number" class="form-control" name="quantity" id= "justAnInputBox1" value="1" style="width:40%"  >
                </div>

            </div>
            <br>

            <br>
            <div class="row">
                <div class="col spacer" style="margin-left:165px">
                <input type="hidden" value="" name="company_id" id="company_id">
                <input type="hidden" value="" name="productname" id="product_name">                
                <input type="hidden"  name="project_id" id="project_id">
                
                <button type="submit" class="btn btn-success1 rounded case_study_button_position" name="Update"
                    value="update">Add Item</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="reset" class="btn btn-success1 rounded case_study_button_position" name="Reset"
                    value="archive">Reset Item</button>
            </div>
        </div>
    </form>

</div>
@endsection
@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
    crossorigin="anonymous"></script>

<script type="text/javascript">


function myFunction(key) {
    document.getElementById("companysearchid").value = key.innerHTML;
    document.getElementById("Companyhere").value = key.nextSibling.innerHTML;
    if (1) {
       
        document.getElementById("seachbycompanyname").style.display = "none";
    } else {
      
        document.getElementById("seachbycompanyname").style.display = "block";
    }




}

function myFunProject(key) {

    console.log(key.innerHTML);
    //alert(key.previousSibling.innerHTML);
    document.getElementById("Projecthere").value = key.innerHTML;
     document.getElementById("project_id").value = key.previousSibling.innerHTML;
    if (1) {
          document.getElementById("seachbyprojectnamedata").style.display = "none";
    } else {
        document.getElementById("seachbyprojectnamedata").style.display = "block";
    }
}


$(document).ready(function() {
    $(".alert").delay(2000).slideUp(300);
        $('#product_id').change(function(){
            
           var id = $(this).find(':selected').val();
           //alert(id);
           if(id=="000")
          $("#customproduct").css('display', 'block');
           var text= $(this).find(':selected').text();
         
            var ret = text.split("$");
            var str1 = ret[0];
            var str2 = ret[1];
           // alert(str2);
           $("#product_name").val(str1);
           $('#rate').val(str2);
        })
        
            $("body").on("click", "td.searchbyname", function() {

        document.getElementById("Companyhere").value = $(this).text();
        document.getElementById("company_id").value = $(this).prev().text();
       // document.getElementById("companysearchid").value = $(this).prev().text();
        if (1) {
            console.log("empp");
            document.getElementById("seachbycompanyname").style.display = "none";
        } else {
            console.log("empp n");
            document.getElementById("seachbycompanyname").style.display = "block";
        }


    });
   

    $('#Companyhere').keyup(function(event) {
         event.preventDefault();
        var j = $(this).val();
        if (j == '') {
            console.log("empp");
            document.getElementById("seachbycompanyname").style.display = "none";
        } else {
            console.log("empp n");
            document.getElementById("seachbycompanyname").style.display = "block";
        }
        console.log(j);
        $.ajax({
            type: 'GET',
            url: "{{url('/SearchcompanyByName')}}",
            data: {
                Companyname: j
            },
            success: function(data) {
              //  alert(data);
                $("#seachbycompanyname").html(data);

            }
        });
    });


    $('#Projecthere').keyup(function(event) {
        event.preventDefault();
        var projectname = $(this).val();
        console.log(projectname);
        if (projectname == '') {
            console.log("empp");
            document.getElementById("seachbyprojectnamedata").style.display = "none";
        } else {
            console.log("empp n");
            document.getElementById("seachbyprojectnamedata").style.display = "block";
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

$(document).ready(function() {
  $('option').mousedown(function(e) {
    e.preventDefault();
    var originalScrollTop = $(this).parent().parent().scrollTop();
    $(this).prop('selected', $(this).prop('selected') ? false : true);
    var self = this;
    $(this).parent().parent().focus();
    setTimeout(function() {
        $(self).parent().parent().scrollTop(originalScrollTop);
    }, 0);
    
    return false;
});
});
</script>

@endpush