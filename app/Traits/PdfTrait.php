<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Events\AuditCustom;

trait PdfTrait
{
    protected $data;
    protected $view_path = 'admin.export.';
    protected $orientation = "P";

    public function __construct()
    {
        $this->data = array(
            'current_year' => date('Y'),
            'current_date' => date('Y-m-d H:i:s'),
            'attachment_label' => 'تقرير ',
            'header_default_logo' => 'https://rakaya.sa/_next/static/media/Gold2.22225b5d.webp'
        );
    }

    public function setPdfData($array)
    {
        if (!is_array($array)) {
            $array = ['body_content' => $array];
        }
        $data = $this->data;
        $data = array_merge($data, $array);
        $this->data = $data;
    }

    public function setOrientation($orientation)
    {
        $allowed_orientation = ["P", "L"];
        if (in_array($orientation, $allowed_orientation)) {
            $this->orientation = $orientation;
        }

        return abort(427, App::hasDebugModeEnabled() ? 'wrong orientation' : '');
    }


    function mPdfInit($path,$custom_header = null, $custom_footer = null)
    {

        $data = $this->data;
        $html = $this->getBladeTemplate($path, $data);

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'UTF-8',
            'display_mode' => 'fullpage',
            'orientation' => $this->orientation,
            'fontDir' => base_path('public/build/fonts/'),
            'fontdata' => [
                'arabicfont' => [
                    'R' => 'IBMPlexSansArabic-Regular.ttf',
                    'B' => 'IBMPlexSansArabic-Bold.ttf',
                    'I' => 'IBMPlexSansArabic-Bold.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            // Set the default font to the Arabic font
            'default_font' => 'arabicfont',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 1,
            'margin_bottom' => 5,
            'margin_header' => 0,
            'margin_footer' => 0,
            'setAutoTopMargin' => 'pad', // Automatically set top margin to pad header
            'setAutoBottomMargin' => 'pad', // Automatically set bottom margin to pad footer
        ]);
        $mpdf->text_input_as_HTML = true;
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->SetCompression(true);
        $mpdf->simpleTables = true;
        $mpdf->packTableData = true;
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->autoScriptToLang = true;
        $mpdf->autoArabic = true;
        $mpdf->autoLangToFont = true; //
        $mpdf->SetDirectionality('rtl');
        $mpdf->defaultheaderline = 0;
        // ini_set('memory_limit', '1500000M');
        // ini_set("pcre.backtrack_limit", "9000000");
        $headerHtml = $custom_header ?? view('admin.export.components.header', compact('data'))->render();
        $footerHtml = $custom_footer ?? view('admin.export.components.footer', compact('data'))->render();


        $mpdf->SetHTMLHeader($headerHtml);
        $mpdf->SetHTMLFooter($footerHtml);
        $mpdf->debug = true;
// dd($html);
        $mpdf->WriteHTML($html);
        $inserted_audit = $this->insert_audit();

        return $mpdf;
    }

    public function generatePDF($path, $filename, $output_type = 'I',$custom_header = null, $custom_footer = null)
    {

        //? if need to add more data to the pdf use the below
        // $this->setPdfData([
        //     'attachment_label' => 'تقرير بلاغ',
        //     'organization_data' => $ticket->order_sector->order->organization_service->organization,
        //     'body_content' => $ticket
        // ]);
        if (!view()->exists($this->view_path . $path)) {
            return abort(427, App::hasDebugModeEnabled() ? 'blade not exist' : '');
        }
        $mpdf = $this->mPdfInit($path,$custom_header,$custom_footer);
        return $mpdf->Output($filename . '.pdf', $output_type);
    }


    public function insert_audit()
    {

        if(config('app.stop_downloaded_audit')){
            Log::info('downloaded audit has stopped');
            return;
        }

        $data = $this->data;
        $model = $data['body_content'] ?? ($data['model'] ?? null);

        if (!$model) {
            Log::error('Model not found', $data);
            return;
        }

        // Ensure the model implements Auditable
        if (!($model instanceof \OwenIt\Auditing\Contracts\Auditable)) {
            Log::error('Model does not implement Auditable', ['model' => get_class($model)]);
            return;
        }

        $model->auditEvent = 'downloaded'; 
        $model->isCustomEvent = true;
        $model->auditCustomOld = [];
        $model->auditCustomNew = [
            'model_data' => $data,
        ];

        Event::dispatch( AuditCustom::class,[($model)]);

    }


    public function getBladeTemplate($path, $data)
    {
        // dd($this->view_path . $path);
        return view($this->view_path . $path, compact('data'))->render();
    }

    public function check_allowed_output_type($output_type)
    {
        $allowed_output_type = ['D', 'I', 'F', 'S'];
        return in_array($output_type, $allowed_output_type);
    }
}
