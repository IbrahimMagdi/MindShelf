<?php
declare(strict_types=1);

namespace App\Domain\Category\Entities;

use App\Domain\Category\ValueObjects\CategoryName;
use App\Domain\Category\ValueObjects\CategorySlug;

final class CategoryEntity
{
    private function __construct(
        private ?int $id,
        private CategoryName $name,
        private CategorySlug $slug,
        private bool $status
    ) {}

    public static function create(CategoryName $name, CategorySlug $slug): self{
        return new self(
            id: null,
            name: $name,
            slug: $slug,
            status: false
        );

    }
    public function fromPersistence(int $id, CategoryName $name, CategorySlug $slug, bool $status): self{
        return new self(
            $id,
            $name,
            $slug,
            $status
        );
    }
    public function rename(CategoryName $name): void
    {
        $this->name = $name;
    }
    public function activate(): void
    {
        $this->status = true;
    }
    public function deactivate(): void
    {
        $this->status = false;
    }
    public function isActive():bool
    {
        return $this->status;
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): CategoryName { return $this->name; }
    public function getSlug(): CategorySlug { return $this->slug; }
}

