@extends('adminlayout.final')

<!-- Page Content  -->
@section('content')



<!-- Page Content  -->
<div id="content" class="p-4 p-md-5 pt-5">
    <div>
        <!--<a href="{{url('/dashboard/timeTracking')}}">  -->
        <!--<i class="fa fa-long-arrow-left" style="font-size:48px;color:red"></i> -->
        <!--</a>-->

        <a onclick="history.go(-1);" href="#" class="link_color"><i class="fas fa-long-arrow-alt-left"></i> <b>Back</b>
            </i></a>

    </div>
    <div style="margin-top: 50px;">
        <?php $name ="" ; foreach ($mainarr1 as $value) { $name = $value['name']; }?>
        @section('title', $name)
        <div>
            <table class="table">
                <thead>
                    <tr>
                        <h2>{{  $name }}</h2>
                    </tr>
                </thead>
                <thead>
                    <tr>

                        <th scope="col">Company Name</th>

                        <th scope="col">Invoice ID</th>
                        <th scope="col">Invoice Date</th>
                        <th scope="col">Payment Date</th>
                        <th scope="col" style='text-align:right'>Invoice Amount</th>
                        <th scope="col" style='text-align:right'>Bonus</th>

                    </tr>
                </thead>
                <tbody class="table_body_color">
                    <?php
                   // dd($mainarr);
                    foreach ($mainarr1 as $value) { ?>
                    <?php echo "<tr>"; ?>


                    <td> <?php echo $value['company_name']; ?> </td>
                    <td> <?php echo $value['invoiceid']; ?> </td>
                    <td> <?php echo $value['invoicedate']; ?> </td>
                    <td> <?php echo $value['paymentdate']; ?> </td>
                    <td style='text-align:right'>$<?php echo number_format($value['price'],2,'.',',');?></td>
                    <td style='text-align:right'>
                        $<?php $bonus=  $value['price']*$value['bonus']/100; echo number_format($bonus,2,'.',',')?></td>
                    <td> <?php echo "</tr>"; ?>
                        <?php  } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection