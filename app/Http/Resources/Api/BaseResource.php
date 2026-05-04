<?php
declare(strict_types=1);

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseResource extends JsonResource
{
    /**
     * Wrap single item response
     */
    public static function item($resource): array
    {
        return (new static($resource))->resolve();
    }

    /**
     * Wrap collection response
     */
    public static function collectionItems($resources): array
    {
        return $resources->map(fn ($item) => static::item($item))->all();
    }
}
