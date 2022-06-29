@section('title', 'Proposal Template')
<div class="container-fluid mt--7" >
    <div class="row">
        <div class="col-xl-12 mb-5 mb-xl-0">
            <div class="" style="margin-bottom:6%">
                <div class="">
                    <div class="row align-items-center">
                        <div class="col">
                            <h1>Templates</h1>
                            @if(Illuminate\Support\Facades\Auth::user()->role=="Admin")
                               <div class="container" style="margin-top:-10px">
                                <div class="row" style="float:right;margin-top: -17px">
                                    <a href="{{route('admin.create')}}">
                                       <button class="btn btn-success1">Add Proposal Template</button>
                                    </a>
                                </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <table class="table table-bordered table-responsive-lg">
                            <tr>
                                <th>Proposal Template</th>
                                 @if(Illuminate\Support\Facades\Auth::user()->role=="Admin")
                                 <th>Action</th>
                                 @endif
                            </tr>
                            <?php
                            for ($i = 0; $i < count($alltemplatedata); $i++) { 
                            //$url =  "https://portal.codeconspirators.com/index-form.php";

                             //Code to get the file...
                             $data = file_get_contents(base_path()."/resources/views/usertemplate/".$alltemplatedata[$i]['name'].".blade.php");
                            
                             //save as?
                             $filename = $alltemplatedata[$i]['name'].".html";
                            
                             //save the file...
                             $fh = fopen($filename,"w");
                             fwrite($fh,$data);
                             fclose($fh);
                                
                            
                            ?>
                                <tr>
                                    <td><?php echo $alltemplatedata[$i]['name'];
                                    if($alltemplatedata[$i]['name'] =="index-form" )
                                    $notshow = 1;
                                    else 
                                    $notshow = 0; ?></td>
                                     @if(Illuminate\Support\Facades\Auth::user()->role=="Admin")
                                     <th> <?php if(!$notshow) {?> <a href="{{ route('admin.edit', $alltemplatedata[$i]['id']) }}" class="link_color">
                                            <i class="fas fa-edit"></i>
                                                                              <form action="{{route('admin.destroy',$alltemplatedata[$i]['id']) }}" style="width: 18px;
    height: 17px; display: inline" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="delete"
                                                        style="border: none; background-color:transparent;">
                                           <a></a><i class="far fa-trash-alt link_color"></i></a>
                                                </button>
                                            </form>
 <?php } ?>
                                                                                 <a href="{{$filename }}" download class="link_color"><i class="fas fa-download"></i></a>
                                                                                </th>

                                      
                                    
                                    @endif

                                </tr>
                            <?php } ?>



                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>