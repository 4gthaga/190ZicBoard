<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán bằng thẻ cào</title>
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container" >
        <div class="order-info1" id="order-info1">
            <h4 class="text-center">Thông tin đơn hàng</h4>
            <p><strong>Đơn Hàng:</strong> <?php echo $order_id; ?></p>
            <p><strong>Mã đơn hàng:</strong> <?php echo $trade_no; ?></p>
            <p><strong>Số Tiền:</strong> <?php echo number_format($amount, 0, ',', '.') . '₫'; ?></p>
            <!--<p><strong>Số Dư:</strong> <?php echo $balance; ?></p>-->
        </div>
        <form id="cardForm">
            <h4 class="text-center">Thanh Toán bằng thẻ cào</h4>
            <div class="alert alert-info"><strong>Chiếu khấu:</strong> 15% giá trị
            <br> <strong>Ví dụ:</strong> Nạp 100k sẽ nhận 85k để thanh toán đơn hàng, số còn lại sẽ cộng vào số dư của bạn.
            <br> <strong>Chú ý:</strong> Nếu chọn sai mệnh giá sẽ không thanh toán đơn hàng mà bị phạt 50% số tiền sẽ cộng vào số dư.
            </div>
            <label for="networkProvider">Nhà mạng:</label>
            <select id="networkProvider" name="networkProvider" required>
                <option value="VIETTEL">Viettel</option>
                <option value="MOBIFONE">Mobifone</option>
                <option value="VINAPHONE">Vinaphone</option>
            </select>

            <label for="amount">Mệnh giá:</label>
            <select id="amount" name="amount" required>
                <option value="" selected disabled>Chọn mệnh giá</option>
                <option value="10000">10,000 VND</option>
                <option value="20000">20,000 VND</option>
                <option value="30000">30,000 VND</option>
                <option value="50000">50,000 VND</option>
                <option value="100000">100,000 VND</option>
                <option value="200000">200,000 VND</option>
                <option value="500000">500,000 VND</option>
            </select>

            <label for="serial">Số seri:</label>
            <input type="text" id="serial" name="serial" placeholder="Nhập serial" required>

            <label for="cardCode">Mã thẻ cào:</label>
            <input type="text" id="cardCode" name="cardCode" placeholder="Nhập mã thẻ" required>

            <input type="submit" value="NẠP THẺ">
            <p id="status">
            <i class="fa fa-spinner fa-spin">
            </i>
            Đang chờ thanh toán ...
        </p>
        <div class="col-xs-12 back-merchant">
                                <a href="<?php echo $return_url;?>"
                                style="color: #0000FF;font-weight: 200;cursor: pointer;" id="cancelOrderT">
                                <i class="fa fa-arrow-left" aria-hidden="true" style=""></i>
                                <span>Quay lại</span></a>
        </div>
        </form>
        
    </div>

    <script>
       $(document).ready(function() {
            $('#cardForm').submit(function(event) {
                event.preventDefault(); // Prevent form submission by default
        
                var selectedAmount = $('#amount').val() * 0.85;
                var requiredAmount = <?php echo $amount; ?>;
                let order_id = "card" + "<?php echo $order_id;?>";
                let formData = {
                    telco: $('#networkProvider').val(),
                    amount: $('#amount').val(),
                    serial: $('#serial').val(),
                    code: $('#cardCode').val(),
                    order_id: order_id,
                };
        
                // Function to execute the fetch API call
                function submitCardForm() {
                    fetch('./card_send.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.status == 99){
                            swal("Success", "Thẻ đã gửi thành công và đang xử lý", "success");
                            $("#status").html('Đang xử lý. vui lòng không tắt trình duyệt');
                        } else {
                            swal("Error", data.message, "error");
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        swal("Error", "Something went wrong!", "error");
                    });
                }
        
                // Check if the selected amount is less than the required amount
                if (selectedAmount < requiredAmount) {
                    swal({
                        title: "Xác nhận",
                        text: "Thẻ này không đủ để thanh toán đơn hàng, nếu vẫn nạp tiền sẽ được cộng vào số dư. Bạn có chắc chắn muốn tiếp tục?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willTopUp) => {
                        if (willTopUp) {
                            submitCardForm(); // Proceed with form submission if user confirms
                        } else {
                            swal("Hủy nạp thẻ.");
                        }
                    });
                } else {
                    submitCardForm(); // Proceed with form submission if amount is sufficient
                }
            });
        });
        
    </script>
    
    <script>
        var order_id = "card" +"<?php echo $order_id;?>";
        var loopCheck;
        setInterval(function(){ check() }, 5000);
        function check(){
            $.ajax({
                url: './croncard.php',
                type: 'POST',
                dataType: 'JSON',
                data: {order_id: order_id},
                success : function (res){
                    var status = Number(res.status); // Convert res.status to a number
                    if(status === 1){
                        clearInterval(loopCheck);
                        $("#status").html('<div class="alert alert-success">Thanh toán thành công! Đang chuyển về trang mua hàng.</div>');
                        setTimeout(function(){ window.location.href = "<?php echo $return_url;?>"; }, 4000);
                    }
                    if(status === 2){
                        clearInterval(loopCheck);
                        $("#status").html('<div class="alert alert-warning">Nạp thẻ thành công nhưng chọn sai mệnh giá. sẽ không thanh toán đơn hàng mà bị phạt 50%. số tiền đã được cộng vào số dư của bạn. liên hệ admin nếu vẫn thắc mắc </div>');
                    }
                    if(status === 3){
                    clearInterval(loopCheck);
                    $("#status").html('<div class="alert alert-danger">Thẻ lỗi, liên hệ admin để được hỗ trợ</div>');
                    }
                    if(status === 4){
                        clearInterval(loopCheck);
                        $("#status").html('<div class="alert alert-info">Hệ thống đang bảo trì</div>');
                    }
                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#amount').change(function() {
                var selectedAmount = $(this).val() * 0.85;
                var requiredAmount = <?php echo $amount; ?>;

                if (selectedAmount < requiredAmount) {
                    $('#amount-warning').remove();
                    $(this).after('<span id="amount-warning" style="color: red;">Thẻ này không đủ để thanh toán đơn hàng, nếu vẫn nạp tiền sẽ được cộng vào số dư.</span>');
                } else {
                    $('#amount-warning').remove();
                }
            });
        });
        
        
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
