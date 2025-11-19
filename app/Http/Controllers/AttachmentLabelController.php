<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use App\Models\AttachmentLabel;
use App\Traits\OrganizationTrait;
use App\Models\OrganizationAttachmentLabel;

class AttachmentLabelController extends Controller
{
  use OrganizationTrait;
  public function show($type)
  {
    try {
      return response()->json([
        'attachment_labels' => AttachmentLabel::where('type', $type)->get(),
      ], 200);
    } catch (Throwable $th) {
      return response()->json([
        'message' => $th->getMessage()
      ], 500);
    }
  }

  public function showOrgLabels()
  {
    try {
      $this->validateOrganization();
      return response()->json([
        'attachment_labels' => OrganizationAttachmentLabel::with('attachment_labels')->where('organization_id', request()->organization_id)->get(),
      ], 200);
    } catch (Throwable $th) {
      return response()->json([
        'message' => $th->getMessage()
      ], 500);
    }
  }
}
