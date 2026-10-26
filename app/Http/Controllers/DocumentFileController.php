<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Support\StoredFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentFileController extends Controller
{
    public function __invoke(Request $request, Document $document): StreamedResponse
    {
        $user = $request->user();
        abort_unless($user && in_array($user->role, ['admin', 'agent'], true), 403);

        if ($user->role !== 'admin' && (int) $document->status !== 1) {
            abort(404);
        }

        if ($request->boolean('download') && $user->role !== 'admin' && (int) $document->access !== 1) {
            abort(403);
        }

        $path = StoredFile::safe($document->path);
        $disk = $path ? StoredFile::findDisk($path) : null;
        abort_unless($path && $disk, 404);

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $filename = trim($document->title) !== '' ? $document->title : 'document';
        if ($extension !== '') {
            $filename .= '.'.$extension;
        }

        return Storage::disk($disk)->response(
            $path,
            $filename,
            [
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store',
                'X-Frame-Options' => 'SAMEORIGIN',
            ],
            $request->boolean('download') ? 'attachment' : 'inline'
        );
    }
}
