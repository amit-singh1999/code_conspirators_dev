<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <!-- google-fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Special+Elite&display=swap" rel="stylesheet" />
    <!-- Font awosome icon  -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

    <!--<link rel="stylesheet" href="https://codeconspirators.com/_resources/proposal/style.css" />-->
    <link rel="stylesheet" href="https://files.codeconspirators.com/_resources/proposal/style.css" />
    <link rel="stylesheet" href="{{ asset('adminstyle') }}/css/propsal.css" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" />-->
    <!--<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>-->
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <style>
        .headstyle_denger{
            font-family: 'Kantumruy Pro', sans-serif;
            font-size:20px;
            color:red;
        }
        .mksmall {
            font-size: 1.3rem !important;
        }

        /* Accordion */
        .accordion,
        .accordion * {
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .conspirator-item:hover {
            background: #E5CFA2;
        }

        .accordion {
            overflow: hidden;
        }

        /* Section Title */
        .section-title {
            background: none;
            display: inline-block;
            width: 100%;
            padding: 0px;

        }

        section#main {
            height: 87vh !important;
        }

        .section-title.active,
        .section-title:hover {}

        .section:last-child .section-title {
            border-bottom: none;
        }

        .section-title:after {
            /* Unicode character for "plus" sign (+) */
            content: '\02795';
            font-size: 13px;
            color: #FFF;
            float: right;
            margin-left: 5px;
        }

        .section-title.active:after {
            /* Unicode character for "minus" sign (-) */
            content: "\2796";
        }

        /* Section Content */
        .section-content {
            display: none;
            padding: 20px;
        }
    </style>
    <script>
        var j = jQuery.noConflict();
        j(document).ready(function() {
            j('.section-content').dblclick(function(e) {

                close_section();
            })

            j(document).on('click', "#edit-item", function() {


                j(this).addClass(
                    'edit-item-trigger-clicked'
                ); //useful for identifying which trigger was clicked and consequently grab data from the correct row and not the wrong one.

                var options = {
                    'backdrop': 'static'
                };
                jQuery('#edit-modal').modal()
            })

            j('.section-title').click(function(e) {
                // alert(1);
                // Get current link value
                var currentLink = j(this).attr('href');
                if (j(e.target).is('.active')) {
                    close_section();
                } else {
                    close_section();
                    // Add active class to section title
                    j(this).addClass('active');
                    // Display the hidden content
                    j('.accordion ' + currentLink).slideDown(350).addClass('open');
                }
                e.preventDefault();
            });

            function close_section() {
                j('.accordion .section-title').removeClass('active');
                j('.accordion .section-content').removeClass('open').slideUp(350);
            }


        })
    </script>

    <title>Action Plan for {{ $UsernameofQuote->result->NAME }}</title>
</head>

