<div>
    <?php 
    //print_r($data);
    //dd($data); ?>
    <div>Hi {{ $strategist}},</div>
    <?php 
    $f_name = explode(" ", $client_name);
    if($f_name[0]==$company_name)
    $string =$client_name;
    else
    $string = $client_name." at ". $company_name;
    ?>
    <div><br>
 {{$string}} has signed the Action Plan for deal <a href= "{{$deallink}}" >{{$dealname}}</a> for ${{$dealamt}}: <br>
<?php
foreach($data as  $data1)
{
if(str_contains($data1['PRODUCT_NAME'],'Discount:') or str_contains($data1['PRODUCT_NAME'],'Credit'))

echo $data1['PRODUCT_NAME'].": -$".$data1['PRODUCT_PRICE'].",<br>";   
else
 echo $data1['PRODUCT_NAME'].": $".$data1['PRODUCT_PRICE'].",<br>";   
}

?>
    </div>
    <div><br>
        </div>
    <div><br>
       Thanks!
    </div>

</div>
</div>
<table cellpadding="0" cellspacing="0"
       style="vertical-align: -webkit-baseline-middle; font-size: medium; font-family: Arial;">
    <tbody>
    <tr>
        <td>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <div style="width: 10px; height:10px;"></div>
        </td>
        <td valign="top">
            <table cellpadding="0" cellspacing="0" class="sc-jDwBTQ dWtMUn"
                   style="vertical-align: -webkit-baseline-top; font-size: medium; font-family: Arial;">
                <tbody>
                <tr>
                    <td style="vertical-align: middle;">
                        <div style="width: 30px; height:10px;"></div>
                    </td>
                </tr>
                <tr>
                    <td valign="top" style="vertical-align: top; padding-top:5px; padding-right:10px;">
                        <h3 color="#444444" class="sc-jhAzac hmXDXQ"
                            style="margin: 0px; font-size: 18px; color: rgb(68, 68, 68);"><span>
                                        Codee</span></h3>

                        <p color="#444444" font-size="medium" class="sc-fMiknA bxZCMx"
                           style="margin: 0px; font-weight: 500; color: rgb(68, 68, 68); font-size: 14px; line-height: 22px;">
                            <span></span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td valign="top" style="vertical-align: top; padding-top:5px; padding-right:10px;">
                        <div style="width: 1px; height:5px;"></div>
                    </td>
                </tr>
                <tr>
                    <td valign="top" align="left"
                        style="vertical-align: top; text-align: left; padding-top:5px; padding-right:10px;"><a
                            href="https://codeconspirators.com" target="_blank"><img
                                src="http://codeconspirators.com/wp-content/uploads/2019/10/logo-CC-horz-250.jpg"/></a>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<br/>
<br/>