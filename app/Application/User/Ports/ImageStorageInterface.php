<?php
declare(strict_types=1);

namespace App\Application\User\Ports;



interface ImageStorageInterface
{
    public function store(string $sourcePath, string $destinationPath): string;
    public function delete(?string $path): void;
    public function getUrl(string $path): string;
}
