<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

class EmailService
{
    private static function getMailer(): PHPMailer
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['stmp_username'];
        $mail->Password   = $_ENV['stmp_password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('abdo.el.kabli12@gmail.com', 'kari Clone');
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        return $mail;
    }

    public static function send(
        string $to,
        string $subject,
        string $body
    ): bool {
        try {
            $mail = self::getMailer();
            $mail->addAddress($to);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            return $mail->send();
        } catch (Exception $e) {
            error_log("Email error: " . $e->getMessage());
            return false;
        }
    }
}
