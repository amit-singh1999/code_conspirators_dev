
<script>
$(document).ready(function() { 
    
                console.log("inside case study");
                let Link = $('#templink').val();
                const regex = /\/yourtemplate\//;
                const str = Link ;
                console.log(Link);
                const subst = ``;
                const result = str.replace(regex, subst);
                $.ajax({
                url: '/subalTesting/{id}',
                type: 'GET',
                data: { id: result},
                beforeSend: function() {
                    console.log(" before send");
                },
                success: function(data) {
                    console.log(data);
                    let datatoInsert = '';
                    let Data = data.data;
                       for (const element of Data) {
                        console.log(element);
                        datatoInsert +=   '<div>';  
                        datatoInsert +=   '<div>';
                        datatoInsert +=    '<img  height="200" width="200" src="https://portal.codeconspirators.com/ClientLogo/'+element.Client_company_logo +'" alt="Girl in a jacket" width="500" height="600">';
                        datatoInsert +=     '</div>';
                        datatoInsert +=     '<div>';
                        datatoInsert +=     '<h2>' + element.Clientname +'</h2>';
                        datatoInsert +=     '</div>';
                        datatoInsert +=     '<div>' + element.description +'</div>';
                        datatoInsert +=     '</div>';
                    }
                      $("#InserTcaseStudyOverhere").html(datatoInsert);
                },
                complete: function(data) {
					console.log("after send");
                }
                
              });
    
});

               
                  
</script>