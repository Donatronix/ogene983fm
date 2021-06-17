<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait SendEmail
{
    private $mail;

    //from address
    private $from;
    private $name;

    /**
     * Send email
     *
     * @param mixed|\Illuminate\Support\Collection|Array|string $to
     * @param string $subject
     * @param string $body
     * @param \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $attachment
     *
     * @return void
     */
    public function send($to, string $subject, string $body, $attachment = null)
    {
        $msg = null;
        $totalSent = 0;
        try {
            if (is_string($to)) {
                $to = strpos($to, ";") > -1 ? explode($to, ';') : array($to);
                $tempTo = [];
                foreach ($to as $row) {
                    $tempTo['email'] = $row;
                }
                $to = $tempTo;
            } elseif ($to instanceof Collection) {
                $to = $to->toArray();
            }
            // Instantiation and passing `true` enables exceptions
            $this->setMail(new PHPMailer(true));

            $mail = $this->getMail();

            //Set who the message is to be sent from
            $mail->setFrom($this->getFrom(), $this->getName());

            //Set an alternative reply-to address
            $mail->addReplyTo(
                'info@pharmacytherapon.com',
                'PharmacyTherapon'
            );

            //Set the subject line
            $mail->Subject = $subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //and convert the HTML into a basic plain-text alternative body
            $mail->msgHTML($body);

            foreach ($to as $row) {
                try {
                    $mail->addAddress($row['email'], $row['name'] ?? null);
                } catch (Exception $e) {
                    $msg .= 'Invalid address skipped: ' . htmlspecialchars($row['email']) . '<br>';
                    continue;
                }
                //Attach file


                if (request()->hasFile('attachment') && request()->file('attachment')->isValid()) {
                    //Attach multiple files one by one
                    foreach (request()->file('attachment') as $key => $file) {
                        $uploadfile = $file->getRealPath();
                        $filename = $file->getClientOriginalName();
                        if (!$mail->addAttachment($uploadfile, $filename)) {
                            $msg .= 'Failed to attach file ' . $filename;
                        }
                    }
                } elseif ($attachment && $attachment instanceof Media) {
                    foreach ($attachment as $key => $media) {
                        $uploadfile = $media->getPath();
                        $filename = $media->file_name;
                        if (!$mail->addAttachment($uploadfile, $filename)) {
                            $msg .= 'Failed to attach file ' . $filename;
                        }
                    }
                }

                try {
                    $mail->send();
                    $msg .= 'Message sent to :' . htmlspecialchars($row['name']) . ' (' . htmlspecialchars($row['email']) . ')<br>';
                    $totalSent++;
                } catch (Exception $e) {
                    $msg .= 'Mailer Error (' . htmlspecialchars($row['email']) . ') ' . $mail->ErrorInfo . '<br>';
                    //Reset the connection to abort sending this message
                    //The loop will continue trying to send to the rest of the list
                    $mail->getSMTPInstance()->reset();
                }
                //Clear all addresses and attachments for the next iteration
                $mail->clearAddresses();
                $mail->clearAttachments();
            }
        } catch (Exception $th) {
            return 'Caught a ' . get_class($th) . ': ' . $th->getMessage();
        } catch (\Throwable $th) { //The leading slash means the Global PHP Exception class will be caught
            return $th->getMessage(); //Boring error messages from anything else!
        }
        $numberOfEmailAddresses = count($to);
        return "$totalSent of $numberOfEmailAddresses " . Str::plural('Email', $numberOfEmailAddresses) . ' sent!<br/>' . $msg;
    }

    /**
     * Get the value of from address
     */
    public function getFrom()
    {
        return $this->from ?? 'info@pharmacytherapon.com';
    }

    /**
     * Set the value of from address
     *
     * @return  self
     */
    public function setFrom($from, $name = null)
    {
        $this->from = $from;
        if ($name) {
            $this->setName($name);
        }
        return $this;
    }

    /**
     * Get the value of mail object
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set the value of mail object
     *
     * @return  self
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
        //Server settings
        $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $this->mail->isSMTP();                                            // Send using SMTP
        $this->mail->Host       = 'smtp1.pharmacytherapon.com';                    // Set the SMTP server to send through
        $this->mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $this->mail->Username   = 'user@example.com';                     // SMTP username
        $this->mail->Password   = 'secret';                               // SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $this->mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        $this->mail->SMTPKeepAlive = true; // SMTP connection will not close after each email sent, reduces SMTP overhead
        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name ?? 'PharmacyTherapon';
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
