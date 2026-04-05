<?php 
namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CMail
{
    public static function send($config)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug  = 0;
            $mail->isSMTP();
            $mail->Host       = config('services.mail.host');
            $mail->SMTPAuth   = true;
            $mail->Username   = config('services.mail.username');
            $mail->Password   = config('services.mail.password');
            $mail->SMTPSecure = config('services.mail.encryption');
            $mail->Port       = config('services.mail.port');

            $mail->setFrom(
                $config['from_address'] ?? config('services.mail.from_address'),
                $config['from_name']    ?? config('services.mail.from_name')
            );

            $mail->addAddress(
                $config['recipient_address'],
                $config['recipient_name'] ?? null
            );

            $mail->isHTML(true);
            $mail->Subject = $config['subject'];
            $mail->Body    = $config['body'];

            return $mail->send();

        } catch (Exception $e) {
            return false;
        }
    }
}