<?php

namespace App\Traits;

use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

trait sendMails
{

    private function processEmails($emails)
    {
        $emails = explode(";", $emails);
        $emails = collect($emails);
        $emails->each(function ($item, $key) {
            $item = trim($item);
        });
        return $emails->toArray();
    }

    public function sendMail($to, $bcc = null, $cc = null, array $details)
    {
        try {
            $to = $this->processEmails($to);
            $mail = Mail::to($to);
            if (!is_null($bcc)) {
                $bcc = $this->processEmails($bcc);
                $mail = $mail->bcc($bcc);
            }
            if (!is_null($cc)) {
                $cc = $this->processEmails($cc);
                $mail = $mail->cc($cc);
            }
            $mail->send(new SendMail($details));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