<body>
    <main>

        <div class="main-body-left iborder">


        </div>
        <div class="main-body-right">


            <div class="main-body-content-box">

                <div class="scroll-function">

                    <!-- Home section start  -->
                    <section id="home">
                        <h1 style="color:blue">
                            Welcome,

                            <!--{{ $company_Title }}-->

                            {{ $UsernameofQuote->result->NAME }}.
                        </h1>
                        <p>
                            You're on a mission. We get that, and we're here to support.Here's our plan of attack.
                        </p>
                        <!--<img src="{{ asset('adminstyle') }}/images/e2c92d14-d216-48e5-8bc9-6d9e16ec3286.png" alt="Girl in a jacket" class="Line_break_style">-->
                        <h1 style="font-size: 25px">
                            <a name="MissionBriefing">
                                Mission Briefing
                            </a>
                        </h1>
                        <p>
                            In order to compete in your marketplace, your messaging needs to communicate the value that
                            you deliver, increase conversions and establish multiple lead channels.</p>

                        <h2>Primary objectives of this project are:</h2>
                        <p>
                        <ul>
                            <li>Build a website that works on all devices and engages your customers, with a
                                mobile-first strategy</li>
                            <li>Improve performance issues that impact slow load speed and search ranking</li>
                            <li>Support User Experience that drives conversions</li>
                        </ul>
                        <p>
                            Code Conspirators will prioritize the build and launch of the website to support your brand
                            long term, backend management and content infrastructure in order to serve your target
                            audiences.<br /><br />

                            We will add value in providing the technical expertise and resources you need, during the
                            development phase, and long after the site has launched.
                        </p>
                        <!--<img src="{{ asset('adminstyle') }}/images/e2c92d14-d216-48e5-8bc9-6d9e16ec3286.png" alt="Girl in a jacket" class="Line_break_style">-->
                        <h2>
                            <a name="WebAssessment">
                                Web Assessment
                            </a>
                        </h2>
                        <p>
                            We reviewed your existing web presence with the following results:<br>
                        <ul>
                            <li>
                                Get your <a href="<?php echo $assesmentlink; ?>" target="_blank">Web Assessment results</a>.
                            </li>
                        </ul>
                        </p>

                        <h1>
                            <a name="Process">
                                Process
                            </a>
                        </h1>
                        <h5>Our proprietary process keeps this on time, on budget, and ensures that nothing falls
                            through the cracks.</h5>
                        <p>
                            Code Conspirators’ methods help us to better understand your audience motivators and
                            behaviors that enable us to build creative experiences that drive results. By profiling your
                            target audience, we will develop a User Experience Strategy that drives results.<br /><br />

                            This page outlines our proven four-step approach for delivering an outstanding experience
                            for your organization.
                        </p>

                        <div>
                            <h3 style="margin-bottom: 0px; padding-bottom: 0px;">
                                <a name="Discover">
                                    Discover
                                </a>
                            </h3>
                            <p>
                                We work with your team to help scope your project. This helps to ensure you’ve asked all
                                the technical questions and understand all the implications of your client’s
                                requests.<br /><br />

                                <em>Outputs: Development Plan, Branding Requirements, Functional Requirements,
                                    Information Architecture, Sitemap, Tool Selection</em>
                            </p>
                            <h3 style="margin-bottom: 0px; padding-bottom: 0px;">
                                <a name="Describe">
                                    Describe
                                </a>
                            </h3>
                            <p>
                                We collaborate on the best way to position your message in a way that communicates your
                                brand's value and boosts sales. This work also covers sitemap development and content
                                migration.<br /><br />

                                <em>Outputs: Development Plan, Branding Requirements, Functional Requirements,
                                    Information Architecture, Sitemap, Tool Selection</em>
                            </p>
                            <h3 style="margin-bottom: 0px; padding-bottom: 0px;">
                                <a name="Design">
                                    Design
                                </a>
                            </h3>
                            <p>
                                We can design or review your team’s designs to make sure the User Experience will be
                                compliant with best practices for today and the foreseeable future.<br /><br />

                                <em>Outputs: Graphic Design (Wireframes, 2 Design concepts with 2 Rounds of Revisions,
                                    then Design for remaining page layouts), HTML/CSS for Responsive, CMS Integration,
                                    Testing and Revisions</em>
                            </p>
                            <h3 style="margin-bottom: 0px; padding-bottom: 0px;">
                                <a name="Develop">
                                    Develop
                                </a>
                            </h3>
                            <p>
                                Having converted to pixel-perfect HTML/CSS, we build the approved designs into the
                                Content Management System and develop any custom functions, integrations or automation
                                as needed.<br /><br />

                                <em>Outputs: Integrate Designs and Content into CMS, Front End and Back End, Functional
                                    Integration, Internal QA, Testing across Browsers and Devices</em>
                            </p>
                            <h3 style="margin-bottom: 0px; padding-bottom: 0px;">
                                <a name="Deploy">
                                    Deploy
                                </a>
                            </h3>
                            <p>
                                We perform internal quality checks to ensure the product meets specifications, optimize
                                for page load speed, and launch or assist with launch to ensure a smooth
                                deployment.<br /><br />

                                <em>Outputs: Content Migration, Security Certificate (SSL), CMS Training and
                                    Documentation, Website Launch</em>
                            </p>

                        </div>
                        <br>

                        <div class="highlighted">
                            <h4>
                                It's not enough to have a pretty website.
                            </h4>
                            <p style="margin-left: 20px;">
                                We're going to be looking for:<br /><br>
                                1) Improved Website Performance<br />
                                2) Increased Click-Through Rate<br />
                                3) Increased Web Conversion Rate<br />
                                4) Improved Ranking to support Search Engine Optimization<br />
                                5) Increased Percent of Customers from Digital Marketing
                            </p>
                            <h3>
                                <em>All in the name of increased business performance.</em>
                            </h3>
                        </div>

                    </section>

                    <!-- Home section end  -->
                    <form action="{{ route('createinvoicefortemplate') }}" method="POST">
                        @csrf


                        <!-- Conspirators section start -->
                        <section id="conspirator" class="pt-5">
                            <h1>
                                <a name="Solutions">
                                    Solutions
                                </a>
                            </h1>

                            <p>
                                We've crafted a plan with your success at the center, because you've chosen to work with
                                us for our expertise. But remember: you've also chosen us because our plans are
                                flexible. Want something more? Want something different? Just let us know.
                                <?php if(isset($estimatorlink) and $estimatorlink!=""): ?>
                            <ul>
                                <li>
                                    See the <a href="{{ $estimatorlink }}" target="_blank">Estimator of our proposed
                                        Action Plan</a>.
                                </li>
                            </ul>
                            <?php endif; ?>
                            </p>

                            <h2>
                                <a name="MissionPaths">
                                    Mission Paths
                                </a>
                            </h2>
                            <p>
                                Our solution breaks out as follows. You can make selections below and see impact to
                                pricing structure.<br />
                                <!-- Insert contents from Assumptions field here -->
                            </p>
                            <br />



                            <?php
    $keyscontainer = [];


    $keyshere = array_keys($FinalProductArraycopy);
    $queryselectrorvalue = 0;
    for ($i = 0; $i < count($keyshere); $i++) {
        $keyofcopyarray = $keyshere[$i];


        echo "<h2>" . $keyofcopyarray . "</h2>";
        // print_r($keyofcopyarray);


        $accessdata = $FinalProductArraycopy[$keyofcopyarray];


        for ($j = 0; $j < count($accessdata); $j++) {
            $queryselectrorvalue = $queryselectrorvalue + 1;
            //  echo $accessdata[$j]['productid'];
            $productid = $accessdata[$j]['productid'];
            //  echo "<br>";
            //  echo $accessdata[$j]['productname'];
            $productname = $accessdata[$j]['productname'];
            //  echo "<br>";
            //  echo $accessdata[$j]['productprice'];
            $productprice = $accessdata[$j]['productprice'];
            //  echo "<br>";
            //  echo $accessdata[$j]['productdescription'];
            $productdescription = $accessdata[$j]['productdescription'];
            //  echo "<br>";
            //  echo $accessdata[$j]['Monthly'];
            $Monthly = $accessdata[$j]['Monthly'];
            //  echo "<br>";
            //  echo $accessdata[$j]['prechecked'];
            $prechecked = $accessdata[$j]['prechecked'];
            //  echo "<br>";
            //  echo $accessdata[$j]['ProductSection'];
            $ProductSection = $accessdata[$j]['ProductSection'];
            // dd($ProductSection);
            //  echo "<br>";
            //  echo "subal";
            //  echo "<br>";


            if ($ProductSection == 187) {
                ?>
                            <!--// array_push($containera,$newarrayhere);-->
                            <!--defautl-->
                            <div class="conspirator-item-wrapper mt-5" style="font-size:180%;">
                                <div class="conspirator-item">
                                    <div class="conspirator-item-left">
                                        <h4 class="conspirator-item-title text-capitalize"> <?php echo $productname; ?></h4>

                                        <p class="conspirator-item-text">

                                            <?php echo $productdescription; ?>
                                        </p>
                                    </div>
                                    <div class="conspirator-item-right">
                                        <input type="hidden" name="preselected[]" required
                                            value="<?php echo $productid; ?>" />
                                        <!--//coment for undestanding  code-->
                                        <strong class="conspirator-rate CalculatePricepreselected">
                                            <?php echo '$' . $productprice; ?> <?php echo $Monthly == 1 ? '/Month' : ''; ?> </strong>
                                        <!-- <button class="conspirator-select">&radic; select</button> -->

                                    </div>
                                </div>
                            </div>


                            <?php
            }
            if ($ProductSection == 189) {
                ?>
                            <!--//   array_push($containerb,$newarrayhere);-->
                            <!--checkbox-->
                            <div class="conspirator-item-wrapper mt-5"
                                onclick="SelectRadioAfterclick({{ $queryselectrorvalue }},'c')"
                                style="<?php echo $prechecked == 1 ? 'background-color:rgba(199, 32, 39, 0.2);font-size:180%;' : ''; ?>">

                                <div class="conspirator-item">
                                    <div class="conspirator-item-left">
                                        <h4 class="conspirator-item-title text-capitalize"> <?php echo $productname; ?></h4>

                                        <p class="conspirator-item-text">
                                            <?php echo $productdescription; ?>
                                            <!--It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).-->
                                        </p>
                                    </div>
                                    <div class="conspirator-item-right">

                                        <strong class="conspirator-rate"><?php echo '$' . $productprice; ?> <?php echo $Monthly == 1 ? '/Month' : ''; ?>
                                            <input type="checkbox" onchange="mainFunctionTocalculateprice('c')"
                                                id="{{ $queryselectrorvalue }}" name="Priceconspirator[]"
                                                value="<?php echo $productid; ?>" <?php echo $prechecked == 1 ? 'checked' : ''; ?>></strong>
                                        <!-- <button class="conspirator-select">&radic; select</button> -->
                                    </div>
                                </div>
                            </div>



                            <?php
            }
            if ($ProductSection == 191) {
                ?>
                            <!--container c-->

                            <div class="conspirator-item-wrapper mt-5"
                                onclick="SelectRadioAfterclick({{ $queryselectrorvalue }},'c')"
                                style="<?php echo $prechecked == 1 ? 'background-color:rgba(255, 99, 71, 0.4); font-size:100%;' : ''; ?>">
                                <div class="conspirator-item">
                                    <div class="conspirator-item-left">
                                        <h4 class="conspirator-item-title text-capitalize" style="margin-left:15px">
                                            <?php echo $productname; ?></h4>

                                        <p class="conspirator-item-text" style="margin-left:15px">
                                            <!--t is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).-->
                                            <?php echo trim($productdescription); ?>
                                        </p>
                                    </div>
                                    <div class="conspirator-item-right">
                                        <strong class="conspirator-rate  CalculatePriceCheckbox"
                                            style="margin-left:15px"><?php echo '$' . $productprice; ?><?php echo $Monthly == 1 ? '/Month' : ''; ?>
                                            <input type="checkbox" onchange="mainFunctionTocalculateprice('c')"
                                                id="{{ $queryselectrorvalue }}" name="Priceconspirator[]"
                                                value="<?php echo $productid; ?>" <?php echo $prechecked == 1 ? 'checked' : ''; ?>></strong>
                                        <!-- <button class="conspirator-select">&radic; select</button> -->
                                    </div>
                                </div>
                            </div>

                            <?php
            }
            if ($ProductSection == 193) {
                ?>
                            <!--//   array_push($containerd,$newarrayhere);-->
                            <div class="conspirator-item-wrapper mt-5"
                                onclick="SelectRadioAfterclick({{ $queryselectrorvalue }},'r')">
                                <div class="conspirator-item">
                                    <div class="conspirator-item-left">
                                        <h4 class="conspirator-item-title text-capitalize"><?php echo $productname; ?></h4>

                                        <p class="conspirator-item-text"><br>
                                            <?php echo $productdescription; ?>
                                        </p>

                                    </div>
                                    <div class="conspirator-item-right">
                                        <strong class="conspirator-rate "><?php echo '$' . $productprice; ?> <?php echo $Monthly == 1 ? '/Month' : ''; ?>
                                        </strong>

                                        <div class="CalculatePriceRadio">
                                            <h2 style="display: inline;margin-right: 32px;">Select</h2>
                                            <input type="radio" onchange="mainFunctionTocalculateprice('r')"
                                                id="{{ $queryselectrorvalue }}" name="selectone"
                                                value="<?php echo $productid; ?>">
                                        </div>


                                    </div>
                                </div>
                            </div>




                            <?php }
