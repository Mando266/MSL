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
    protected String $authMail;
    
    public function __construct($details)
    {
        $this->details = $details;
        $this->authMail = auth()->user()->email ?? 'info-meastline@meastline.com';
        if (!str_ends_with($this->authMail, '@meastline.com')) {
            $this->authMail = 'info-meastline@meastline.com';
        }
    }


    public function build()
    {
        return $this->markdown('emails.temperature-discrepancy-mail')
            ->subject('Temperature Discrepancy')
            ->from($this->authMail)
            ->with([
                'details' => $this->details
            ]);
    }
}
