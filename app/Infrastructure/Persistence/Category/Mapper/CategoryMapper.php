<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Category\Mapper;

use App\Domain\Category\Entities\CategoryEntity;
use App\Domain\Category\ValueObjects\CategoryName;
use App\Domain\Category\ValueObjects\CategorySlug;
use App\Models\Category as CategoryModel;

class CategoryMapper
{
    public function toEntity(CategoryModel $model):CategoryEntity
    {
        return CategoryEntity::fromPersistence(
            id: $model->id,
            name: new CategoryName($model->name),
            slug: new CategorySlug($model->slug),
            status: $model->status
        );
    }

    public function toModel(CategoryEntity $entity):CategoryModel
    {
        $model = CategoryModel::find($entity->getId()) ?? new CategoryModel();
        $model->name = $entity->getName()->value();
        $model->slug = $entity->getSlug()->value();
        $model->status = $entity->isActive();
        return $model;
    }
}
