<!DOCTYPE html>
<html lang="en">
<head>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <!-- site metas -->
    <title>HP Store</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" href="/template/images/icons/favicon.png"/>
    <!-- bootstrap css -->
    <link rel="stylesheet" href="/honey-html/css/bootstrap.min.css">
    <!-- style css -->
    <link rel="stylesheet" href="/honey-html/css/style.css">
    <!-- Responsive-->
    <link rel="stylesheet" href="/honey-html/css/responsive.css">
    <!-- Tweaks for older IEs-->
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
</head>
<!-- body -->
<body class="main-layout">
<!-- loader  -->
<div class="loader_bg">
    <div class="loader"><img src="/honey-html/images/success.gif" alt="#"/></div>
</div>
<!-- end loader -->
<!-- header -->
<header>
    <div class="header_full_banne">
        <!-- end header inner -->
        <!-- end header -->
        <!-- top -->

        <div class="full_bg">
            <div class="slider_main">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">

                            <!-- carousel code -->
                            <div id="carouselExampleIndicators" class="carousel slide">
                                <ol class="carousel-indicators">
                                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                                </ol>
                                <div class="carousel-inner">
                                    <!-- first slide -->
                                    <div class="carousel-item active">
                                        <div class="carousel-caption relative">
                                            <div class="row d_flex">
                                                <div  class="col-md-7">
                                                    <div class="board">
                                                        <h3>
                                                            {{ $blockUser->ten_sp }}
                                                        </h3>
                                                        <p>{{ $blockUser->mota_sp }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="banner_img">
                                                        <figure><img class="img_responsive" src="{{ $blockUser->thumb_sp }}"></figure>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- second slide -->
                                    <div class="carousel-item">
                                        <div class="carousel-caption relative">
                                            <div class="row d_flex">
                                                <div  class="col-md-7">
                                                    <div class="board">
                                                        <h3>
                                                            {{ $blockUser->ten_sp }}
                                                        </h3>
                                                        <p>{{ $blockUser->mota_sp }}</p>
                                                        <a class="read_more" href="contact.html">Contact  </a>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="banner_img">
                                                        <figure><img class="img_responsive" src="{{ $blockUser->thumb_sp }}"></figure>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- third slide-->
                                    <div class="carousel-item">
                                        <div class="carousel-caption relative">
                                            <div class="row d_flex">
                                                <div  class="col-md-7">
                                                    <div class="board">
                                                        <h3>
                                                            {{ $blockUser->ten_sp }}
                                                        </h3>
                                                        <p>{{ $blockUser->mota_sp }}</p>
                                                        <a class="read_more" href="contact.html">Contact  </a>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="banner_img">
                                                        <figure><img class="img_responsive" src="{{ $blockUser->thumb_sp }}"></figure>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- controls -->
                                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                    <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- end banner -->
<!-- about -->
<div class="about" style="background-image: url("{{ $blockUser->thumb_sp }}")">
<div class="container">
    <div class="row d_flex">
        <div class="col-md-7">
            <div class="titlepage text_align_left">
                <h2>ĐẲNG CẤP - SANG TRỌNG</h2>
                <p>{{ $blockUser->chitiet_sp }}
                </p>
            </div>
        </div>
        <div class="col-md-5">
            <div class="about_img text_align_center">
                <figure><img class="img_responsive" src="{{ $blockUser->thumb_sp }}" alt="#"/></figure>
            </div>
        </div>
    </div>
</div>
</div>
<!-- end about -->
<!-- quality -->



<div class="quality">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="titlepage text_align_center">
                    <h2>Chuỗi Siêu Thị Kinh Doanh Sản Phẩm Này</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            $arrs = explode(",",$billreceiveds[0]->list_saleroom);
            ?>
            @for($i=0;$i<sizeof($arrs);$i++)
                @foreach($salerooms as $key => $saleroom)
                    @if($saleroom->id == (int)$arrs[$i])

                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 text_align_center">
                            <h3 style="font-weight: bold; color: yellow">{{ $saleroom->tencoso }}</h3>
                            <br>
                            <div class="quality-box ">
                                <figure><img width="200px" src="{{ $saleroom->thumb }}" alt="#"/></figure>
                            </div>
                            <br>
                            <h4>{{ $saleroom->diachi }}</h4>
                        </div>
                    @endif
                @endforeach
            @endfor
        </div>
    </div>

</div>



<div class="quality">
    <div class="about">
        <div class="container">
            <div class="row d_flex">
                <div class="col-md-6">
                    <div class="titlepage text_align_left">
                        <h2>NHÀ CUNG CẤP CHẤT LƯỢNG</h2>
                        <p>{{ $blockUser->mota_ncc }}
                        </p>
                        <a type="button" class="read_more" data-toggle="modal" data-target=".bd-example-modal-lg" href="about.html">Read More</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="about_img">
                        <figure><img class="img_responsive" src="{{ $blockUser->thumb_ncc }}" alt="#"/></figure>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div style="color: black" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 style="color: #f87c7c">HAHA</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <table class="table table-image">
                        <thead>
                        <tr>
                            <th colspan="3" scope="col">Thông tin</th><img src="{{ $blockUser->thumb_ncc }}" class="img-fluid img-thumbnail" alt="Sheep">
                        </tr>
                        <tr>
                            <th scope="col">Mã Doanh Nghiệp</th>
                            <th scope="col">Tên Nhà Cung Cấp</th>
                            <th scope="col">Số Điện Thoại</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td >{{ $blockUser->madoanhnghiep }}</td>
                            <td >{{ $blockUser->tenncc }}</td>
                            <td >{{ $blockUser->sodienthoai_ncc }}</td>
                        </tr>
                        <tr>
                            <th colspan="3" scope="col">Địa chỉ</th>
                        </tr>
                        <tr>
                            <td colspan="3">{{ $blockUser->diachi_ncc }}</td>
                        </tr>
                        <tr>
                            <th colspan="3" scope="col">Mô Tả</th>
                        </tr>
                        <tr>
                            <td colspan="3" >{{ $blockUser->mota_ncc }}</td>
                        </tr>
                        {{--                        <tr>--}}
                        {{--                            <th scope="row">2</th>--}}
                        {{--                            <td class="w-25">--}}
                        {{--                                <img src="https://s3.eu-central-1.amazonaws.com/bootstrapbaymisc/blog/24_days_bootstrap/sheep-5.jpg" class="img-fluid img-thumbnail" alt="Sheep">--}}
                        {{--                            </td>--}}
                        {{--                            <td>Bootstrap Grid 4 Tutorial and Examples</td>--}}
                        {{--                            <td>Cristina</td>--}}
                        {{--                            <td>1.434</td>--}}
                        {{--                            <td>3.417</td>--}}
                        {{--                        </tr>--}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<footer>
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="infoma text_align_left">
                        <h3>About</h3>
                        <p class="ipsum">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sedconsectetur </p>
                        <ul class="social_icon">
                            <li><a href="Javascript:void(0)"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                            <li><a href="Javascript:void(0)"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <li><a href="Javascript:void(0)"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a></li>
                            <li><a href="Javascript:void(0)"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="infoma">
                        <h3>Adderess</h3>
                        <ul class="conta">
                            <li>Healing Center, oo W Street name, <br>
                                Loram ipusum
                            </li>
                            <li>(+71) 8522369417 <br>(+71) 8522369417</li>
                            <li> <a href="Javascript:void(0)"> demo@gmail.com</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 pad_lrft col-sm-6">
                    <div class="infoma">
                        <h3>Links</h3>
                        <ul class="fullink">
                            <li><a href="index.html">Home</a></li>
                            <li><a href="about.html">About</a></li>
                            <li><a href="shop.html">Shop</a></li>
                            <li><a href="quality.html">Quality</a></li>
                            <li><a href="contact.html">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="infoma">
                        <h3>Newsletter</h3>
                        <form class="form_subscri">
                            <div class="row">
                                <div class="col-md-12">
                                    <input class="newsl" placeholder="Your Name" type="text" name="Your Name">
                                </div>
                                <div class="col-md-12">
                                    <input class="newsl" placeholder="Email" type="text" name="Email">
                                </div>
                                <div class="col-md-12">
                                    <button class="subsci_btn">subscribe</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <p>© 2020 All Rights Reserved.  <a href="https://html.design/"> Free html Templates</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- end footer -->
<!-- Javascript files-->
<script src="/honey-html/js/jquery.min.js"></script>
<script src="/honey-html/js/bootstrap.bundle.min.js"></script>
<script src="/honey-html/js/jquery-3.0.0.min.js"></script>
<!-- sidebar -->
<script src="/honey-html/js/custom.js"></script>
</body>
</html>




