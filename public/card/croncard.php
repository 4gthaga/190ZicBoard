<?php
error_reporting(E_ALL); 
require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__.'/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Order;
use App\Services\TelegramService;
use App\Models\User;
use App\Utils\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


include('config.php');

$order_id = $_POST['order_id'];

if ($order_id) {
    $dir = 'ttt/' . $order_id;
    if (is_dir($dir)) {
        $telco = file_get_contents($dir . '/telco.log');
        $code = file_get_contents($dir . '/code.log');
        $serial = file_get_contents($dir . '/serial.log');
        $amount = file_get_contents($dir . '/amount.log');
        $request_id = file_get_contents($dir . '/request_id.log');
        $statusLogContent = file_get_contents($dir . '/status.log');
        if (trim($statusLogContent) == '1' || trim($statusLogContent) == '2') {
            echo json_encode(['status' => 1, 'message' => 'Đơn hàng đã được xử lý trước đó.']);
            exit;
        }

        // $cardinfo = md5($telco.$serial.$code);
        // $status_file = 'logs/' . $cardinfo . '/status.log';
        // if (file_exists($status_file) && file_get_contents($status_file) != '0') {
        //     echo json_encode(['status' => 111, 'message' => 'Thẻ đã được nạp rồi']);
        //     exit;
        // }

        $id_order = substr($order_id, 4);
        $order_real = Order::where('id', $id_order)->first();
        $order_amount = $order_real->total_amount;
        $user = $order_real->user_id;

        $chatid = CONFIG['IDTELE'];
        $tokenbot = CONFIG['TOKENBOT'];

        if ($telco && $code && $serial && $amount && $request_id) {
            $telcoInfo = [
                'VIETTEL' => ['serial' => 14, 'code' => 15],
                'VINAPHONE' => ['serial' => 14, 'code' => 14],
                'MOBIFONE' => ['serial' => 15, 'code' => 12]
            ];

            if(array_key_exists($telco, $telcoInfo)){
                $telcoInfo = $telcoInfo[$telco];
                if(strlen((string)$serial) == $telcoInfo['serial']){
                    
                    $PartnerKey = CONFIG['GATE']['CARD']['PartnerKey'];
                    $sign = md5($PartnerKey.$code.$serial);

                    $ch = curl_init("https://gachthenhanh.net/chargingws/v2");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                        'telco' => $telco,
                        'code' => $code,
                        'serial' => $serial,
                        'amount' => $amount,
                        'request_id' => $request_id,
                        'partner_id' => CONFIG['GATE']['CARD']['PartnerID'],
                        'sign' => $sign,
                        'command' => 'check'
                    ]));
                    $response = curl_exec($ch);
                    curl_close($ch);

                    if ($response) {
                        $result = json_decode($response, true);
                        $value = $result['value'];
                        $amount_card = $value * 85;

                        if (is_array($result) && isset($result['status'])) {
                            echo json_encode(['status' => $result['status'], 'message' => $result['message']]);
                            
                            if ($result['status'] == 1) {
                                if (trim($statusLogContent) == '0' && ($order_real->status == 0) && $amount_card > $order_amount) {
                                    DB::beginTransaction();
                                    try {
                                        $dataPost = array(
                                            "token" => CONFIG['TOKEN'],
                                            "trade_no" => $order_real->trade_no,
                                            "out_trade_no" => "cron card",
                                        );
                    
                                        $ch1 = curl_init(CONFIG['GATE']['CARD']['WEBHOOK']);
                                        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
                                        curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);
                                        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
                                        curl_setopt($ch1, CURLOPT_TIMEOUT, 30);
                                        curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, 'POST');
                                        curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query($dataPost));
                                        $output = curl_exec($ch1);
                                        curl_close($ch1);

                                        $user_balance = User::find($user);
                                        $user_balance->balance += ($amount_card - $order_amount);
                                        $user_balance->save();
                                        
                                        $message = sprintf(
                                            "💰 Nạp thành công thẻ: %s \n———————————————\nMệnh giá: %s đ\n———————————————\nSeri：%s\n———————————————\nMã thẻ：%s\n———————————————\nEmail khách hàng: %s\n",
                                            $telco,
                                            $value,
                                            $serial,
                                            $code,
                                            $user_balance->email
                                        );
                                        Helper::sendNotification($chatid,$tokenbot,$message);
                                        file_put_contents($dir . '/status.log', 1);
                                        // file_put_contents($status_file, '1');

                                        DB::commit();
                                    } catch (\Exception $e) {
                                        DB::rollback();
                                    }
                                } elseif (($order_real->status == 0) && $amount_card < $order_amount) {
                                    
                                
                                    if ($statusLogContent == "105") {
                                        echo json_encode(['status' => 105, 'message' => 'Đơn hàng đã được xử lý trước đó.']);
                                    } else {
                                        DB::beginTransaction();
                                        try {
                                            $user_balance = User::find($user);
                                            $user_balance->balance += $amount_card; 
                                            $user_balance->save();
                                
                                            $order_real->status = 2;
                                            $order_real->save();
                                
                                            $message = sprintf(
                                                "💰 Nạp thẻ thành công nhưng số tiền nhỏ hơn đơn hàng: %s \n———————————————\nMệnh giá thẻ: %s đ\n———————————————\nSeri：%s\n———————————————\nMã thẻ：%s\n———————————————\nSố dư được cộng thêm: %s đ\n———————————————\nEmail khách hàng: %s\n",
                                                $telco,
                                                $value,
                                                $serial,
                                                $code,
                                                $amount_card / 100,
                                                $user_balance->email
                                            );
                                            Helper::sendNotification($chatid, $tokenbot, $message);
                                            file_put_contents($dir . '/status.log', 105);
                                
                                            DB::commit();
                                            echo json_encode(['status' => 105, 'message' => 'Nạp thẻ thành công nhưng không đủ thanh toán đơn hàng nên số tiền đã được cộng vào số dư. vui lòng tạo đơn hàng mới và nạp thêm']);
                                        } catch (\Exception $e) {
                                            DB::rollback();
                                        }
                                    }
                                }
                            } else if ($result['status'] == 2) {
                                DB::beginTransaction();
                                try {
                                    $user_balance = User::find($user);
                                    $user_balance->balance += $amount_card / 2;
                                    $user_balance->save();
                                    
                                    $message = sprintf(
                                            "💰 Nạp thành công thẻ: %s \n———————————————\nMệnh giá chọn: %s đ\n———————————————\nMệnh giá thực: %s đ\n———————————————\nSeri：%s\n———————————————\nMã thẻ：%s\n———————————————\nĐã cộng: %s cho Email:%s \n",
                                            $telco,
                                            $amount,
                                            $value,
                                            $serial,
                                            $code,
                                            $amount_card / 2,
                                            $user_balance->email
                                        );
                                    Helper::notifyViaTelegram($message);

                                    file_put_contents($dir . '/status.log', 2);
                                    file_put_contents($dir . '/amount_real.log', $result['value']);
                                    // file_put_contents($status_file, '1');

                                    DB::commit();
                                } catch (\Exception $e) {
                                    DB::rollback();
                                }
                            } else {
                                file_put_contents($dir . '/status.log', 0);
                            }
                        }
                    }
                } else {
                    echo json_encode(['status' => 0, 'message' => 'Serial thẻ cào không hợp lệ!']);
                }
            } else {
                echo json_encode(['status' => 0, 'message' => 'Loại thẻ không hợp lệ!']);
            }
        }
    }
    else {
        echo json_encode(['status' => 0, 'message' => 'chưa có card hoặc card đã được nạp rồi']);
    }
}