if ($ProductSection == 0) {
    ?>
                            <!--container c-->

                            <div class="conspirator-item-wrapper mt-5"
                                onclick="SelectRadioAfterclick({{ $queryselectrorvalue }},'c')"
                                style="<?php echo $prechecked == 1 ? 'background-color:rgba(199, 32, 39, 0.2); font-size:200%;' : ''; ?>">
                                <div class="conspirator-item">
                                    <div class="conspirator-item-left">
                                        <h4 class="conspirator-item-title text-capitalize"> <?php echo $productname; ?></h4>

                                        <p class="conspirator-item-text">
                                            <!--t is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).-->
                                            <?php echo trim($productdescription); ?>
                                        </p>
                                    </div>
                                    <div class="conspirator-item-right">
                                        <strong class="conspirator-rate CalculatePriceCheckbox"
                                            data-price="<?php echo -$productprice; ?>"><?php echo '$' . $productprice; ?><?php echo $Monthly == 1 ? '/Month' : ''; ?>
                                            <input type="checkbox"
                                                onclick="SelectRadioAfterclick({{ $queryselectrorvalue }},'c')"
                                                onchange="mainFunctionTocalculateprice('c')"
                                                id="{{ $queryselectrorvalue }}" name="Priceconspirator[]"
                                                value="<?php echo $productid; ?>" <?php echo $prechecked == 1 ? 'checked' : ''; ?>></strong>
                                        <!-- <button class="conspirator-select">&radic; select</button> -->
                                    </div>
                                </div>
                            </div>

                            <?php }


        }

    }


    ?>

                            <div class="row">
                                <div clas="col ml-5" style="display: flex;justify-content: end;">
                                    <h1>
                                        <div id="OnetimepriceAppend"></div>
                                    </h1>
                                </div>

                                <div clas="col ml-5" style="margin-top: -61px;display: flex;justify-content: end;">
                                    <h1>
                                        <div id="MonthlypriceAppend"></div>
                                    </h1>
                                </div>

                            </div>


                        </section>

                        <!-- Conspirators section end  -->

                        <!-- sechdule section start  -->

                        <section id="schedule" class="pt-5">
                            <h2>
                                <a name="Schedule">
                                    Schedule
                                </a>
                            </h2>
                            <p>
                                Our proposal Development Schedule is detailed below:<br />
                                <!-- Future path: Insert contents from Schedule field here -->
                            </p>
                            <div class="schedule-body ">
                                <table class="schedule-table" border="3px">
                                    <thead>
                                        <tr>
                                            <th class="schedule-table--head">Phase</th>
                                            <th class="schedule-table--head">Timeline</th>
                                            <th class="schedule-table--head">Amount Due</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="schedule-table-data">Define</td>
                                            <td class="schedule-table-data">At or Before Kickoff</td>
                                            <td class="schedule-table-data">30% of Total</td>
                                        </tr>
                                        <tr>
                                            <td class="schedule-table-data">Design</td>
                                            <td class="schedule-table-data">Week 4</td>
                                            <td class="schedule-table-data">30% of Total</td>
                                        </tr>
                                        <tr>
                                            <td class="schedule-table-data">Develop</td>
                                            <td class="schedule-table-data">Week 8</td>
                                            <td class="schedule-table-data">30% of Total</td>
                                        </tr>
                                        <tr valign="top">
                                            <td class="schedule-table-data">Deploy + Deliver</td>
                                            <td class="schedule-table-data">Week 12</td>
                                            <td class="schedule-table-data">10% of Total,<br /> +/- any Adjustments
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td class="schedule-table-data">Ongoing</td>
                                            <td class="schedule-table-data"><em>Monthly</em></td>
                                            <td class="schedule-table-data">+ any ongoing Installment<br /> services
                                                provided</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section><br><br><br><br>
                        <!-- schedule section end  -->
                        <!-- Case Studies  -->
                        <h2>
                            <a name="CaseStudies">
                                Case Studies
                            </a>
                        </h2>
                        <div id="InserTcaseStudyOverhere">
                            <?php  foreach($Listofcasestudy as $study)
    //  dd($Listofcasestudy);
{ ?>
                            <h4 class="conspirator-item-title text-capitalize"> {{ $study['caseStudyName'] }}</h4>

                            <div style="">
                                <div style="width: 26%; float:left">
                                    

                                    {{-- <img src="{{ public_path('C:/xampp/htdocs/CC/codeconspirators/public/ClientLogo/' . $study['Client_company_logo']) }}"
                                        alt="" style="width: 150px; height: 150px;" /> --}}
                                        <img src="{{'data:image/png;base64,' . base64_encode(file_get_contents(public_path("ClientLogo/".$study['Client_company_logo'])))}}" alt="" style="width: 150px; height: 120px;" /> 
                                </div>
                                <div style="width: 74%; float:right";>
                                    <?php
                                    $desc = str_replace("<p></p>", "", $study["description"]);
                                    echo $desc;
                                    if(isset($study["moreinfo"]) and $study["moreinfo"]!=""): ?>
                                    <p><a href="{{ $study['moreinfo'] }}" target="_blank" >More info</a></p>
                                    <?php endif; ?>
                                </div>
                                <div style="clear:both"></div>

                            </div>
                            <?php
 }
