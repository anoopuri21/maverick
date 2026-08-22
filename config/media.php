<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Media library (Cloudinary-backed)
    |--------------------------------------------------------------------------
    |
    | Permanent media is stored on Cloudinary only. The server keeps temporary
    | Livewire uploads briefly; never mirror full media libraries to disk.
    |
    */

    'max_upload_kilobytes' => (int) env('MEDIA_MAX_UPLOAD_KB', 5120), // 5 MB

    'allowed_mime_prefixes' => [
        'image/',
    ],

];
