<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Time Tracking Report</title>
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
                <th scope="col" class="spacer">User</th>
                <th scope="col" class="exspacer">Project</th>
                <th scope="col" class="spacer">Task</th>
                <th scope="col" class="spacer">Hours</th>
            </tr>
        </thead>
        <tbody>

            <?php $updatekey = 0; $Grandtotal=0;  ?>
            @foreach($newmainarrayfinal as $key =>$data)

            <?php $total=0; ?>
            @foreach($data as $Valueofarray)
            <?php  if ($key > 0 && $updatekey != $key)
            {
            $updatekey = $key; ?>
            <tr>
                <th colspan="3">&nbsp;</th>

            </tr>
            <tr>
                <th colspan="3">&nbsp;</th>

            </tr>

            <tr>
                <th colspan="3">&nbsp;</th>

            </tr>

            <?php
            } 
            $total = $total + $Valueofarray['MINUTES'];
            $Grandtotal= $Grandtotal + $Valueofarray['MINUTES'];
            ?>

            <tr>
                <th scope="row" class="spacer">{{ $Valueofarray['username'] }}</th>
                <td class="exspacer">{{$Valueofarray['projectname']}}</td>
                <td class="spacer">{{$Valueofarray['TaskName']}}</td>
                <td class="spacer">{{$Valueofarray['MINUTES']}}</td>
            </tr>
            @endforeach
            <?php if($total > 0) { ?>
            <tr>
                <td><strong>Total</strong></td>
                <td></td>
                <td></td>
                <td> <strong>{{$total}}</strong></th>
            </tr>
            <?php } ?>

            @endforeach
            <tr>
                <td><strong>Grand Total</strong></td>
                <td></td>
                <td></td>
                <td> <strong>{{$Grandtotal}}</strong></th>
            </tr>
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