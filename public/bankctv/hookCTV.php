<?php
error_reporting(E_ALL); 
include('ZicBoard.php');

use App\Models\Order;
use App\Services\TelegramService;
use App\Models\User;
use Illuminate\Support\Facades\DB;


include('config.php');

$trade_no = $_POST['tradeno'];
$token = $_POST['token']; 
$orderTK = DB::table('v2_order')->where('trade_no', $trade_no)->first();
$id_order = $orderTK->id;
$id_staffTK = $orderTK->invite_user_id;
$paymentInfo = DB::table('v2_payment')->where('id_staff', $id_staffTK)->first();
if ($paymentInfo->token != $token) {
    echo json_encode([['staff' => 3, 'mess' => 'token lỗi']]);
    exit;
}

$bankId = $_POST['bankid']; 
$url = ""; 


if ($bankId == "970436") {
    $url = "https://api/vpnfast.vn/api/historyvietcombank/$token";
} elseif ($bankId == "970416") {
    $url = "https://api.vpnfast.vn/api/historyacb/$token";
} elseif ($bankId == "970422") {
    $url = "https://api.vpnfast.vn/api/historyviettel/$token";
}


$response = file_get_contents($url);
$DataInput = json_decode($response);

$transactions = [];
$keyword = strtolower("bank");
$results = [];
$commentFromPost = strtolower($_POST['noidung']); //key +  id

if ($bankId == "970436") {
    if (isset($DataInput->transactions) && is_array($DataInput->transactions)) {
        $transactions = $DataInput->transactions;
    }
} elseif ($bankId == "970422") {
    if (isset($DataInput->data->content) && is_array($DataInput->data->content)) {
        $transactions = $DataInput->data->content;
    }
} elseif ($bankId == "970416") {
    if (isset($DataInput->data) && is_array($DataInput->data)) {
        $transactions = $DataInput->data;
    }
}

foreach ($transactions as $transaction) {
    if ($bankId == "970436") {
        $description = strtolower($transaction->Description); 
        $amount = (int)str_replace(',', '', $transaction->Amount);
    } elseif ($bankId == "970422") {
        $description = strtolower($transaction->description); 
        $amount = (int)$transaction->amount;
    } elseif ($bankId == "970416") {
        $description = strtolower($transaction->description); 
        $amount = (int)$transaction->amount;
    }
        
    
        if (strpos($description, $commentFromPost) !== false) {
            
            
            
            $staff = 0;

            $status = $orderTK->status;
            $tien = $orderTK->total_amount;
            $price = $tien/100;
                    

            if ($status == "5") {
                $staff = 2;
            } elseif (($status == "0" || $status == "2") && $amount >= $price) {
                        
                        $order = DB::table('v2_order')->where('trade_no', $trade_no)->first();
                        if ($order) {
                            
                            $user = DB::table('v2_user')->where('id', $order->invite_user_id)->first();
                            if ($user) {
                                $prices = $price*100;
                                
                                $commission_rate = $user->commission_rate; 
                                $required_balance = $prices - ($prices * ($commission_rate / 100));
                                
                                if ($user->commission_balance >= $required_balance) {
                                    try {
                                        
                                        DB::beginTransaction();
                
                                        
                                        DB::table('v2_order')->where('trade_no', $trade_no)->update([
                                            'status' => '1',
                                            
                                            'commission_status' => '4',
                                            'callback_no' => "Bank Riêng CTV",
                                            'commission_balance' => -$required_balance
                                        ]);
                
                                        DB::table('v2_user')->where('id', $user->id)->decrement('commission_balance', $required_balance);

                                        DB::commit();
                                        $telegramService = new TelegramService();
                                        
                                        $userEmail = 'Email not found';
                                        if ($order) {
                                            $userFromOrder = DB::table('v2_user')->where('id', $order->user_id)->first();
                                            if ($userFromOrder) {
                                                $userEmail = $userFromOrder->email;
                                            }
                                        }
                                        $invitedUser = $user->invite_user_id;
                                        $webcon = $user ? $user->staff_url : 'Web mẹ';

                                        $message = sprintf(
                                            "💰TT Bank Riêng %s VNĐ\n———————————————\nID ĐH：%s\n———————————————\nNội Dung: %s\n———————————————\nEmail: %s\n———————————————\nWeb: %s",
                                            $price,
                                            $trade_no,
                                            $commentFromPost,
                                            $userEmail,
                                            $webcon
                                        );
                                        $telegramService->sendMessageWithAdmin($message);
                                        if ($user && $user->id && $user->telegram_id) {
                                            $telegramService->sendMessageToUser($message,$user->id);
                                        }
                
                                        $staff = 1;
                                    } catch (Exception $e) {
                                        
                                        DB::rollBack();
                
                                        
                                        error_log('Error updating order and user balance: ' . $e->getMessage());
                                    }
                                } else {
                                    $staff = 2; 
                                    DB::table('v2_order')->where('trade_no', $trade_no)->update(['status' => '5']);
                                    $telegramService = new TelegramService();
                                    $userEmail = 'Email not found';
                                        if ($order) {
                                            $userFromOrder = DB::table('v2_user')->where('id', $order->user_id)->first();
                                            if ($userFromOrder) {javascript:;
                                                $userEmail = $userFromOrder->email;
                                            }
                                        }
                                    $invitedUser = $user->invite_user_id;
                                    $webcon = $user ? $user->staff_url : 'Web mẹ';

                                    $message = sprintf(
                                        "⚠️Bank Riêng đã nhận %s VNĐ\n———————————————\nID ĐH：%s\n———————————————\nNội Dung: %s\n———————————————\nEmail: %s\n———————————————\nWeb: %s\n———————————————\nLỗi: Số dư CTV không đủ",
                                        $price,
                                        $trade_no,
                                        $commentFromPost,
                                        $userEmail,
                                        $webcon
                                    );
                                    $telegramService->sendMessageWithAdmin($message);
                                    if ($user && $user->id && $user->telegram_id) {
                                        $telegramService->sendMessageToUser($message,$user->id);
                                    }
                                }
                            }
                        }
                    }
               
            
            $results[] = [
                'comment' => $commentFromPost,
                'amount' => $amount,
                'staff' => $staff,
            ];
        }
    }
    echo json_encode(['data' => $results]);

