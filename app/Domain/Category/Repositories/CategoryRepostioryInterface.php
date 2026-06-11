<?php
declare(strict_types=1);

namespace App\Domain\Category\Repositories;

use App\Domain\Category\Entities\CategoryEntity;
use App\Domain\Category\ValueObjects\CategorySlug;

interface CategoryRepositoryInterface
{
    public function findById(int $id): ?CategoryEntity;
    public function findBySlug(CategorySlug $slug): ?CategoryEntity;
    public function save(CategoryEntity $category): CategoryEntity;
    public function delete(CategoryEntity $category): void;
    public function findAll(): array;
}
