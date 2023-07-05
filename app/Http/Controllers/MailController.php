<?php

namespace App\Http\Controllers;

use App\Mail\temperatureDiscrepancyMail;
use Illuminate\Http\Request;
use Mail;

class MailController extends Controller
{
    public function sendMailToCustomer()
    {
        dd(request()->all());
    }

    public function sendMailToProvider()
    {
        $emails = array_filter(explode("\r\n", request()->provider_email));
        $data = request()->except(['customer_email', 'provider_email', '_token']);
        $details = collect();

        for ($i = 0; $i < count($data['container_no']); $i++) {
            $item = collect($data)->map(fn($values) => $values[$i]);
            $details->push(collect($item));
        }
        Mail::to($emails)->send(new temperatureDiscrepancyMail($details));
        return redirect()->route('booking.index')->with('message',"Email Sent Successfully!");
    }
}
