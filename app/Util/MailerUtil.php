<?php

namespace App\Util;

use App\Mail\ExceptionOccured;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailerUtil
{
    public static function sendErrorMail(\Throwable $exception): void
    {
        try {

            $content['message'] = $exception->getMessage();
            $content['file'] = $exception->getFile();
            $content['line'] = $exception->getLine();
            $content['trace'] = $exception->getTrace();
            $request = request();
            $content['request'] =[
                'method' => $request?->method(),
                'url' => $request?->fullUrl(),
                'headers' => $request?->headers?->all(),
                'body' => $request?->all(),
                'user' => $request?->user()
            ];
            $content['user'] =[
                'name' => $request?->user()?->name,
                'roles' => $request?->user()?->getRoleNames()?->toJson(),
                'email' => $request?->user()?->email,

            ];

            $content['url'] = request()->url();
            $content['body'] = request()->all();
            $content['ip'] = request()->ip();

            Mail::to('williamsbsi@gmail.com')
                ->cc([
                    "daniells.developer@gmail.com",
                    'saviogmoiagaia.2001@gmail.com'
                ])
                ->send(new ExceptionOccured($content));


        } catch (\Throwable $exception) {
            Log::error($exception);
        }

    }

}
