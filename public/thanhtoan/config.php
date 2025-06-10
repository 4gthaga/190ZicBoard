<?php
const CONFIG = [
    'DOMAINV2B' => "dailysieure.vn", //Tên miền web của bạn
    'TOKEN' => "", // Token lấy lại https://api.vpnfast.vn  
    'KEYWORD' => "", // Nội dung chuyển khoảng KEYWORD (điền chữ thường không viết hoa, vì hệ thống sẽ tự hiển thị chữ hoa)
    'DATABASE' => [ // vào file .env của src web để xem thông tin TK, MK DB
        'HOST' => "localhost",
        'DBNAME' => "" , // DB_DATABASE trong file.env
        'USERNAME' => "", // DB_USERNAME trong file .env
        'PASSWORD' => "", // DB_PASSWORD trong file .env
    ],
    'GATE' => [
        'CARD_DVS' => [
            'hookCard_DVS' => "xxx", //link hookcard mà dvsteam đã setup trước đó
            'WEBHOOK' => "xxx", // link (Địa Chỉ Thông Báo) trong web admin mục (Cấu Hình Thanh Toán)
            'KEY_CARD' => "Wlxxx", // bảo trì
            'CHIEUKHAU' => "2", // Chiếu Khấu Thẻ
            'CHAT_ID' => "5540480097" // lấy ID telegram tại https://t.me/getmyid_bot
            // Bấm Start bot https://t.me/apivpnfast_bot
        ]
    ],    
    'DVS_CHECKV2B' => '/www/wwwroot/vpnfast.vn/public/thanhtoan/ttt/',
];
