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
<?php
function convertMinutesToDecimal($minutes)
{
    if($minutes==0) return 0;
   $hours =  $minutes / 60;
   return round($hours,2);
}

?>

<body>

        <table class="table">
                <thead>
                    <tr>
                        <th scope="col" class="spacer">Project Name</th>
                        <th scope="col" class="exspacer">Task Name</th>
                        <th scope="col" class="spacer">Hours</th>
                        <th scope="col" class="exspacer">Date Start</th
                    </tr>
                </thead>
                <tbody class="table_body_color">
                    <?php        $total=0;
                             
                     foreach ($newResponse as $value) { ?>
                        <?php echo "<tr>"; ?>
                        
                        <td> <?php echo $value['projectname']; ?> </td>
                        <td> <?php echo $value['TaskName']; ?> </td>
                        <td> <?php echo $time= convertMinutesToDecimal($value['MINUTES']);
                           $total = $total + $time; ?></td>
                        <td> <?php echo  date("Y-m-d H:i", strtotime($value['DATE_START']));  ?></td>
                        <td> <?php echo "</tr>"; ?>
                        <?php  } ?>
                        
                        <?php if($total > 0) { ?>
            <tr>
                <td><strong>Total</strong></td>
                <td></td>
                
                <td> <strong>{{$total}}</strong></th>
            <td></td>
            </tr>
            <?php } ?>

                        
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