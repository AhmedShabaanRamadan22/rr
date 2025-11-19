<?php

namespace App\Services;

use App\Models\Attachment;
use Illuminate\Support\Collection;

class GalleryService
{
    public function getAllAttachments() {
        
        return Attachment::with(['user','attachmentable'])->images()->answer()->orderByDesc('created_at'); 
    }
}