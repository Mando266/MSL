<?php


use App\Mail\TestMail;

Route::get('/testmail', function () {
    Mail::to('goee2015@gmail.com')->send(new \App\Mail\gogoMail('testgogo'));
});