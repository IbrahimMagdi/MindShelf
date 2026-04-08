<?php
namespace App\Services\Categories;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryService
{
    public function getCategories(?string $search = null, bool $onlyActive = false)
    {
        $query = Category::query();

        // لو عاوزين المفعل بس (لليوزرز)
        if ($onlyActive) {
            $query->where('status', true);
        }

        // لو فيه بحث
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return $query->latest()->get();
    }

    public function create(array $data): Category
    {
        // توليد الـ slug أوتوماتيك من الاسم
        $data['slug'] = Str::slug($data['name']);
        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        // لو الاسم اتغير، نغير الـ slug معاه
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);
        return $category->fresh();
    }

    public function delete(Category $category): bool
    {
        // تريك: ممكن تشيك هنا لو الكاتيجري مرتبط بكتب، تمنع المسح عشان الداتا ما تضربش
        return $category->delete();
    }
}
