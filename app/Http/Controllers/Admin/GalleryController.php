<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttachmentResource;
use App\Models\Attachment;
use App\Services\GalleryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    protected $gallerService;

    public function __construct(GalleryService $gallerService)
    {
        $this->gallerService = $gallerService;
    } 
    
    
    public function index() : View {
        
        $images = $this->gallerService->getAllAttachments()->limit(10)->get();
        return view('admin.gallery.index',compact('images'));
    }

    public function getGalleries(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);

        $images = $this->gallerService->getAllAttachments()->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => AttachmentResource::collection($images),
            'next_page' => $images->nextPageUrl() ? $page + 1 : null
        ]);
    }

}
