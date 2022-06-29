<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Performance Bonus Report</title>
    <style>
    .spacer {
        width: 20%;
        text-align: left;

    }

    .exspacer {
        width: 30%;
        text-align: left;

    }

    .divTable {
        display: table;
        width: 100%;
        text-align: left;

    }
    </style>
</head>

<body>

    <table class="table divTable table-borderless">
        <thead>
            <tr>

                <th scope="col" class="spacer">Name</th>
                <th scope="col" class="spacer">Company Name</th>
                <th scope="col" class="spacer">Invoice Id</th>
                <th scope="col" class="spacer">Invoice Date</th>
                <th scope="col" class="spacer">Payment Date</th>
                <th scope="col" class="spacer">Amount</th>
                <th scope="col" class="spacer">Bonus</th>

            </tr>
        </thead>
        <tbody>

            <?php   //print_r($mainarr); die("shifa");?>
            @foreach($mainarr as $Valueofarray)



            <tr>
                <th scope="row" class="spacer">{{ $Valueofarray['name'] }}</th>
                 <td class="spacer">{{ $Valueofarray['company_name'] }}</th>
                <td class="spacer">{{$Valueofarray['invoiceid']}}</td>
                <td class="spacer">{{$Valueofarray['invoicedate']}}</td>
                <td class="spacer">{{$Valueofarray['invoicedate']}}</td>
                <td class="spacer"><?php $bonus=  $Valueofarray['price']*$Valueofarray['bonus']/100; ?>${{number_format($Valueofarray['price'],2,'.',',')}}</td>
                <td class="exspacer">${{number_format($bonus,2,'.',',')}}</td>
            </tr>
            @endforeach


            <?php //die("123");?>
        </tbody>
    </table>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
</body>

</html>