?>

                        </div>
                        <!-- Case Studies end -->
                        <!-- Assumptions -->
                        <?php if(isset($assumptiondata) and $assumptiondata!=""):
?>
                        <h1>
                            <a name="Assumptions">
                                Assumptions
                            </a>
                        </h1>
                        <div id="InsertAssumption">
                            <p>
                                {{ $assumptiondata }}

                            </p>

                        </div>
                        <?php endif;  ?>
                        <!-- Case Studies end -->

                        <!-- service agremeent  -->
                        <h1>
                            <a name="ServiceAgreement">
                                Service Agreement
                            </a>
                        </h1>



                        <p class="headstyle">
                            <strong><span class="spantext">PROVISIONS OF AGREEMENT</span></strong>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">Upon completion of the project and payment in full
                                of all invoices, Client shall retain ownership rights to work performed within the
                                confines of this agreement, including but not limited to the Website or Web Application
                                in its delivered state.</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">Fees paid to Code Conspirators by the client are
                                for services provided, including but not limited to consultation, strategy, design, and
                                development. Additional fees may be incurred for any licenses, royalties, or other costs
                                that are not associated with services provided
                                by Code Conspirators. [Additional anticipated fees, if any, will be listed in the Fee
                                Schedule and agreed to before they are incurred]. Any amount over the agreed proposed
                                amount needs to be written client approval before
                                proceeding with work and incurring fees.</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">Code Conspirators offers support at no charge for
                                30 days following deployment. No maintenance contract is required thereafter, but the
                                Client will have the rights to manage the Website, and to secure assistance from Code
                                Conspirators for additional support at Code
                                Conspirators&rsquo;s then prevailing rates, or by engaging in a Retainer Agreement with
                                Code Conspirators, or to engage support from a third-party provider at the
                                Client&rsquo;s discretion.</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">Code Conspirators retains the right to reference
                                the project in digital or printed collateral, including but not limited to a portfolio,
                                case study, or client list.</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">Code Conspirators retains the right of ownership
                                of the intellectual property to any computer software and other intellectual property
                                underlying, incorporated into or embedded in the Website or used in custom modules and
                                technologies and provided by Code Conspirators or
                                its suppliers, as well as any</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">(i) search mechanisms, software &quot;engines&quot;, scripting
                                routines, and other higher-level or proprietary languages (together with any graphic or
                                digital video extensions thereof),</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">(ii) pre-existing or non-specific content, text,
                                illustrations, and other graphical elements, and</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">(iii) general know-how, expertise, concepts,
                                authoring tools, designs, programs, devices, methods, techniques (including video and
                                audio digitalization techniques) and processes utilized by Code Conspirators in the
                                course of its performance hereunder, and the
                                right to utilize those technologies in future endeavors for other clients, provided that
                                Code Conspirators does not utilize Client confidential information in such
                                endeavors.</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">Client retains the right of ownership of the
                                Website itself and the right to use all computer software (including all computer
                                programming code and documentation) incorporated into or embedded in the Website or used
                                in custom modules and technologies and provided by Code Conspirators or its suppliers,
                                as well as any</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">(i) search mechanisms, software &quot;engines&quot;, scripting
                                routines, and other higher-level or proprietary languages (together with any graphic or
                                digital video extensions thereof),</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">(ii) pre-existing or non-specific content, text,
                                illustrations, and other graphical elements, and</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">(iii) the results of general know-how, expertise,
                                concepts, authoring tools, designs, programs, devices, methods, techniques (including
                                video and audio digitalization techniques), and processes utilized by Code Conspirators
                                in the development of the Website.</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">Upon deployment of the marketing campaign, Client
                                will not be in any way tied to, committed to, or otherwise dependent on Code
                                Conspirators for successful operation, maintenance, or accessibility of the Website or
                                any component or asset thereof.</span>
                        </p>

                        <p class="headstyle">
                            <strong><span class="spantext">SCOPE OF AGREEMENT</span></strong>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">Funds deposited with Code Conspirators for work
                                performed are non-refundable, except as expressly set forth in this agreement.</span>
                        </p>

                        <p class="headstyle">
                            <strong><span class="spantext">Client Participation</span></strong><span
                                class="spantext">&nbsp;- As with any project, the more we know about
                                the driving business factors, requirements, and expectations, the better we can prepare
                                our staff to meet all project requirements. In order to fully perform the services
                                outlined in this agreement, a certain level of
                                client participation is required in each project we work on.</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">Some projects require less participation than
                                others, while some require almost daily or weekly meetings and updates. The parties will
                                discuss such level of participation upon commencement and during the course of the
                                services, and Client will provide such information
                                and participation reasonably required by Code Conspirators in order to perform its
                                obligations hereunder, in addition to the information requested to be provided by Client
                                as set forth in this agreement.</span>
                        </p>

                        <p class="headstyle">
                            <strong><span class="spantext">Electronic
                                    Communication</span></strong><span class="spantext">&nbsp;- By
                                approving this proposal you give your consent to Code Conspirators to communicate to you
                                via email and/or print communications. Future communications may include automated voice
                                or other communication mediums as they become available.<br>&nbsp;Agreement Term - In
                                the event of any ongoing services provided by Code Conspirators, this agreement will
                                take effect on the date accepted and signed by the Client below (&ldquo;Effective
                                Date&rdquo;) and will remain in effect for a period of twelve (12) months (the
                                &ldquo;Initial Term&rdquo;). Upon expiration of the Initial Term, any continuation shall
                                be via execution of a new agreement.</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">There are only recurring services within the scope
                                of this agreement if they are selected on the Next Steps page.</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">Customers may cancel any applicable monthly
                                recurring services at any time with thirty (30) days written notification and receive a
                                refund for pre-paid fees for services in months not yet performed.</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">Upon termination of this agreement for any reason,
                                all amounts outstanding shall become immediately due and payable, including, but not
                                limited to, any fees for website design that have not been paid in full as of the
                                termination effective date.</span>
                        </p>

                        <p class="headstyle">
                            <strong><span class="spantext">Payment</span></strong><span class="spantext">&nbsp;-
                                Payment may be made by check,
                                credit card, or ACH. Please see the details on any invoice from Code
                                Conspirators.</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">&nbsp;</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">The client agrees to pay any such outstanding
                                amounts due within the agreed-upon terms upon receipt of an invoice from Code
                                Conspirators. In the event, Client fails to pay any invoice in full when due, Client
                                shall, in addition to the invoice amount, pay Code Conspirators interest on such unpaid
                                balance at the lesser of one percent (1%) simple interest per month or the highest rate
                                of interest permitted by law with respect to such balance.</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">The client shall also reimburse Code Conspirators
                                for any collection costs incurred by Code Conspirators, including attorneys&rsquo;
                                fees.</span>
                        </p>

                        <p class="headstyle">
                            <strong><span class="spantext">Arbitration</span></strong><span class="spantext">&nbsp;-
                                The parties have agreed
                                that the validity, interpretation, implementation, and resolution of disputes of this
                                agreement shall be governed by the laws of the State of Georgia, without reference to
                                its conflicts of law principles.</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">In the event a dispute arises pursuant to this
                                agreement, including any dispute regarding its breach, termination, validity, or
                                interpretation, the parties agree to attempt in good faith to resolve such dispute by
                                consultation of the parties.</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">If the parties are unable to resolve such dispute
                                within 20 days of the first notice of the dispute, after reasonable attempts to do so,
                                the parties agree to submit the dispute to binding arbitration pursuant to the
                                then-existing Commercial Arbitration Rules of the
                                American Arbitration Association. Such arbitration shall take place in Milton, Georgia
                                before a single arbitrator.</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">The parties shall endeavor to select a mutually
                                acceptable arbitrator knowledgeable about issues relating to the subject matter of this
                                agreement.</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">The arbitrator shall base his decision on the
                                provisions of this agreement and relevant governing law.</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">The arbitrator shall not award punitive or
                                exemplary damages. Any judgment or award rendered by such arbitrator shall be final and
                                binding on all parties to the proceeding, may be entered into with the highest court of
                                competent jurisdiction for enforcement as a
                                final judgment adjudication. The prevailing party will be awarded reasonable attorney
                                fees, together with any costs and expenses, to resolve the dispute and to enforce the
                                final arbitration award, except as otherwise
                                determined by the arbitrator.</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">All arbitral proceedings and documents exchanged
                                during arbitration shall be held confidential by both parties and the arbitrator.</span>
                        </p>

                        <p class="headstyle">
                            <span class="spantext">Notwithstanding anything in this agreement to the
                                contrary, either party may seek interim and/or permanent injunctive or declaratory
                                relief as appropriate, from any court of competent jurisdiction, and any injunctive or
                                declaratory relief so obtained shall not be subject
                                to arbitral review.</span>
                        </p>

                        <p class="headstyle">
                            <strong><span class="spantext">LIMITATIONS OF OFFER</span></strong>
                        </p>

                        <p class="headstyle_denger">
                            <span class="spantext">This proposal is valid for 30 days from the date
                                of submission.</span>
                        </p>
                </div>
                <!-- section-content end -->
            </div>
            <!-- section end -->

        </div>
        <!-- accordion end -->
        <!-- Attachment Accordian end -->
        <!-- service agremeent end -->
        <!-- next steps  -->
        <h1>
            <a name="NextSteps">
                Next Steps
            </a>
        </h1>
        <p>
            Ready to get started? <span style="color:#222222;"><strong>Awesome, so are we!</strong></span><br /><br />
            Please <br><span style="color:#222222;"><strong>1) Make your selections (above)</strong></span><br>
            <br><span style="color:#222222;"><strong>2) Sign (below)</strong></span><br> You'll receive an initial
            invoice shortly, and a Strategist will reach out to get this moving.
        </p><br>

        <div class="container">
            <br>
            <?php echo isset($msg) ? $msg : ''; ?>
            <h4>Sign below:</h4>
            <hr>
            <div id="canvasDiv" style="width:100%; background:white;opacity: 0.8;"></div>
            <br>
            {{-- <button type="button" class="btn btn-danger" id="reset-btn">Clear</button> --}}
            <!--<button type="button" class="btn btn-success" id="btn-save">Save</button>-->
            <!--<form id="signatureform" action="" style="display:none" method="post">-->
            <input type="hidden" id="signature" name="signature">
            <input type="hidden" id="pdflink" name="pdflink" value="">
            <input type="hidden" id="templink" name="templink" value="">
            <input type="hidden" name="signaturesubmit" value="1">
            <!--</form>-->
        </div>

        <input type="hidden" id="custId" name="dealIdhere" value="{{ $datanew->result->ID }}">
       
        {{-- <button type="submit" style="float:right; font-weight:800; font-size: 1.8rem; height: 41px;width: 137px;"
            class="btn btn-primary" id="btn-save">Submit</button> --}}
        </form>

        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
            integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
        <!--//product price calculation-->




        <!-- next steps end -->

        <div>
            <p style="font-size:12px;">
                Copyright by Code Conspirators, All Rights Reserved.
            </p>
        </div>

        </div>
        </div>

        </div>
        </div>
        </div>
        </section>

</body>

</html>

</html>
