<?php error_reporting( E_ALL&~E_NOTICE );?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="MediaCenter, Template, eCommerce">
    <meta name="robots" content="all">

    <title>文文商城(wenwen.com)</title>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!-- Customizable CSS -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/red.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/owl.transitions.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">

    <!-- Demo Purpose Only. Should be removed in production -->
    <link rel="stylesheet" href="assets/css/config.css">

    <link href="assets/css/green.css" rel="alternate stylesheet" title="Green color">
    <link href="assets/css/blue.css" rel="alternate stylesheet" title="Blue color">
    <link href="assets/css/red.css" rel="alternate stylesheet" title="Red color">
    <link href="assets/css/orange.css" rel="alternate stylesheet" title="Orange color">
    <link href="assets/css/navy.css" rel="alternate stylesheet" title="Navy color">
    <link href="assets/css/dark-green.css" rel="alternate stylesheet" title="Darkgreen color">
    <!-- Demo Purpose Only. Should be removed in production : END -->

    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800' rel='stylesheet' type='text/css'>

    <!-- Icons/Glyphs -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- HTML5 elements and media queries Support for IE8 : HTML5 shim and Respond.js -->
    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->


</head>
<body>

<div class="wrapper">
    <nav class="top-bar animate-dropdown">
        <div class="container">
            <div class="col-xs-12 col-sm-6 no-margin">
                <ul>
                    <li><a href="<?php echo yii\helpers\Url::to(['index/index']) ?>">首页</a></li>
                    <?php if (\Yii::$app->session['isLogin'] == 1): ?>
                        <li><a href="<?php echo yii\helpers\Url::to(['cart/index']) ?>">我的购物车</a></li>
                        <li><a href="<?php echo yii\helpers\Url::to(['order/index']) ?>">我的订单</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="col-xs-12 col-sm-6 no-margin">
                <ul class="right">
                    <?php if(Yii::$app->session['isLogin'] ==1):?>
                        您好，欢迎回来<?php echo Yii::$app->session['loginname'];?>,
                        <a href="<?php echo \yii\helpers\Url::to(['member/logout']);?>">退出</a>
                    <?php else: ?>
                    <li><a href="<?php echo yii\helpers\Url::to(['member/auth']); ?>">注册</a></li>
                    <li><a href="<?php echo yii\helpers\Url::to(['member/auth']); ?>">登录</a></li>
                    <?php endif; ?>
                </ul>
            </div><!-- /.col -->
        </div><!-- /.container -->
    </nav><!-- /.top-bar -->
    <header>
        <div class="container no-padding">

            <div class="col-xs-12 col-sm-12 col-md-3 logo-holder">
                <div class="logo">
                    <a href="index.html">
                        <img alt="logo" src="assets/images/logo.jpg" width="233" height="54"/>
                    </a>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-6 top-search-holder no-margin">
                <div class="contact-row">
                    <div class="phone inline">
                        <i class="fa fa-phone"></i> (+086) 182 2501 6613
                    </div>
                    <div class="contact inline">
                        <i class="fa fa-envelope"></i> 1079517763@<span class="le-color">qq.com</span>
                    </div>
                </div>
                <div class="search-area">
                    <form>
                        <div class="control-group">
                            <input class="search-field" placeholder="搜索商品" />
                            <ul class="categories-filter animate-dropdown">
                                <li class="dropdown">
                                    <a class="dropdown-toggle"  data-toggle="dropdown" href="category-grid.html">所有分类</a>
                                    <ul class="dropdown-menu" role="menu" >
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="category-grid.html">laptops</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="category-grid.html">tv & audio</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="category-grid.html">gadgets</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="category-grid.html">cameras</a></li>

                                    </ul>
                                </li>
                            </ul>
                            <a class="search-button" href="#" ></a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-3 top-cart-row no-margin">
                <div class="top-cart-row-container">

                    <div class="top-cart-holder dropdown animate-dropdown">

                        <div class="basket">

                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <div class="basket-item-count">
                                    <span class="count"><?php echo count($this->params['cart']['products']) ?></span>
                                    <img src="assets/images/icon-cart.png" alt="" />
                                </div>

                                <div class="total-price-basket">
                                    <span class="lbl">您的购物车:</span>
                                    <span class="total-price">
                                    <span class="sign">￥</span><span class="value"><?php echo $this->params['cart']['total'] ?></span>
                                    </span>
                                </div>
                            </a>

                            <ul class="dropdown-menu">
                                <?php foreach((array)$this->params['cart']['products'] as $product): ?>
                                    <li>
                                        <div class="basket-item">
                                            <div class="row">
                                                <div class="col-xs-4 col-sm-4 no-margin text-center">
                                                    <div class="thumb">
                                                        <img alt="" src="<?php echo $product['cover'] ?>-picsmall" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-8 col-sm-8 no-margin">
                                                    <div class="title"><?php echo $product['title'] ?></div>
                                                    <div class="price">￥ <?php echo $product['price'] ?></div>
                                                </div>
                                            </div>
                                            <a class="close-btn" href="<?php echo yii\helpers\Url::to(['cart/del', 'cartid' => $product['cartid']]) ?>"></a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                                <li class="checkout">
                                    <div class="basket-item">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6">
                                                <a href="<?php echo yii\helpers\Url::to(['cart/index']) ?>" class="le-button inverse">查看购物车</a>
                                            </div>
                                            <div class="col-xs-12 col-sm-6">
                                                <a href="<?php echo yii\helpers\Url::to(['cart/index']) ?>" class="le-button">去往收银台</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <?php echo $content; ?>
    <footer id="footer" class="color-bg">

        <div class="container">
            <div class="row no-margin widgets-row">
                <div class="col-xs-12  col-sm-4 no-margin-left">
                    <div class="widget">
                        <h2>推荐商品</h2>
                        <div class="body">
                            <ul>
                                <?php foreach($this->params['tui'] as $pro): ?>
                                    <li>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-9 no-margin">
                                                <a href="<?php echo yii\helpers\Url::to(['product/detail', 'productid' => $pro->productid]); ?>"><?php echo $pro->title ?></a>
                                                <div class="price">
                                                    <div class="price-prev">￥<?php echo $pro->price ?></div>
                                                    <div class="price-current">￥<?php echo $pro->saleprice ?></div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-3 no-margin">
                                                <a href="<?php echo yii\helpers\Url::to(['product/detail', 'productid' => $pro->productid]) ?>" class="thumb-holder">
                                                    <img alt="<?php echo $pro->title ?>" src="<?php echo $pro->cover ?>-picsmall" data-echo="<?php echo $pro->cover ?>-picsmall" />
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div><!-- /.body -->
                    </div>
                </div><!-- /.col -->

                <div class="col-xs-12 col-sm-4 ">
                    <div class="widget">
                        <h2>热卖商品</h2>
                        <div class="body">
                            <ul>
                                <?php foreach($this->params['hot'] as $pro): ?>
                                    <li>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-9 no-margin">
                                                <a href="<?php echo yii\helpers\Url::to(['product/detail', 'productid' => $pro->productid]); ?>"><?php echo $pro->title ?></a>
                                                <div class="price">
                                                    <div class="price-prev">￥<?php echo $pro->price ?></div>
                                                    <div class="price-current">￥<?php echo $pro->saleprice ?></div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-3 no-margin">
                                                <a href="<?php echo yii\helpers\Url::to(['product/detail', 'productid' => $pro->productid]) ?>" class="thumb-holder">
                                                    <img alt="<?php echo $pro->title ?>" src="<?php echo $pro->cover ?>-picsmall" data-echo="<?php echo $pro->cover ?>-picsmall" />
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 ">
                    <div class="widget">
                        <h2>最新商品</h2>
                        <div class="body">
                            <ul>
                                <?php foreach($this->params['new'] as $pro): ?>
                                    <li>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-9 no-margin">
                                                <a href="<?php echo yii\helpers\Url::to(['product/detail', 'productid' => $pro->productid]); ?>"><?php echo $pro->title ?></a>
                                                <div class="price">
                                                    <div class="price-prev">￥<?php echo $pro->price ?></div>
                                                    <div class="price-current">￥<?php echo $pro->saleprice ?></div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-3 no-margin">
                                                <a href="<?php echo yii\helpers\Url::to(['product/detail', 'productid' => $pro->productid]) ?>" class="thumb-holder">
                                                    <img alt="<?php echo $pro->title ?>" src="<?php echo $pro->cover ?>-picsmall" data-echo="<?php echo $pro->cover ?>-picsmall" />
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sub-form-row">
        </div>

        <div class="link-list-row">
            <div class="container no-padding">
                <div class="col-xs-12 col-md-4 ">
                    <div class="contact-info">
                        <div class="footer-logo">
                            <img alt="logo" src="assets/images/logo.jpg" width="233" height="54"/>
                        </div>
                        <p class="regular-bold">请通过电话，电子邮件随时联系我们</p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 no-margin">
                        <div class="link-widget">
                            <div class="widget">
                                <h3>购物指南</h3>
                                <ul>
                                    <li><a href="javascript:void(0);">购物流程</a></li>
                                    <li><a href="javascript:void(0);">会员介绍</a></li>
                                    <li><a href="javascript:void(0);">常见问题</a></li>
                                    <li><a href="javascript:void(0);">联系客服</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="link-widget">
                            <div class="widget">
                                <h3>支付方式</h3>
                                <ul>
                                    <li><a href="javascript:void(0);">网银支付</a></li>
                                    <li><a href="javascript:void(0);">快捷支付</a></li>
                                    <li><a href="javascript:void(0);">任性付支付</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="link-widget">
                            <div class="widget">
                                <h3>物流配送</h3>
                                <ul>
                                    <li><a href="javascript:void(0);">免运费政策</a></li>
                                    <li><a href="javascript:void(0);">签收验货</a></li>
                                    <li><a href="javascript:void(0);">物流查询</a></li>
                                    <li><a href="javascript:void(0);">物流配送服务</a></li>
                                </ul>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <div class="copyright-bar">
            <div class="container">
                <div class="col-xs-12 col-sm-6 no-margin">
                    <div class="copyright">
                        &copy; <a href="index.html">wenwen.com</a> - all rights reserved
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 no-margin">
                    <div class="payment-methods ">
                        <ul>
                            <li><img alt="" src="assets/images/payments/payment-visa.png"></li>
                            <li><img alt="" src="assets/images/payments/payment-master.png"></li>
                            <li><img alt="" src="assets/images/payments/payment-paypal.png"></li>
                            <li><img alt="" src="assets/images/payments/payment-skrill.png"></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </footer>
