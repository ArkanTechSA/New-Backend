<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    protected $username = 'Ymtaz.sa';

    protected $password = '7uhb6YGV@@ymtaz!?';

    protected $sender = 'Ymtaz.sa';

    /**
     * إرسال رسالة SMS
     *
     * @param  string|array  $to  رقم واحد أو مصفوفة أرقام مفصولة
     * @param  string  $message  نص الرسالة
     * @return bool نجاح أو فشل
     */
    public function sendSms($to, string $message): bool
    {

        $message = urlencode($message);
        if (is_array($to)) {
            $to = implode(',', $to);
        }

        $passwordEncoded = urlencode($this->password);

        $url = "http://www.jawalbsms.ws/api.php/sendsms?user={$this->username}&pass={$passwordEncoded}&to={$to}&message={$message}&sender={$this->sender}&unicode=u";

        try {
            $response = Http::get($url);

            return $response->successful();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SMS sending failed: '.$e->getMessage());

            return false;
        }
    }
}
