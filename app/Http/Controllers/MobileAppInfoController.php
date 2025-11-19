<?php

namespace App\Http\Controllers;

use App\Http\Requests\updateMobileInfoRequest;
use App\Http\Resources\MobileAppInfoResource;
use App\Models\AttachmentLabel;
use App\Models\MobileInfo;
use App\Traits\AttachmentTrait;
use Illuminate\Http\Request;

class MobileAppInfoController extends Controller
{

    use AttachmentTrait;

    public function getCurrentAppVersion()
    {
        $mobileInfo = MobileInfo::with([
            'androidBundleFile',
            'iosBundleFile'
        ])->latest('created_at')
            ->first();
        if (is_null($mobileInfo)) {
            return response([
                'flag' => false,
                'message' => 'لاتوجد بيانات لنسخة التطبيق حاليا، الرجاء رفع بيانات النسخة الاخيرة'
            ], 400);
        }
        return response(MobileAppInfoResource::make($mobileInfo));
    }

    public function updateAppVersion(updateMobileInfoRequest $request)
    {
        $data = $request->safe()->all();
        $mobileInfo = MobileInfo::latest('created_at')->first();
        $user = auth()->user();
        $mobileInfo = MobileInfo::create(['current_version' => $data['current_version']]);
        $androidAttachmentLabel = AttachmentLabel::ANDROID_APP_BUNDLE;
        $iosAttachmentLabel = AttachmentLabel::IOS_APP_BUNDLE;
        $this->store_attachment($data['android_bundle_file'], $mobileInfo, $androidAttachmentLabel, null, $user->id);
        $this->store_attachment($data['ios_bundle_file'], $mobileInfo, $iosAttachmentLabel, null, $user->id);

        return response([
            'message' => 'تم رفع بيانات النسخة الجديدة للتطبيق'
        ]);
    }
}
