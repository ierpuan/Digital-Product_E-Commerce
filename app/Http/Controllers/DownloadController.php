<?php

namespace App\Http\Controllers;

use App\Models\DownloadToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadController extends Controller
{
    // GET /download/{token}
    public function __invoke(string $token)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $downloadToken = DownloadToken::with('orderItem.product')
            ->where('token', $token)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (!$downloadToken->isUsable()) {
            abort(403, 'Token download tidak valid, sudah habis, atau sudah kedaluwarsa.');
        }

        $filePath = $downloadToken->consume();

        if (!Storage::disk('private')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        $product  = $downloadToken->orderItem->product;
        $fileName = Str::slug($product->name) . '.' . $product->file_type;

        return response()->download(
            Storage::disk('private')->path($filePath),
            $fileName
        );
    }
}
