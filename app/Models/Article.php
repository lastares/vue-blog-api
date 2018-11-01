<?php
/**
 * Created by PhpStorm.
 * User: songyaofeng
 * Date: 2018/9/18
 * Time: 14:50
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Cache;

class Article extends Model
{
    protected $table = 'articles';

    public function articleList(int $page, int $pagesize, $word = '')
    {
        $articles = $this
            ->select('articles.id', 'articles.title', 'articles.html', 'articles.click', 'c.category_name')
            ->leftJoin('categories as c', 'c.id', '=', 'articles.category_id')
            ->when(!empty($word), function ($query) use ($word) {
                return $query->where('title', 'like', '%' . $word . '%');
            })
            ->offset($this->setOffset($page, $pagesize))
            ->limit($pagesize)
            ->orderBy('id', 'desc')
            ->get();
        return $articles;
    }


    public function setOffset(int $page, $pagesize)
    {
        return ($page - 1) * $pagesize;
    }

    public function getArticleById(int $articleId)
    {
        $data = self::find($articleId);
        $articleTag = new ArticleTag();
        $tag = $articleTag->getTagNameByArticleIds([$articleId]);
        // 处理标签可能为空的情况
        $data['tags'] = empty($tag) ? [] : current($tag);
        return $data;
    }

    public function hotArticle()
    {
        $topArticle = Cache::remember('common:topArticle', 10080, function () {
            // 获取置顶推荐文章
            return Article::select('id', 'title', 'click', 'cover', 'description', 'created_at')
                ->orderBy('click', 'desc')
                ->limit(10)
                ->get();
        });

        return $topArticle;
    }

    public function randomArticle()
    {
        $minArticleId = self::min('id');
        $maxArticleId = self::max('id');
        $randomArticleIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $randomArticleId = mt_rand($minArticleId, $maxArticleId);
            $randomArticleIds[] = $randomArticleId;
        }

        $randomArticle = $this->whereIn('id', $randomArticleIds)->get();
        return $randomArticle;
    }
}