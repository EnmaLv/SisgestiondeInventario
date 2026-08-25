<?php

namespace App\Http\Controllers\salud;

use App\Http\Controllers\Controller;

class MediaController extends Controller
{
    public function showPublicacionMedia($filename)
    {
        $path = storage_path('app/public/publicaciones/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        $type = mime_content_type($path);

        return response()->file($path, [
            'Content-Type' => $type
        ]);
    }

    public function showProfilePhoto($filename)
    {
        $path = storage_path('app/public/profile-photos/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        $type = mime_content_type($path);

        return response()->file($path, [
            'Content-Type' => $type
        ]);
    }
}