</div>

<script src="assets/js/jquery-1.10.2.min.js"></script>
<script src="assets/js/jquery-migrate-1.2.1.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false&amp;language=en"></script>
<script src="assets/js/gmap3.min.js"></script>
<script src="assets/js/bootstrap-hover-dropdown.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/js/css_browser_selector.min.js"></script>
<script src="assets/js/echo.min.js"></script>
<script src="assets/js/jquery.easing-1.3.min.js"></script>
<script src="assets/js/bootstrap-slider.min.js"></script>
<script src="assets/js/jquery.raty.min.js"></script>
<script src="assets/js/jquery.prettyPhoto.min.js"></script>
<script src="assets/js/jquery.customSelect.min.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/scripts.js"></script>


<script src="switchstylesheet/switchstylesheet.js"></script>

<script>
    $(document).ready(function(){
        $(".changecolor").switchstylesheet( { seperator:"color"} );
        $('.show-theme-options').click(function(){
            $(this).parent().toggleClass('open');
            return false;
        });
    });

    $(window).bind("load", function() {
        $('.show-theme-options').delay(2000).trigger('click');
    });

    $("#createlink").click(function(){
        $(".billing-address").slideDown();
    });
    $(".minus").click(function(){
        var cartid = $("input[name=productnum]").attr('id');
        var num = parseInt($("input[name=productnum]").val()) - 1;
        if (parseInt($("input[name=productnum]").val()) <= 1) {
            var num = 1;
        }
        var total = parseFloat($(".value.pull-right span").html());
        var price = parseFloat($(".price span").html());
        changeNum(cartid, num);
        var p = total - price;
        if (p < 0) {
            var p = "0";
        }
        $(".value.pull-right span").html(p + "");
        $(".value.pull-right.ordertotal span").html(p + "");
    });
    $(".plus").click(function(){
        var cartid = $("input[name=productnum]").attr('id');
        var num = parseInt($("input[name=productnum]").val()) + 1;
        var total = parseFloat($(".value.pull-right span").html());
        var price = parseFloat($(".price span").html());
        changeNum(cartid, num);
        var p = total + price;
        $(".value.pull-right span").html(p + "");
        $(".value.pull-right.ordertotal span").html(p + "");
    });
    function changeNum(cartid, num)
    {
        $.get('<?php echo yii\helpers\Url::to(['cart/mod']) ?>', {'productnum':num, 'cartid':cartid}, function(data){
            location.reload();
        });
    }
    var total = parseFloat($("#total span").html());
    $(".le-radio.express").click(function(){
        var ototal = parseFloat($(this).attr('data')) + total;
        $("#ototal span").html(ototal);
    });
    $("input.address").click(function(){
        var addressid = $(this).val();
        $("input[name=addressid]").val(addressid);
    });
</script>

<script src="http://w.sharethis.com/button/buttons.js"></script>

</body>
</html>