<?php
/**
 * Created by PhpStorm.
 * User: songyaofeng
 * Date: 2018/9/18
 * Time: 14:50
 */

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Cache;

class Article extends Model
{
    protected $table = 'articles';

    public function articleList(int $page, int $pagesize, $word = '')
    {
        $articles = $this
            ->select('articles.id', 'articles.title', 'articles.html', 'articles.click', 'articles.created_at', 'c.category_name')
            ->leftJoin('categories as c', 'c.id', '=', 'articles.category_id')
            ->when(!empty($word), function ($query) use ($word) {
                return $query->where('title', 'like', '%' . $word . '%');
            })
            ->offset($this->setOffset($page, $pagesize))
            ->limit($pagesize)
            ->orderBy('id', 'desc')
            ->get();
        foreach($articles as &$article) {
            $createdAt = Carbon::parse($article->created_at)->toDateTimeString();
            $article->insert_at = Carbon::parse($createdAt)->diffForHumans();
        }
        return $articles;
    }


    public function setOffset(int $page, $pagesize)
    {
        return ($page - 1) * $pagesize;
    }

    public function getArticleById(int $articleId)
    {
        $data = self::find($articleId);
        $data->increment('click');
        $articleTag = new ArticleTag();
        $tag = $articleTag->getTagNameByArticleIds([$articleId]);
        // 处理标签可能为空的情况
        $data['tags'] = empty($tag) ? [] : current($tag);
        $createdAt = Carbon::parse($data->created_at)->toDateTimeString();
        $data->insert_at = Carbon::parse($createdAt)->diffForHumans();
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

//    public function getCreatedAtAttribute($date) {
//        if (Carbon::now() > Carbon::parse($date)->addDays(15)) {
//            return Carbon::parse($date);
//        }
//
//        return Carbon::parse($date)->diffForHumans();
//    }

}