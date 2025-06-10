vào thư mục /public/ và tạo 1 thư mục trong public xong update cái file api này lên
file Card.php chép nó vào /www/wwwroot/thư mục web/app/Payments


trong file card_send.php tìm $amount_card = $value * 85; 
85 là số % khách nhận được. tức là chiết khấu 15% đó. nếu chiết khấu 20% thì sửa thành 80

trong file gate/card.php tìm
var selectedAmount = $(this).val() * 0.85;

tương tự đang là 15%. nếu là 20% thì sửa thành $(this).val() * 0.8;
và có 1 đoạn html ở trên note là  sẽ chiết khấu bao nhiêu %.