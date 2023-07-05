<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class temperatureDiscrepancyMail extends Mailable
{
    use Queueable, SerializesModels;
    
    protected $details;
    
    public function __construct($details)
    {
        $this->details = $details;
    }


    public function build()
    {
        return $this->markdown('emails.temperature-discrepancy-mail')
            ->subject('Temperature Discrepancy')
            ->with([
                'details' => $this->details
            ]);
    }
}
