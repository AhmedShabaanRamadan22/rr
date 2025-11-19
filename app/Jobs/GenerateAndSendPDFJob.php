<?php

namespace App\Jobs;

use App\Http\Services\MailService;
use App\Models\Danger;
use App\Models\Status;
use App\Traits\PdfTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateAndSendPDFJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, PdfTrait;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private $organization,
        private $pdfName,
        private $pdfTemplate,
        private $pdfData,
        private $mailTopic,
        private $mailTemplate,
        private $mailTemplateData,
    ) {
        $this->data = array(
            'current_year' => date('Y'),
            'current_date' => date('Y-m-d H:i:s'),
            'attachment_label' => 'تقرير ',
            'header_default_logo' => 'https://rakaya.sa/_next/static/media/Gold2.22225b5d.webp'
        );
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $populatedTemplate = view($this->mailTemplate, $this->mailTemplateData);
        $this->setPdfData($this->pdfData);
        $mpdf = $this->mPdfInit($this->pdfTemplate);
        $pdf = $mpdf->Output(dest: 'S');
        $receivers = config('app.send_email_to_chairmans') ? [$this->organization->chairmans] : [['a.bahmeed@rakaya.co', 'o.khan@rakaya.co', 'o.jehni@rakaya.co']];
        (new MailService())->sendMailToUsers(
            receivers_array: $receivers,
            topic: $this->mailTopic,
            content: $populatedTemplate,
            organization: $this->organization,
            raw_attachments: [
                [
                    'content' => $pdf,
                    'name' => $this->pdfName
                ]
            ],
        );
    }
}
