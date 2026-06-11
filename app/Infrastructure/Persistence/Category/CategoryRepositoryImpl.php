<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Category;

use App\Domain\Category\Entities\CategoryEntity;
use App\Domain\Category\ValueObjects\CategorySlug;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Infrastructure\Persistence\Category\Mapper\CategoryMapper;
use App\Models\Category as CategoryModel;

final class CategoryRepositoryImpl implements CategoryRepositoryInterface
{
    public function __construct(private CategoryMapper $mapper){}

    public function findById(int $id): ?CategoryEntity
    {
        $model = CategoryModel::find($id);
        return $model ? $this->mapper->toEntity($model) : null;
    }
    public function findBySlug(CategorySlug $slug): ?CategoryEntity
    {
        $model = CategoryModel::where('slug', $slug->value())->first();
        return $model ? $this->mapper->toEntity($model): null;
    }
    public function save(CategoryEntity $category): CategoryEntity
    {
        $model = $this->mapper->toModel($category);
        $model->save();
        return $this->mapper->toEntity($model);
    }
    public function delete(CategoryEntity $category): void
    {
        CategoryModel::destroy($category->getId());
    }
    public function findAll(): array
    {
        return CategoryModel::all()
            ->map(fn (CategoryModel $model) => $this->mapper->toEntity($model))->toArray();
    }
}
