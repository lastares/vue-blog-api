<?php
/**
 * Created by PhpStorm.
 * User: songyaofeng
 * Date: 2018/9/18
 * Time: 16:32
 */

namespace App\Http\Controllers\Api\V1;


use App\Models\Category;

class CategoryController
{
    public function index(Category $category)
    {
        $categories = $category->index();
        return response()->json($categories, 200);
    }
}