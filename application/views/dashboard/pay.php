<?php
// ini_set('display_errors', 1);
// ini_set('log_errors', 1);
ini_set('error_log', dirname(__file__) . '/error_log.txt');
// error_reporting(E_ALL ^ E_NOTICE);
// session_start();
// if(!isset($_SESSION['Username'])){
//     header("location: index");
// }
// ========================= config the languages ================================
// error_reporting(E_NOTICE ^ E_ALL);
// if (is_file('home.php')){
//     $path = "";
// }elseif (is_file('../home.php')){
//     $path =  "../";
// }elseif (is_file('../../home.php')){
//     $path =  "../../";
// }
// include_once $path."langs/set_lang.php";
?>
<!DOCTYPE html>
<html translate="no" dir="<?php echo lang('html_dir'); ?>">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>almaqar</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="<?php echo base_url();?>Asset/assets/img/almaqar.png" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v5.13.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    <!-- Third party plugin CSS-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="<?php echo base_url();?>Asset/assets/css/styles.css" rel="stylesheet" />
    <style>
        .btn {
            border: none; /* Remove borders */
            color: black; /* Add a text color */
            padding: 14px 28px; /* Add some padding */
            cursor: pointer; /* Add a pointer cursor on mouse-over */
            border-radius: 29px;
        }
        .btnlogin {
            border: 2px solid black;
            background-color: white;
            color: black;
            padding: 14px 28px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 29px;
        }


        .arrows {
            width: 60px;
            height: 72px;
            position: absolute;
            left: 50%;
            margin-left: -30px;
            bottom: 20px;
        }

        .arrows path {
            stroke: orange;
            fill: transparent;
            stroke-width: 1px;
            animation: arrow 2s infinite;
            -webkit-animation: arrow 2s infinite;
        }

        @keyframes arrow
        {
            0% {opacity:0}
            40% {opacity:1}
            80% {opacity:0}
            100% {opacity:0}
        }

        @-webkit-keyframes arrow /*Safari and Chrome*/
        {
            0% {opacity:0}
            40% {opacity:1}
            80% {opacity:0}
            100% {opacity:0}
        }

        .arrows path.a1 {
            animation-delay:-1s;
            -webkit-animation-delay:-1s; /* Safari 和 Chrome */
        }

        .arrows path.a2 {
            animation-delay:-0.5s;
            -webkit-animation-delay:-0.5s; /* Safari 和 Chrome */
        }

        .arrows path.a3 {
            animation-delay:0s;
            -webkit-animation-delay:0s; /* Safari 和 Chrome */
        }

        .warninglogin {
            border-color: #ffce61;
            color: orange;
        }

        .warninglogin:hover {
            background:#ff9800;
            color: white;
        }
    </style>
</head>
<body id="page-top">
<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
    <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">almaqar</a>
        <img src="<?php echo base_url();?>Asset/assets\img\almaqar.png" style="width: 50px;">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto my-2 my-lg-0">
                <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#pricing">الباقات</a></li>
                <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#contact">اتصل بينا</a></li>
                <li class="nav-item"><a class="nav-link js-scroll-trigger" href="logout" style="color: #FFCE6B;"><?php echo lang('logout'); ?></a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- Masthead-->
<header class="masthead">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-10 align-self-end">
                <h1 class="text-uppercase text-white font-weight-bold">لقد تمت المدة التجريبيه للمنظومه و عليك الاشتراك او التجديد</h1>
                <hr class="divider my-4" />
            </div>
            <a href="#pricing">
                <svg class="arrows">
                    <path class="a1" d="M0 0 L30 32 L60 0"></path>
                    <path class="a2" d="M0 20 L30 52 L60 20"></path>
                    <path class="a3" d="M0 40 L30 72 L60 40"></path>
                </svg>
            </a>
            <div class="col-lg-8 align-self-baseline">
                <a href="#"><button class="btnlogin warninglogin" >تواصل معنا لتجديد الاشتراك</button></a>
            </div>
        </div>
    </div>
