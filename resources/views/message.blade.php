
@extends('layouts.app')

@section('content')
  @include('layouts.headers.cards') 
  <div class="container-fluid mt--7" style="background-color:#DCDCDC">
        <div class="row">
            <div class="col-xl-12 mb-5 mb-xl-0">
                <div class="card bg-gradient-default shadow" style="margin-bottom:6%">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2><b>Messages</b></h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="chart">
                                <?php
                                $check =0;
                                        for($i=0;$i<count($msg);$i++){
                                            if($msg[$i]){
                                                for($j=0;$j<count($msg[$i]);$j++){
                                                    $msg_date = new \DateTime($msg[$i][$j]['date']);
                                                    $msg_date = $msg_date->format('Y-m-d');
                                                    $check = 1;
                                                    $time1 = new \DateTime($msg[$i][$j]['date']);
                                                    $date = $time1->format('M d, Y');
                                                    if (!empty($msg[$i][$j]['detailtext'])) {
                                                        echo "<p class='d-inline' style='font-size:16px'><b>".$msg[$i][$j]['detailtext']."</b></p>";
                                                    }
                                                     echo "<p  class='d-inline' style='color:#999999;font-size:12px'>". "    " .$date."</p><br><br>";
                                                    if (!empty($msg[$i][$j]['youtubelink'])) {
                                                    $link="https://";
                                                    $link=$link.$msg[$i][$j]['youtubelink'];
                                                    echo '<div class="iframe-container" style="margin-bottom:30px">
                                                       <iframe class="custom_image_sizing1" id="custom_image_sizing" src="'.$link.'" frameborder="0" style="overflow: hidden; height: 100%; width: 100%; position: absolute;" height="100%" width="100%"></iframe>
                                                    </div>';
                                                    }
                                                    if($msg[$i][$j]['file']){ 
                                                        $dir = '/files'."/".$msg[$i][$j]['file'];
                                                        ?>
                                                        <div class="iframe-container">
                                                            <iframe class="custom_image_sizing1" id='custom_image_sizing' src="{{asset($dir)}}" frameborder="0" style="overflow: hidden; height: 100%; width: 100%; position: absolute;" height="100%" width="100%"></iframe>
                                                        </div>
                                                        <br>
                                                    <?php
                                                    
                                                    }
                                                }
                                            }
                                        }
                                        if($check == 0){
                                            echo "<h4>You have no active Messages at this time. Check back soon!</h4>";
                                        }
                                    ?>
                                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
  @include('layouts.footers.auth')
@endsection



@push('js')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
// $('footer').css('position', 'absolute');
//  $('footer').css('bottom', '0');
//  $('footer').css('width', '100%');
 
});
</script>

<script>
    $('.custom_image_sizing1').on('load', function() {
        $('.custom_image_sizing1').contents().find('img').css("width", "100%");
        $('.custom_image_sizing1').contents().find('img').css("height", "100%");
        var images = $('.custom_image_sizing1').contents().find('img');
        console.log(images);
    });
</script>


    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush