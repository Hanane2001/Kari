<?php
require_once __DIR__ . '/EmailService.php';

class ReservationEmailNotification
{
    public static function reservationCreated(string $emailHote,int $logementId): void {
        $subject = "New reservation";
        $body = "<h3>New reservation</h3>
            <p>A new reservation has been made for your logement. <strong>#{$logementId}</strong>.</p>";

        EmailService::send($emailHote, $subject, $body);
    }

    public static function reservationCancelled(string $emailUser,int $reservationId,string $reason): void {
        $subject = "Reservation cancelled";
        $body = " <h3>Reservation cancelled</h3>
            <p>The reservation <strong>#{$reservationId}</strong> has been cancelled.</p>
            <p>Reason : {$reason}</p>";

        EmailService::send($emailUser, $subject, $body);
    }
}
