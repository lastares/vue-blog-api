<?php
/**
 * Created by PhpStorm.
 * User: songyaofeng
 * Date: 2018/9/18
 * Time: 16:31
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    public function index()
    {
        return $this->orderBy('id', 'asc')->get();
    }
}