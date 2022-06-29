
<style>
/* Style the buttons */
.btn1 {
  border: none;
  border-color:#e1e2eD;
  outline: none;
  padding: 10px 16px;
  background-color: #f1f1f1;
  cursor: pointer;
  font-size: 14px;
  width:180px;
  line-height:23.8px;
  border-radius:7px;
}

/* Style the active class, and buttons on mouse-over */
.active, .btn1:hover {
  background-color: #c72027;
  color: white;
}
#myDIV{
          padding-top: 52px;
    padding-left: 49px;
}
.clr{
    clear:both;
}
.mydsn{
     float: left;
   
    width: 100%;
    
}
</style>
<div class="clr"></div>
<div class="mydsn">
<div id="myDIV">
 @if(str_contains(url()->current(), '/dashboard/timeTracking'))
     <a href="{{url('/dashboard/timeTracking')}}"><button class="btn1 active">Time Tracking</button></a>
@else
 <a href="{{url('/dashboard/timeTracking')}}"><button class="btn1">Time Tracking</button></a>
@endif

 @if(str_contains(url()->current(), '/dashboard/salesCommisionReport'))
     <a href="{{url('/dashboard/salesCommisionReport')}}"><button class="btn1 active">Sales Commission</button></a>
 @else 
    <a href="{{url('/dashboard/salesCommisionReport')}}"><button class="btn1 act">Sales Commission</button></a>
 @endif

 @if(str_contains(url()->current(), '/dashboard/performanceBonusReport'))
     <a href="{{url('/dashboard/performanceBonusReport')}}"><button class="btn1 active">Performance Bonus</button></a>
      @else 
      <a href="{{url('/dashboard/performanceBonusReport')}}"> <button class="btn1">Performance Bonus</button></a>
@endif


</div>
<script>
// Add active class to the current button (highlight it)
var header = document.getElementById("myDIV");
var btns = header.getElementsByClassName("btn1");
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function() {
  var current = document.getElementsByClassName("active");
  current[0].className = current[0].className.replace(" active", "");
  this.className += " active";
  });
}
</script>


