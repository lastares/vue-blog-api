<?php
/**
 * Created by PhpStorm.
 * User: songyaofeng
 * Date: 2018/9/18
 * Time: 14:39
 */

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Article $article, Request $request)
    {
        $page = $request->input('page', 1);
        $pagesize = $request->input('pagesize', 40);
        $word = $request->input('word', '');
        $articles = $article->articleList($page, $pagesize, $word);
        return response()->json($articles, 200);
    }

    public function show(Request $request, Article $article)
    {
        $articleId = $request->input('article_id');
        $article = $article->getArticleById($articleId);
        return response()->json($article, 200);
    }

    public function getArticleByCateId(Request $request)
    {
        $categoryId = $request->input('category_id', '');
        $articles = Article::where('category_id', $categoryId)->orderBy('id', 'desc')->limit(40)->get();
        return response()->json($articles, 200);
    }

    public function hotArticle(Article $article)
    {
        $hotArticle = $article->hotArticle();
        return response()->json($hotArticle, 200);
    }

    public function randomArticle(Article $article)
    {
        $randomArticle = $article->randomArticle();
        return response()->json($randomArticle, 200);
    }

}