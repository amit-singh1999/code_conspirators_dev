<script>

  let DownloadTimeTrackingresultasPDf = () => {
      console.log("button clicked ");
      const matches = document.querySelectorAll(".DateandIDselectorForPDF");
      let value;
      let Datarraytocontroller = [];
        if(matches.length > 0){
              for (const element of matches) {
              let Linkattribute =  element.href;
              console.log(Linkattribute);
              value = RegexFunctiontoExtractdate(Linkattribute);
              console.log(value);
              Datarraytocontroller.push(value);
              
        }
      }
      
        console.log(Datarraytocontroller);
        $("#ArraytopasSincontroller").val(Datarraytocontroller);
        var elem = document.getElementById('TimereportPDfgeneretaall');
		elem.submit();
		
        
    //      $.ajaxSetup({
             
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
            
    //      });
           
          
    //   $.ajax({
    //         type:'POST',
    //         url: '/dashboard/TimetrackingDownloadAspdf/', 
    //         data:{name:"hello"},
    //         beforeSend: function() {
    //                 console.log(" before send");
    //             },
    //       success:function(data){
    //           console.log("hey there");
    //           console.log(data);
    //       },
    //       complete: function(data) {
				// 	console.log("after send");
    //             }
    //     });
        
        
       
       
       
         }
  
  
        
          let RegexFunctiontoExtractdate = (link) => {
            
          const regex = /[0-9\-+]+[0-9]$/gm;
          const str = link;
          let m;
          let subal;
          while ((m = regex.exec(str)) !== null) {
            // This is necessary to avoid infinite loops with zero-width matches
            if (m.index === regex.lastIndex) {
              regex.lastIndex++;
            }
              return m[0];
          }
        };
             


</script>