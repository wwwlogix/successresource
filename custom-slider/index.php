<!DOCTYPE html>
<html>
    <head>
        <title>Simple styled maps</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
        <link href="lib/jquery.bxslider.css" rel="stylesheet" />
        <script src="lib/jquery.bxslider.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <style>
            html, body {
                padding: 0px;
                margin: 0 auto;
            }
            .container{
                width:1170;
                margin: 0 auto;
            }
            .image-holder img{
                border-radius: 50%;
                max-width: 120px;
                margin-bottom: 10px;
            }
            #map-canvas{
                width:100%;
                height: 400px;
            }
            #carousel-slider .bx-wrapper{
                margin-left: 61px;
                height: inherit;
                background-image: url("images/box-bg.png")!important;
                background-repeat: repeat-x;
            }
            .slide{

                background-image: url("images/box-bg.png")!important;
                background-repeat: repeat-x;
            }
            #carousel-slider {
                width: 100%;
                height: 221px;
            }
            .slide-items{
                margin-top: 66px;
            }
            .image-holder{
                max-width: 300px;
                margin: 0 auto;
                text-align: center;
            }
            .image-holder .name-text{
                color: #616264;
                text-transform: uppercase;
                font-size: 12px;
                padding: 9px 7px;
            }
            .image-holder .separator{
                height: 50px;
                border-right: 2px dotted #C1C1C1;
                float: right;
                margin-top: 30px;
            }
            #active-pin{
                margin-left: 37%;
                z-index: 10000;
                margin-top: 30px;
                color: #00aeef;
                position: absolute;
                text-transform: uppercase;
                font-size: 12px;
            }
            .border-right{
                background: rgba(0, 0, 0, 0) url("images/border.png") no-repeat scroll right 15px;
                background-repeat: no-repeat;
            }
            #carousel-slides{
                height: 218px;
            }
            #carousel-slides .bx-viewport{
                height: 218px !important;
            }
            .bx-wrapper .bx-next {
                right: 0px;
                background: none;
                background-image: url("images/box-right.png") !important;
                width: 67px !important;
            }
            .bx-wrapper .bx-prev {
                left: 0px;
                background: none;
                background-image: url("images/box-left.png") !important;
                width: 67px !important;
            }
            .bx-wrapper{
                padding-left: 67px;
                padding-right: 52px;
                max-width: 1165px !important;
            }
            .bx-wrapper .bx-controls-direction a {
                position: absolute;
                top: 9%;
                margin-top: -20px;
                outline: 0;
                width: 32px;
                height: 218px;
                text-indent: -9999px;
                z-index: 9999;
                /* background-image: url("images/box-right.png") !important; */
            }
            .bx-wrapper .bx-viewport {
                -moz-box-shadow: none;
                -webkit-box-shadow: none;
                box-shadow: none;
                border: none;
            }
            .bx-wrapper .bx-pager {
                display: none;
            }
            .map-container{
                background-image: url("images/world-map.png");
                min-width: 1169px;
                height: 470px;
            }
            .add-pin{
                font-size: 0px;
                color: transparent;
                text-decoration: none;
                background: url("images/gray-pin.png") 0% 0% no-repeat;
                margin: 0px 0px 0px;
                padding: 0px;
                line-height: 21px;
                width: 14px;
                height: 21px;
                display: inline-block;
                position: relative;
            }
            .inner-pin{
                min-width: 14px;
                min-height: 21px;
            }
            .pin-australia{
                top: 344px;
                left: 847px;
            }
            .pin-Malaysia{
                top: 255px;
                left: 754px;
            }
            .pin-Indonesia{
                top: 293px;
                left: 761px;

            }
            .pin-Singapore{
                top: 271px;
                left: 727px;
            }
            .pin-India{
                top: 229px;
                left: 652px;
            }
            .pin-Taiwan{
                top: 215px;
                left: 729px;
            }
            .pin-Brunei{
                top: 265px;
                left: 696px;
            }
            .pin-China{
                top: 197px;
                left: 667px;
            }
            .pin-Thailand{
                top: 228px;
                left: 631px;
            }
            .pin-South_Africa{
                top: 348px;
                left: 447px;
            }
            .pin-Spain{
                top: 171px;
                left: 364px;
            }
            .pin-Germany{
                top: 149px;
                left: 379px;
            }
            .pin-Italy{
                top: 168px;
                left: 370px;
            }
            .pin-Netherlands{
                top: 133px;
                left: 335px;
            }
            .pin-UK{
                top: 127px;
                left: 298px;
            }
            .pin-Poland{
                top: 136px;
                left: 327px;
            }
            .pin-Norway{
                top: 102px;
                left: 283px;
            }
            .pin-USA{
                top: 174px;
                left: 28px;
            }
            .pin-Canada{
                top: 115px;
                left: -17px;
            }
            .active{
                background: url("images/blue-pin.png") 0% 0% no-repeat;
                outline: none !important;
            }
            .add-pin:focus{
                outline: none;
            }
        </style>

        <script>
            $(document).ready(function() {
                go_slider = null;
                go_slider = $('.carousel-slider').bxSlider({
                    slideWidth: 160,
                    minSlides: 2,
                    maxSlides: 6,
                    moveSlides: 1,
                    slideMargin: 10,
                    adaptiveHeight: true,
                });
                $(".add-pin").click(function() {
                    var current_pin = this;
                    var title_active_pin = this.title;
                    $.ajax({url: "ajax.php",
                        data: {location: this.title},
                        success: function(result) {
                            if (go_slider) {
                                go_slider.destroySlider();
                            }
                            $(".add-pin").removeClass("active");
                            $(current_pin).addClass("active");
                            $('#carousel-slides').html(result);
                            go_slider = $('.carousel-slider').bxSlider({
                                slideWidth: 160,
                                minSlides: 2,
                                maxSlides: 6,
                                moveSlides: 1,
                                adaptiveHeight: true,
                                slideMargin: 10
                            });
                            $("#active-pin").html("<img src='images/blue-pin.png'> " + title_active_pin);
                        }});
                    return false;
                });
            });
        </script>
    </head>
    <body>
        <div class="container">
            <!--<div id="map-canvas"></div>-->
            <div class="map-container">
                <a class="add-pin pin-australia active" title="Australia"  href="#"></a>
                <a class="add-pin pin-Malaysia " title="Malaysia"  href="#"></a>
                <a class="add-pin pin-Indonesia" title="Indonesia"  href="#"></a>
                <a class="add-pin pin-Singapore" title="Singapore"  href="#"></a>
                <a class="add-pin pin-India" title="India"  href="#"></a>
                <a class="add-pin pin-Taiwan" title="Taiwan"  href="#"></a>
                <a class="add-pin pin-Brunei" title="Brunei"  href="#"></a>
                <a class="add-pin pin-China" title="China"  href="#"></a>
                <a class="add-pin pin-Thailand" title="Thailand"  href="#"></a>
                <a class="add-pin pin-South_Africa" title="South Africa"  href="#"></a>
                <a class="add-pin pin-Spain" title="Spain"  href="#"></a>
                <a class="add-pin pin-Germany" title="Germany"  href="#"></a>
                <a class="add-pin pin-Italy" title="Italy"  href="#"></a>
                <a class="add-pin pin-Netherlands" title="Netherlands"  href="#"></a>
                <a class="add-pin pin-UK" title="UK"  href="#"></a>
                <a class="add-pin pin-Poland" title="Poland"  href="#"></a>
                <a class="add-pin pin-Norway" title="Norway"  href="#"></a>
                <a class="add-pin pin-USA" title="USA"  href="#"></a>
                <a class="add-pin pin-Canada" title="Canada"  href="#"></a>
            </div>
            <div id="active-pin"><img src="images/blue-pin.png"> Australia</div>
            <div id="carousel-slides">
                <div class="carousel-slider">
                    <div class="slide"><div class="row slide-items">
                            <div class="col-md-12 border-right">
                                <div class="image-holder">
                                    <div class="imge"><img src="images/aus.jpg" alt="..."></div>
                                    <div class="name-text">Lorem Ipsum Country Name</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide"><div class="row slide-items">
                            <div class="col-md-12 border-right">
                                <div class="image-holder">
                                    <div class="imge"><img src="images/aus.jpg" alt="..."></div>
                                    <div class="name-text">Lorem Ipsum Country Name</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide"><div class="row slide-items">
                            <div class="col-md-12 border-right">
                                <div class="image-holder">
                                    <div class="imge"><img src="images/aus.jpg" alt="..."></div>
                                    <div class="name-text">Lorem Ipsum Country Name</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide"><div class="row slide-items">
                            <div class="col-md-12 border-right">
                                <div class="image-holder">
                                    <div class="imge"><img src="images/aus.jpg" alt="..."></div>
                                    <div class="name-text">Lorem Ipsum Country Name</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide"><div class="row slide-items">
                            <div class="col-md-12 border-right">
                                <div class="image-holder">
                                    <div class="imge"><img src="images/aus.jpg" alt="..."></div>
                                    <div class="name-text">Lorem Ipsum Country Name</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide"><div class="row slide-items">
                            <div class="col-md-12 border-right">
                                <div class="image-holder">
                                    <div class="imge"><img src="images/aus.jpg" alt="..."></div>
                                    <div class="name-text">Lorem Ipsum Country Name</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide"><div class="row slide-items">
                            <div class="col-md-12 border-right">
                                <div class="image-holder">
                                    <div class="imge"><img src="images/aus.jpg" alt="..."></div>
                                    <div class="name-text">Lorem Ipsum Country Name</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            </div>
        </div>
       
    </body>
</html>