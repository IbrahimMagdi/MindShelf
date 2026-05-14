<?php
declare(strict_types=1);

namespace App\Infrastructure\Services\User;

use App\Application\User\Ports\ImageStorageInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final readonly class ImageStorage implements ImageStorageInterface
{
    public function store(string $sourcePath, string $destinationPath): string
    {
        $filename = Str::uuid() . '.' . pathinfo($sourcePath, PATHINFO_EXTENSION);
        $fullPath = trim($destinationPath, '/') . '/' . $filename;

        Storage::disk('public')->putFileAs(
            dirname($fullPath),
            $sourcePath,
            $filename
        );
        return $fullPath;
    }
    public function delete(?string $path): void
    {
        if ($path && !str_starts_with($path, 'images/')) {
            Storage::disk('public')->delete($path);
        }
    }

    public function getUrl(string $path): string
    {
        return Storage::disk('public')->url($path);
    }


}
