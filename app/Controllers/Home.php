<?php

namespace App\Controllers;
use Telegram\Bot\Api;

class Home extends BaseController
{
    public function index(){
        $message = "Kamu siapa";
        $encrypt_message = urlencode($message);
        $url = "https://api.hy-tech.my.id/api/gemini/".$message;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $answer = json_decode($result, true);
        dd($encrypt_message);
    }

    public function telegram(){
        $data = file_get_contents('php://input');

        $decoded_data = json_decode($data, true);
        $chat_id = $decoded_data['message']['from']['id'];

        $message = $decoded_data['message']['text'];
        $messageEncrypt = urlencode($message);

        $url = "https://api.hy-tech.my.id/api/gemini/";

        $complete_message = $url.$messageEncrypt;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $complete_message);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $answer = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($answer, true);
        $text_array = $data['text'];

        $telegram = new Api('7056736653:AAERod1e9EvITVBMp3HA6KoP6mFU672FGUw');

        switch($message) {
            case '/start':
                $response = $telegram->sendMessage([
                    'chat_id' => $chat_id,
                    'text' => 'Halo, aku bot di buat oleh Adi dan aku bisa membantu mu menjawab semua pertanyaan mu. Silahkan ketik apa saja, aku akan menjawab mu.'
                ]);
                $response->getMessageId();
                break;

            default:
                $response = $telegram->sendMessage([
                    'chat_id' => $chat_id,
                    'text' => $text_array
                ]);
                $response->getMessageId();
                break;
        }

    }

    // https://api.telegram.org/bot7056736653:AAERod1e9EvITVBMp3HA6KoP6mFU672FGUw/setWebhook?url=https://diginine.my.id/webhook    // // Tentukan nama file untuk menyimpan data
}
