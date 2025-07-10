<?php

namespace App\Service;

use App\Document\Charge;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ChargeDeliveryService{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer){
        $this->mailer = $mailer;
    }

    public function dispatchDelivery(Charge $charge): void{
        foreach ($charge->getDeliveryMethods() as $method) {
            match ($method) {
                'email' => $this->sendByEmail($charge),
                'whatsapp' => $this->sendByWhatsApp($charge),
                'sms' => $this->sendBySMS($charge),
                default => null
            };
        }
    }

    private function sendByEmail(Charge $charge): void{
        $formattedValue = number_format($charge->getAmount(), 2, ',', '.');
    $formattedDate = $charge->getDueDate()?->format('d/m/Y');

    $html = "
    <div style=\"font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;\">
        <h2 style=\"color: #1d4ed8; text-align: center;\">📬 Nova Cobrança Recebida</h2>
        
        <p style=\"font-size: 16px; color: #111827;\">Olá <strong>{$charge->getCustomerName()}</strong>,</p>

        <p style=\"font-size: 15px; color: #374151;\">Você recebeu uma nova cobrança. Veja os detalhes abaixo:</p>

        <table style=\"width: 100%; border-collapse: collapse; margin: 20px 0;\">
            <tr><td style=\"padding: 8px; font-weight: bold; color: #374151;\">Descrição:</td><td style=\"padding: 8px;\">{$charge->getDescriptioncharge()}</td></tr>
            <tr><td style=\"padding: 8px; font-weight: bold; color: #374151;\">Valor:</td><td style=\"padding: 8px;\">R$ {$formattedValue}</td></tr>
            <tr><td style=\"padding: 8px; font-weight: bold; color: #374151;\">Vencimento:</td><td style=\"padding: 8px;\">{$formattedDate}</td></tr>
        </table>

        <div style=\"text-align: center; margin-top: 30px;\">
            <a href=\"#\" style=\"background: #10b981; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold;\">Pagar Agora</a>
        </div>

        <p style=\"font-size: 14px; color: #6b7280; margin-top: 40px; text-align: center;\">
            Se você tiver dúvidas, entre em contato conosco.<br><br>
            <em>Este e-mail foi gerado automaticamente. Por favor, não responda.</em>
        </p>
    </div>
    ";

    $email = (new Email())
        ->from('israelgmedeiros@gmail.com')
        ->to($charge->getCustomerEmail())
        ->subject('📬 Nova Cobrança Recebida')
        ->html($html);

    $this->mailer->send($email);
    }

    private function sendByWhatsApp(Charge $charge): void{}
    private function sendBySMS(Charge $charge): void{}
}
