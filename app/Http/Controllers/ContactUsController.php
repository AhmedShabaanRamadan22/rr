<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Models\ContactUs;
use App\Traits\SmsTrait;
use Illuminate\Http\Request;
use App\Traits\WhatsappTrait;

class ContactUsController extends Controller
{

    use WhatsappTrait, SmsTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( ContactUsRequest $request )
    {
        $contactUs = ContactUs::create( $request->all() );

        $message = trans( 'translation.send-whatsapp-add-new-contact-us', [ 'name' => $contactUs->name ] );
        $whatsapp_response = $this->send_message( $this->getSender(), $message, $contactUs->phone_code. $contactUs->phone );
        $sending_sms = $this->send_sms($this->getSender(),$message,$contactUs->phone,$contactUs->phone_code);

        return response()->json( [ 'message' => trans( 'translation.submitted-successfully' ) , 'whatsapp_response' => $whatsapp_response, 'sending_sms' => $sending_sms ], 200 );

    }

    /**
     * Display the specified resource.
     */
    public function show( ContactUs $contactUs )
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( ContactUs $contactUs )
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( Request $request, ContactUs $contactUs )
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( ContactUs $contactUs )
    {
        //
    }
}
