<?php

namespace App\Http\Controllers;

use App\Mail\temperatureDiscrepancyMail;
use App\Services\MailControllerService;
use Illuminate\Http\Request;
use Mail;

class MailController extends Controller
{
    protected MailControllerService $mailService;
    
    public function __construct()
    {
        $this->mailService = new MailControllerService();
    }

    public function sendTemperatureDiscrepancyMail()
    {
        $emails = $this->mailService->emailStringToArray(request()->emails);
        $data = request()->except(['emails', '_token']);
        $details = $this->mailService->seperateInputByIndex($data);
        
        Mail::to($emails)->send(new temperatureDiscrepancyMail($details));
        return redirect()->route('booking.index')->with('success',"Email Sent Successfully!");
    }
}
