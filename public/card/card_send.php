<?php

include('config.php');

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $telco = [
        'VIETTEL' => ['serial' => 14, 'code' => 15],
        'VINAPHONE' => ['serial' => 14, 'code' => 14],
        'MOBIFONE' => ['serial' => 15, 'code' => 12]
    ];

    if(array_key_exists($data['telco'], $telco)){
        $PartnerKey = CONFIG['GATE']['CARD']['PartnerKey'];
        $sign = md5($PartnerKey.$data['code'].$data['serial']);
        $request_id = time() . mt_rand(10000, 99999);
        $order_id = $data['order_id'];
        
        $ch = curl_init("https://gachthenhanh.net/chargingws/v2");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'telco' => $data['telco'],
            'code' => $data['code'],
            'serial' => $data['serial'],
            'amount' => $data['amount'],
            'request_id' => $request_id,
            'partner_id' => CONFIG['GATE']['CARD']['PartnerID'],
            'sign' => $sign,
            'command' => 'charging'
        ]));
        $response = curl_exec($ch);
        curl_close($ch);

        if($response){
            $result = json_decode($response,true);
            if(is_array($result) && isset($result['status'])){
                if($result['status'] == 99){
                    $dir = 'ttt/' . $order_id;
                    if (!is_dir($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    

                    file_put_contents($dir . '/telco.log', $data['telco']);
                    file_put_contents($dir . '/code.log', $data['code']);
                    file_put_contents($dir . '/serial.log', $data['serial']);
                    file_put_contents($dir . '/amount.log', $data['amount']);
                    file_put_contents($dir . '/request_id.log', $request_id);
                    file_put_contents($dir . '/status.log', '0');
                    file_put_contents($dir . '/amount_real.log', '0');
                    
                    $cardinfo = md5($data['telco'].$data['serial'].$data['code']);
                    $dirlog = 'logs/' . $cardinfo;
                    if (!is_dir($dirlog)) {
                        mkdir($dirlog, 0777, true);
                    }
                    file_put_contents($dirlog . '/status.log', '0');
                    file_put_contents($dirlog . '/order_id.log', $data['order_id']);
                }

                echo json_encode(['status' => $result['status'], 'message' => $result['message']]);
            }
        }
    } else {
        echo json_encode(['status' => '0', 'message' => 'Loại thẻ không hợp lệ!']);
    }
}
?>