<?php
/**
 * Created by PhpStorm.
 * User: songyaofeng
 * Date: 2018/10/11
 * Time: 16:40
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ArticleTag extends Model
{
    protected $table = 'article_tags';
    /**
     * 传递一个文章id数组;获取标签名
     *
     * @param $ids
     * @return array
     */
    public function getTagNameByArticleIds($ids)
    {
        // 获取标签数据
        $tag = $this
            ->select('article_tags.article_id as id', 't.id as tag_id', 't.name')
            ->leftJoin('tags as t', 'article_tags.tag_id', '=', 't.id')
            ->whereIn('article_tags.article_id', $ids)
            ->get();
        $data = [];
        // 组合成键名是文章id 键值是 标签数组
        foreach ($tag as $k => $v) {
            $data[$v->id][] = $v;
        }
        return $data;
    }
}