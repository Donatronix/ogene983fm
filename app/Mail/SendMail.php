<?php

namespace App\Mail;

use App\Traits\UploadAble;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    use UploadAble;

    public $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->subject($this->details['subject']);
        if (isset($this->details['attachment'])) {
            foreach ($this->details['attachment'] as $key => $file) {
                //attach the file
                $mail->attach($file);
            }
        }
        if ($this->details['from']) {
            $mail = $this->from($this->details['from']);
        }
        return $mail->view('emails.sendMail');
    }
}
