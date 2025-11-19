<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\User;
use App\Models\Attachment;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Traits\AttachmentTrait;
use App\Traits\OrganizationTrait;
use App\Http\Controllers\Controller;

class AttachmentController extends Controller {

  use AttachmentTrait;
  use OrganizationTrait;
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {

  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {

  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    $user = User::find(1);
    if(!request()->has('images')){
      return response(['message'=>'No file submitted!'],400);

    }
    $signature = $this->store_attachment(request()->signature,$user, null,'mobile_test',$user->id);
    $signature= url('/') . \Storage::disk()->url( ($signature->path??'') . '/' . ($signature->name??''));
    $attachments = [];
    foreach(request()->images as $key => $image){

      $attachment = $this->store_attachment($image,$user, null,'mobile_test',$user->id);
      if($attachment){

        $attachment_url= url('/') . \Storage::disk()->url( ($attachment->path??'') . '/' . ($attachment->name??''));
        array_push($attachments,$attachment_url);
      }
    }

    if(!$attachments){
      return response(['message'=>trans('translation.something went wrong!')],400);
    }
    return response(['message'=>'attachment added successfully','attachments'=>$attachments,'signature'=>$signature,'texts_array'=>request()->texts_array],200);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show(Attachment $attachment)
  {
      $attachment_url= url('/') . \Storage::disk()->url( ($attachment->path??'') . '/' . ($attachment->name??''));

      return response(compact('attachment_url'),200);

  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {

  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {

  }

}

?>
