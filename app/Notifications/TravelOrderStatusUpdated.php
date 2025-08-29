<?php

namespace App\Notifications;

use App\Models\TravelOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TravelOrderStatusUpdated extends Notification
{
    use Queueable;


    public $travelOrder;


    public function __construct(TravelOrder $travelOrder)
    {

        $this->travelOrder = $travelOrder;
    }


    public function via(object $notifiable): array
    {

        return ['mail'];
    }


    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->travelOrder->status;
        $subject = "Atualização do seu Pedido de Viagem #{$this->travelOrder->id}";
        $greeting = "Olá, {$notifiable->name}!";
        $line = "O status do seu pedido de viagem para {$this->travelOrder->destination} foi atualizado para: **{$status}**.";

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($line)
            ->action('Ver Pedido', url('/pedidos/' . $this->travelOrder->id)) 
            ->line('Obrigado por usar nossa aplicação!');
    }
}
