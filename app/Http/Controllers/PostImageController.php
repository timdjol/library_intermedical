<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Support\StoredFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PostImageController extends Controller
{
    public function __invoke(Post $post): StreamedResponse
    {
        $path = StoredFile::safe($post->image);
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->response($path, basename($path), [
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
