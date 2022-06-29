<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script>
    let getdataTimeTracking = () => {
        console.log("hii my name is subal")
        let startdate = $("#Timetrackingdatestart").val();
        let enddate = $("#Timetrackingdatend").val();
        console.log(startdate);
        console.log(enddate);
        if (startdate != '' && enddate != '') {

        if(enddate <= startdate){
            alert("End date shoud be greated than start date");
            return
        }


            console.log(startdate);
            console.log(enddate);
            $.ajax({
                type: 'GET',
                url: "{{url('/dashboard/timeTrackinggetdata')}}",
                dataType: 'json',
                data: {
                    "VarA": startdate,
                    "VarB": enddate
                },
                beforeSend: function() {
                    $("#spinnderLoading").show();
                    $("#tableDATA").hide();
                    $("#Nodataavailable").hide();
                    $("#appendingTableBody").empty();
                },
                success: function(data) {
                    console.log(data);
                    console.log("inside else");
                    console.log(data);
                    let Data = data.data;
                    var vara = 10;
                    let tablebody = '';
                    console.log("subal");
                    // console.log(data);
                    console.log("subal");
                     var GT = 0;
                    for (const element of Data) {
                          GT = GT + element.totalMinutes;
                        var edit_route = '{{route("getSingletime",":id+:startdate+:enddate")}}';
                        edit_route = edit_route.replace(':id', element.KeyID);
                        edit_route = edit_route.replace(':startdate', element.startdate);
                        edit_route = edit_route.replace(':enddate', element.enddate);
                        console.log(element);
                        tablebody += "<tr>";
                        tablebody += "<td>" + `${ element.Inforarray.firstname}   ${ element.Inforarray.lastname} `+ "</td>";
                        // tablebody += "<td>" + element.Inforarray.firstname + "</td>";
                        // tablebody += "<td>" + element.Inforarray.email + "</td>";
                        tablebody += "<td>" + element.totalMinutes + "</td>";
                      //  tablebody += "<td><a href=" + '"' + edit_route + '"' + ' class="link_color DateandIDselectorForPDF"' + ">View Report</a> </td>";
                    tablebody += "<td><a href=" +  edit_route + " class='link_color DateandIDselectorForPDF'><i class='fas fa-eye link_color'></i></a>" +
                        "<a href='' class = 'link_color' onclick='DownloadTimeTrackingsinglePDf()' > <i class = 'fas fa-download DateandIDselectorForPDF' > </i></a > </td>";
                        tablebody += "</tr>";
                         
                    }
                    tablebody += "<tr><td><strong>Grand Total</strong></td> <td><strong>" + GT.toFixed(2) +"</strong></td><td></td></tr>";
                    $("#appendingTableBody").html(tablebody);
                },
                complete: function(data) {
                    console.log("complete");
                    var tablevalue = $("#appendingTableBody").html();
                    console.log(tablevalue);
                    if (tablevalue == '') {
                        console.log("empty hai");
                        // $('#NotfoundCard').html('your HTML');
                        $("#spinnderLoading").hide();
                        $("#Nodataavailable").show();
                        $("#DownloadTimeTrackingresultasPDfButtOn").html('');


                    } else {
                        $("#spinnderLoading").hide();
                        $("#tableDATA").show();
                        var buttonDownloadasPDf = '<button type="button" class="btn btn-success1"  onclick="DownloadTimeTrackingresultasPDf()">Download PDF</button>';
                        $("#DownloadTimeTrackingresultasPDfButtOn").html(buttonDownloadasPDf);
                        
                    }

                }
            });
        } else {
            alert("please select date");
            return
        }
    }
</script>