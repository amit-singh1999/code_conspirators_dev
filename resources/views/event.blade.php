@section('head') 
  <script>
    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
        //alert(JSON.stringify(data));
         $('#full_calendar_events').fullCalendar('destroy');
        calendar_fetch();
    });
  </script>
  
  <script>
      
      function calendar_fetch()
      {
        var SITEURL = "{{ url('/') }}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var calendar = $('#full_calendar_events').fullCalendar({
            editable: false,
            events: SITEURL + "/calendardata",
            displayEventTime: true,
            editable: false,
            eventClick: function(event) {                
                jQuery.noConflict();
                if (event.resourceid == 1) {
                    console.log("if worked");
                    $("#successModal").modal("show");
                    $("#eventtitle").text(event.title);                    
                    var link = $("<a>");
                    link.attr("href",event.description);
                    link.attr("target","_blank");
                    link.text("Meeting link");
                    link.addClass("link");
                    $("#container").html(link);
                    $('#successModal').on('hidden.bs.modal', function () {
                        console.log("helllo subal");
                    })                        
                }
                else if(event.resourceid == 3){            
                    $("#successModalhere").modal("show");
                    console.log(event.title);
                    $("#eventtitle1").html(event.title);  
                    $("#eventtitle2").text(event.title);    
                    $("#eventdescription").text(event.description); 
        
                    $('#successModalhere').on('hidden.bs.modal', function () {
                        console.log("helllo bain");               
                    })                        
                        
                }
                else {
                    console.log("else worked");
                    $('#viewtaskmodal').modal('show');
                    console.log(event.id);
                    $('#service_id').val(event.id);
                }                                    
            },        
            loading: function (status){
                if(status==true){
                    document.getElementById('full_calendar_events').querySelector("table").style.display='none';
                    calendar_loader.style.display='block';
                }else{
                    document.getElementById('full_calendar_events').querySelector("table").style.display='block';
                    full_calendar_events.style.display='block';
                    calendar_loader.style.display='none';
                }
            },
            eventRender: function (event, element, view) {
                if (event.allDay === 'true') {
                    event.allDay = true;
                } else {
                    event.allDay = false;
                }
            },
            selectable: false,
            selectHelper: false,
        });
      } 
  </script>
@endsection

