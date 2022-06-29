<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script>
let BonusTracking = () => {
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
            url: "{{url('/dashboard/performanceBonusReport/ajax')}}",
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
                        var edit_route =
                            '{{route("getSingleuserbonus",":id+:startdate+:enddate")}}';
                        edit_route = edit_route.replace(':id', Data[i].id);
                        edit_route = edit_route.replace(':startdate', startdate);
                        edit_route = edit_route.replace(':enddate', enddate);

                        var pdf_route =
                            '{{route("getSingleuserbonuspdf",":id+:startdate+:enddate+1")}}';
                        pdf_route = pdf_route.replace(':id', Data[i].id);
                        pdf_route = pdf_route.replace(':startdate', startdate);
                        pdf_route = pdf_route.replace(':enddate', enddate);


                        tablebody += "<tr>";
                        tablebody += "<td >" +
                            `${ Data[i].name} ` +
                            "</td>";
                        // tablebody += "<td>" + element.Inforarray.email + "</td>";

                        tablebody += "<td style='text-align:right'>$" + Data[i].bonus + "</td>";
                        tablebody += "<td style='text-align:right'>$" + Data[i].price + "</td>";
                        tablebody += "<td ><a href=" + edit_route +
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

let DownloadBonusReport = () => {
    let startdate = $("#FilterDate").val();
    let enddate = $("#FilterendDate").val();

    $("#Nodataavailable").hide();
    if (startdate != '' && enddate != '') {

        if (enddate <= startdate) {
            alert("End date shoud be greated than start date");
            //  return

        }

        var newUrl =
            "/dashboard/DealsCommisionReport/ajax?VarA=" + startdate + "&VarB=" +
            enddate + "&download=true";
        window.location = newUrl;

    } else {
        alert("please select date");
        return
    }
}
</script>