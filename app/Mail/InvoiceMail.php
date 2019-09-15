<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;
    public $pdf;
    public $subject;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pdf, $subject)
    {
        $this->pdf = $pdf;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.invoice')->from(env('MAIL_FROM_ADDRESS', 'admin@tropicalgida.hol.es'), "Invoice")
            ->attachData($this->pdf->output(), 'invoice.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