</header>
<!-- pricing-table-->
<section class="pricing py-5" id="pricing">
    <div class="container">
        <div class="row">
            <!-- Free Tier -->
            <div class="col-lg-4">
                <div class="card mb-5 mb-lg-0">
                    <div class="card-body">
                        <h5 class="card-title text-muted text-uppercase text-center">اساسي</h5>
                        <h6 class="card-price text-center">$0<span class="period">/شهر</span></h6>
                        <hr>
                        <ul class="fa-ul">
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>مستخدم واحد</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>5GB ذاكرة</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Unlimited Public Projects</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Community Access</li>
                            <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Unlimited Private Projects</li>
                            <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Dedicated Phone Support</li>
                            <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Free Subdomain</li>
                            <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Monthly Status Reports</li>
                        </ul>
                        <a href="login_test.php" class="btn btn-block btn-primary text-uppercase">أشترك الان</a>
                    </div>
                </div>
            </div>
            <!-- Plus Tier -->
            <div class="col-lg-4">
                <div class="card mb-5 mb-lg-0">
                    <div class="card-body">
                        <h5 class="card-title text-muted text-uppercase text-center">بلص</h5>
                        <h6 class="card-price text-center">$9<span class="period">/شهر</span></h6>
                        <hr>
                        <ul class="fa-ul">
                            <li><span class="fa-li"><i class="fas fa-check"></i></span><strong>5 مستخدمين</strong></li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>50GB ذاكرة</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Unlimited Public Projects</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Community Access</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Unlimited Private Projects</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Dedicated Phone Support</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Free Subdomain</li>
                            <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Monthly Status Reports</li>
                        </ul>
                        <a href="login_test.php" class="btn btn-block btn-primary text-uppercase">أشترك الان</a>
                    </div>
                </div>
            </div>
            <!-- Pro Tier -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-muted text-uppercase text-center">برو</h5>
                        <h6 class="card-price text-center">$49<span class="period">/شهر</span></h6>
                        <hr>
                        <ul class="fa-ul">
                            <li><span class="fa-li"><i class="fas fa-check"></i></span><strong>عدد مستخدمين لا محدود</strong></li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>150GB ذاكرة</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Unlimited Public Projects</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Community Access</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Unlimited Private Projects</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Dedicated Phone Support</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span><strong>Unlimited</strong> Free Subdomains</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Monthly Status Reports</li>
                        </ul>
                        <a href="login_test.php" class="btn btn-block btn-primary text-uppercase">أشترك الان</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact-->
<section class="page-section" id="contact" style="background: #f8f9fa;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="mt-0">توصل معنا</h2>
                <hr class="divider my-4" />
                <p class="text-muted mb-5">لي اي استفسار او سؤال علي المنظومه طريقة الاشتراك او استخدمها رسلنا عبر الهاتف او البريد الاكتروني</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 ml-auto text-center mb-5 mb-lg-0">
                <i class="fas fa-phone fa-3x mb-3 text-muted"></i>
                <div>+218 (92) 100000</div>
                <div>+218 (92) 200000</div>
                <div>+218 (92) 300000</div>
            </div>
            <div class="col-lg-4 mr-auto text-center">
                <i class="fas fa-envelope fa-3x mb-3 text-muted"></i>
                <!-- Make sure to change the email address in BOTH the anchor text and the link target below!-->
                <a class="d-block" href="mailto:contact@yourwebsite.com">contact@almaqar.com</a>
            </div>
        </div>
    </div>
</section>
<!-- Footer-->
<footer class="bg-light py-5">
    <div class="container"><div class="small text-center text-muted">Copyright © 2020 - Almaqr</div></div>
</footer>
<!-- Bootstrap core JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
<!-- Third party plugin JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
<!-- Core theme JS-->
<script src="<?php echo base_url();?>Asset/assets/js/scripts.js"></script>
</body>
</html>
