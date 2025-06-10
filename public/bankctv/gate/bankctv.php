
<?php
    $linkQR = "https://img.vietqr.io/image/".$bankid."-".$accNumber."-B5io27.jpg?amount=".$amount."&addInfo=".strtoupper($keywork)."".$order_id."&accountName=".$accName."";
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    
    <head id="j_idt6">
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="IE=edge" />
        <title>
            Thanh Toán - Bank
        </title>
        <meta name="description" content="Cổng thanh toán qua Ví điện tử MoMo" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="format-detection" content="telephone=no" />
        <meta name="robots" content="all,follow" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="pragma" content="no-cache" />
        <!-- Bootstrap CSS-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha512-Dop/vW3iOtayerlYAqCgkVr2aTr2ErwwTYOvRFUpzl2VhCMJyjQF0Q9TjUXIo6JhuM/3i0vVEt2e/7QQmnHQqw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Google fonts - Roboto -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,700"
        />
        <!-- theme stylesheet-->
        <link rel="stylesheet" href="css/style.default.css?version=<?php echo time();?>" id="theme-stylesheet" />
        <!-- Custom stylesheet - for your changes-->
        <link rel="stylesheet" href="css/style.css?version=<?php echo time();?>" />
        <link rel="stylesheet" href="css/qr-code.css?version=<?php echo time();?>" />
        <link rel="stylesheet" href="css/qr-code-tablet.css?version=<?php echo time();?>" />
      
        <!-- Font Awesome CDN-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
        />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css" integrity="sha512-hwwdtOTYkQwW2sedIsbuP1h0mWeJe/hFOfsvNKpRB3CkRxq8EW7QMheec1Sgd8prYxGm1OM9OZcGW7/GUud5Fw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style type="text/css">
		
			
            .container-fluid {
				width: 40 % !important;
				min-width: 750px!important;
			}
			.info-box {
				min-height: 600px;
			}
			#page{
				padding: 50px 0;
			}
			.image-qr-code{
				max-width: 300px;
			}
			.note-box{
				background-color: #ffde4f;
				padding: 25px 15px;
				max-width: 400px;
				margin: 20px auto;
			}
			.code{
				font-weight: 700;
				color: red;
				font-size: 22px;
			}
			.code span{
				color: #5d5b5b;
				cursor: pointer;
			}
			.note-title{
				font-weight: 700;
				font-size: 18px;
				color: #3f3f3f;
				padding-bottom: 5px;
			}
			.momo-buttom{
				margin-bottom: 20px;
			}
			@media (max-height: 750px){
				#page {
					padding: 0;
				}
				.provider{
					margin-bottom: 10px;
				}
			}
			.sweet-alert h2{
				padding-top: 12px;
				font-size: 19px;
				text-transform: uppercase;
				font-weight: 700;
				color: #5cb85c;
			}
			.provider{
				margin-top: 19px;
				font-size: 10px;
				color: #b9b9b9;
				font-style: italic;
			}
			.provider a{
				color: #b9b9b9
			}
        </style>
		
    </head>
    
    <body>
        
        <div id="page">
            
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-4 left hidden-xs">
                        <div class="info-box">
                            <div class="expiredAt" style="position: relative; padding-bottom: 20px; color: white">
                                <h3 style="color: #e8e8e8;">
                                    Đơn hàng hết hạn sau
                                </h3>
                                <span name="expiredAt" style="font-size: 1.7rem;">
                                </span>
                            </div>
							
                            <div class="entry">
                                <p>
                                    <i class="fa fa-money" aria-hidden="true" style="">
                                    </i>
                                    <span style="padding-left: 5px;">
                                        Số tiền
                                    </span>
                                    <br />
                                    <span style="padding-left: 25px;">
                                        <?php echo number_format($amount);?>đ
                                    </span>
                                </p>
                            </div>
                            <div class="entry">
                                <p>
                                    <i class="fa fa-credit-card" aria-hidden="true" style="">
                                    </i>
                                    <span style="padding-left: 5px;">
                                        Đơn hàng
                                    </span>
                                    <br />
                                    <span style="padding-left: 25px;word-break: keep-all;">
                                        <?php echo $trade_no;?>
                                    </span>
                                </p>
                            </div>
                            <div class="entry">
                                <p>
                                    <i class="fa fa-barcode" aria-hidden="true" style="">
                                    </i>
                                    <span style="padding-left: 5px;">
                                        Mã Đơn hàng
                                    </span>
                                    <br />
                                    <span style="padding-left: 25px;word-break: break-all;">
                                        <?php echo strtoupper($keywork);?><?php echo $order_id;?>
                                    </span>
                                </p>
                            </div>
                            <div class="entry" style="width: 100%;text-align: center;position: absolute;bottom: 10px; left: 0;">
                                <a href="<?php echo $return_url;?>"
                                style="color: #e8e8e8;font-weight: 200;">
                                <i class="fa fa-arrow-left" aria-hidden="true" style=""></i>
                                <span>Quay lại</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-8 right">
                        <div class="content">
                            
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="message" id="loginForm">
                                        
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="qr-code">
                                                    <img class="image-qr-code" src="<?php echo $linkQR;?>"
                                                    alt="paymentcode" />
                                                    <p class="hidden-md">
                                                        Đơn hàng hết hạn sau:
                                                        <b name="expiredAt" style="font-size: 1rem;">
                                                        </b>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="info-qr-code">
                                                    <p>
                                                        <img width="25" src="images/qr-code-1.png"
                                                        alt="" />
                                                        Sử dụng <strong> App ngân hàng của bạn</strong> chọn mục quét mã QR và quét mã phía trên.
                                                    </p>
													<p style="text-align: center;">Hoặc chuyển khoản với thông tin sau:</p>
													<div>SỐ TIỀN: <strong style="color: red;"><font style="font-size:15px"> <?php echo number_format($amount);?>đ</font></strong></div>
														<div>NGÂN HÀNG: <span style="color: blue"><?php echo $bankName;?></span></div>
														<div>SỐ TÀI KHOẢN: <span style="color: GREEN" class="copyclipboard" data-content="<?php echo $accNumber;?>"><?php echo $accNumber;?>
														<i class="fa fa-clipboard" aria-hidden="true"></i></span> </div>
														<div>CHỦ TÀI KHOẢN: <span style=""><?php echo $accName;?></span></div>
													<div class="note-box">
													    
														
														<div>NỘI DUNG CHUYỂN KHOẢN: <span class="code copyclipboard" data-content="<?php echo strtoupper($keywork);?><?php echo $order_id;?>"><?php echo strtoupper($keywork);?><?php echo $order_id;?> <i class="fa fa-clipboard" aria-hidden="true"></i></span>
														</div>
														
													</div>

                                                    <p id="status">
                                                        
                                                        Chuyển khoản xong bấm nút bên dưới...
                                                    </p>

                                                    <button type="button" id="confirmPaymentButton" class="confirm btn btn-lg btn-success" style="margin-bottom: 20px;">Xác nhận đã chuyển khoản</button>
                                                </div>
                                            </div>
                                        </div>
										
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="row info-box-ipad hidden-md">
                            <div class="col-xs-4 col-sm-4 col-md-4 info-box-border">
                                <div style="word-break: break-all;">
                                    <i class="fa fa-credit-card" aria-hidden="true" style="">
                                    </i>
                                    <h5 style="padding-left: 5px;">
                                        Đơn hàng
                                    </h5>
                                    <span style="word-break: break-all;">
                                        <?php echo $trade_no;?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 info-box-border">
                                <i class="fa fa-credit-card" aria-hidden="true" style="">
                                </i>
                                <h5 style="padding-left: 5px;">
                                    Số tiền
                                </h5>
                                <b>
                                   <?php echo number_format($amount);?>đ
                                </b>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 nowrap">
                                <div style="word-break: break-all;">
                                    <i class="fa fa-barcode" aria-hidden="true" style="">
                                    </i>
                                    <h5 style="padding-left: 5px;">Mã Đơn hàng</h5>
                                    <span style="white-space: pre-wrap;"><?php echo strtoupper($keywork);?><?php echo $order_id;?></span>
                                </div>
                            </div>
							
							
                            <div class="col-xs-12 back-merchant">
                                <a href="<?php echo $return_url;?>"
                                style="color: #e8e8e8;font-weight: 200;cursor: pointer;" id="cancelOrderT">
                                <i class="fa fa-arrow-left" aria-hidden="true" style=""></i>
                                <span>Quay lại</span></a>
                            </div>
							
							
                        </div>
                    </div>
                </div>
            </div>
            
            
        </div>
		
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js" integrity="sha512-MqEDqB7me8klOYxXXQlB4LaNf9V9S0+sG1i8LtPOYmHqICuEZ9ZLbyV3qIfADg2UJcLyCm4fawNiFvnYbcBJ1w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		
		<script>
            $(document).ready(function(){
                $("#confirmPaymentButton").click(function(){
                    $(this).html('Xác nhận đã chuyển khoản <i class="fa fa-spinner fa-spin"></i>');
                });
            });
			document.getElementById('confirmPaymentButton').addEventListener('click', function() {
            var noidung = "<?php echo strtoupper($keywork) . $order_id; ?>".toLowerCase();
            var token = "<?php echo $tokenbank; ?>"; 
            var bankid = "<?php echo $bankid; ?>"; 
            var trade_no = "<?php echo $trade_no; ?>"; 
                $.ajax({
                    url: './hookCTV.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: { 
                                noidung: noidung,
                                bankid: bankid,
                                tradeno: trade_no,
                                token: token
                            },
                    success: function(response) {
                        
                        var matchedComment = response.data.find(function(item) {
                            return item.comment === noidung;
                        });

                        if (matchedComment) {
                            switch (matchedComment.staff) {
                                case 1:
                                    swal({
                                        title: "Thành công!",
                                        text: "Xác nhận đã chuyển khoản thành công!",
                                        type: "success",
                                        showCancelButton: false,
                                        confirmButtonColor: "#DD6B55",
                                        confirmButtonText: "OK",
                                        closeOnConfirm: false
                                    }, function() {
                                        setTimeout(function() {
                                            window.location.href = "<?php echo $return_url;?>";
                                        }, 300);
                                    });
                                    
                                    break;
                                case 2:
                                    swal({
                                        title: "Chuyên Khoản thành công!",
                                        text: "Nhưng Lỗi đơn hàng, vui lòng liên hệ admin để kích hoạt.",
                                        type: "warning",
                                        cancelButtonText: "Đóng",
                                        confirmButtonText: "Trở Về Trang Trước",
                                        
                                        showCancelButton: true,
                                        closeOnConfirm: true
                                    }, function() {
                                        setTimeout(function() {
                                            window.location.href = "<?php echo $return_url;?>";
                                        }, 300);
                                    });
                                    break;
                                case 3:
                                    swal({
                                        title: "ToKen Không Đúng!",
                                        text: "Token sai, vui lòng liên hệ admin giải quyết",
                                        type: "warning",
                                        cancelButtonText: "Đóng",
                                        confirmButtonText: "Trở Về Trang Trước",
                                        
                                        showCancelButton: true,
                                        closeOnConfirm: true
                                    }, function() {
                                        setTimeout(function() {
                                            window.location.href = "<?php echo $return_url;?>";
                                        }, 300);
                                    });
                                    break;
                                case 0:
                                    swal({
                                        title: "Lỗi!",
                                        text: "Lỗi, không tìm thấy nội dung chuyển khoản. Vui lòng thử lại.",
                                        type: "error",
                                        confirmButtonText: "OK",
                                        closeOnConfirm: true
                                    });
                                    break;
                            }
                        } else {
                            swal({
                                title: "Lỗi!",
                                text: "Lỗi, không tìm thấy nội dung chuyển khoản. Vui lòng thử lại.",
                                type: "error",
                                confirmButtonText: "OK",
                                closeOnConfirm: true
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        swal({
                            title: "Lỗi!",
                            text: "Lỗi, không xác định. liên hệ admin để giải quyết.",
                            type: "error",
                            confirmButtonText: "OK",
                            closeOnConfirm: true
                        });
                    }
                });
            });


			
			$('.copyclipboard').click(function(){
				let content = $(this).data('content');
				copy(content);
				swal({
					type: "success",
					title: "Đã Copy",
					timer: 1000,
					showConfirmButton: false
				  });
			});
			
			
			
			
			
			function copy(text) {
				var input = document.createElement('input');
				input.setAttribute('value', text);
				document.body.appendChild(input);
				input.select();
				var result = document.execCommand('copy');
				document.body.removeChild(input);
				return result;
			 }
			
		</script>
    </body>

</html>