<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script>
let SalesTracking = () => {
    let startdate = $("#FilterDate").val();
    let enddate = $("#FilterendDate").val();
    console.log(startdate);
    console.log(enddate);
    $("#Nodataavailable").hide();
    if (startdate != '' && enddate != '') {

        if (enddate <= startdate) {
            alert("End date shoud be greated than start date");
            //  return
        }


        //console.log(startdate);
        //console.log(enddate);
        $.ajax({
            type: 'GET',
            url: "{{url('/dashboard/salesCommisionReport/ajax')}}",
            dataType: 'json',
            contentType: "application/json",

            data: {
                "VarA": startdate,
                "VarB": enddate
            },
            beforeSend: function() {
                $("#spinnderLoading").show();
                $("#tableDATA").hide();

                // $("#tableDATA").hide();
                //$("#Nodataavailable").hide();
                //  $("#appendingTableBody").empty();
            },
            success: function(data) {

                var Data = data;
                let tablebody = '';
                var start = 0;
                var GT = 0;
                console.log(Data);
                // alert(Data.length);
                // console.log(json.result[0].RESPONSIBLE_NAME);
                console.log("aadil");
                if (Data.length === 0) {
                    $("#spinnderLoading").hide();
                    $("#Nodataavailable").show();
                } else {
                    $("#tableDATA").show();

                    for (let i = 0; i < Data.length; i++) {
                        console.log(Data[i].name);
                        var edit_route = '{{route("getSingleusersales",":id+:startdate+:enddate")}}';
                        edit_route = edit_route.replace(':id', Data[i].id);
                        edit_route = edit_route.replace(':startdate', startdate);
                        edit_route = edit_route.replace(':enddate', enddate);

                        var pdf_route = '{{route("getSingleuserpdf",":id+:startdate+:enddate+1")}}';
                        pdf_route = pdf_route.replace(':id', Data[i].id);
                        pdf_route = pdf_route.replace(':startdate', startdate);
                        pdf_route = pdf_route.replace(':enddate', enddate);


                        tablebody += "<tr>";
                        tablebody += "<td>" +
                            `${ Data[i].name} ` +
                            "</td>";
                        // tablebody += "<td>" + element.Inforarray.email + "</td>";
                        tablebody += "<td style='text-align:right'>$" + Data[i].price + "</td>";
                        tablebody += "<td style='text-align:right'>$" + Data[i].commission + "</td>";
                        
                        tablebody += "<td style='text-align:right'><a href=" + edit_route +
                            " class='link_color DateandIDselectorForPDF'><i class='fas fa-eye link_color'></i></a>" +
                            "<a href=" + pdf_route +
                            " class = 'link_color' onclick='' > <i class = 'fas fa-download DateandIDselectorForPDF' > </i></a > </td>";
                        tablebody += "</tr>";
                        tablebody += "</tr>";
                        $("#spinnderLoading").hide();

                        $("#appendingTableBody").html(tablebody);

                        //  $("#appendingTableBody").html(tablebody);
                        // $("#showbuttons").show();

                    }
                }

            },


        });
    } else {
        alert("please select date");
        return
    }
}
let SalesTrackingcopy = (start) => {
    let startdate = $("#FilterDate").val();
    let enddate = $("#FilterendDate").val();
    console.log(enddate);
    $('#nextbutton').data('id', start); //setter
    $('#prevbutton').data('id', start); //setter
    $("#Nodataavailable").hide();
    if (startdate != '' && enddate != '') {

        if (enddate <= startdate) {
            alert("End date shoud be greated than start date");
            //  return

        }
        console.log(start + "aadil");
        $.ajax({

            type: 'GET',
            url: "{{url('/dashboard/salesCommisionReport/ajax')}}",
            dataType: 'json',
            contentType: "application/json",

            data: {
                "VarA": startdate,
                "VarB": enddate,
                "start": start
            },
            beforeSend: function() {
                $("#spinnderLoading").show();
                $("#tableDATA").hide();

                $("#showbuttons").hide();

                $("#Nodataavailable").hide();
                $("#appendingTableBody").empty();
            },
            success: function(data) {
                var Data = data;
                let tablebody = '';
                var start = 0;
                var GT = 0;
                console.log(Data);
                // alert(Data.length);
                // console.log(json.result[0].RESPONSIBLE_NAME);
                console.log("aadil");
                if (Data.length === 0) {
                    $("#spinnderLoading").hide();
                    $("#Nodataavailable").show();
                } else {
                    $("#tableDATA").show();

                    for (let i = 0; i < Data.length; i++) {
                        console.log(Data[i].name);
                        tablebody += "<tr>";
                        tablebody += "<td>" +
                            `${ Data[i].name} ` +
                            "</td>";
                        // tablebody += "<td>" + element.Inforarray.email + "</td>";
                        tablebody += "<td>" + Data[i].commission + "</td>";
                        tablebody += "<td>$" + Data[i].price + "</td>";
                        tablebody += "<td><a href=" + edit_route +
                            " class='link_color DateandIDselectorForPDF'><i class='fas fa-eye link_color'></i></a>" +
                            "<a href=" + pdf_route +
                            " class = 'link_color' onclick='' > <i class = 'fas fa-download DateandIDselectorForPDF' > </i></a > </td>";
                        tablebody += "</tr>";
                        tablebody += "</tr>";
                        $("#spinnderLoading").hide();

                        $("#appendingTableBody").html(tablebody);

                        //  $("#appendingTableBody").html(tablebody);
                        //  $("#showbuttons").show();

                    }
                }

            },

        });
    } else {
        alert("please select date");
        return
    }
}
let NextData = (button) => {
    var start = $("#nextbutton").data("id");
    var start = $("#prevbutton").data("id");


    if (button == "N") {
        start = start + 50;
        console.log(start);

        $('#nextbutton').data('id', start); //setter
        $('#prevbutton').data('id', start); //setter

        SalesTrackingcopy(start);

    } else {
        if (start > 0) {
            start = start - 50;
            console.log(start);
            $('#nextbutton').data('id', start); //setter
            $('#prevbutton').data('id', start); //setter
            SalesTrackingcopy(start);
        }
        console.log(start);
        return 0;

    }

}
let DownloadCommisionReport = () => {
    let startdate = $("#FilterDate").val();
    let enddate = $("#FilterendDate").val();

    $("#Nodataavailable").hide();
    if (startdate != '' && enddate != '') {

        if (enddate <= startdate) {
            alert("End date shoud be greated than start date");
            //  return

        }

        var newUrl =
            "/dashboard/salesCommisionReport/ajax?VarA=" + startdate + "&VarB=" +
            enddate + "&download=true";
        window.location = newUrl;

    } else {
        alert("please select date");
        return
    }
}
</script>