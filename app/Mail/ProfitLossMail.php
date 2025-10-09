<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class ProfitLossMail extends Mailable
{
    use Queueable, SerializesModels;

    public $revenue;
    public $expenses;
    public $netProfit;
    public $pdf;

    /**
     * Create a new message instance.
     */
    public function __construct($revenue, $expenses, $netProfit, $pdf)
    {
        $this->revenue = $revenue;
        $this->expenses = $expenses;
        $this->netProfit = $netProfit;
        $this->pdf = $pdf;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Profit & Loss Report',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.profit_loss',
            with: [
                'revenue' => $this->revenue,
                'expenses' => $this->expenses,
                'netProfit' => $this->netProfit,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn () => $this->pdf,
                'profit_loss_report.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
