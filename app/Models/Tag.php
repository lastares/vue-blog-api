<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends BaseModel
{
    use SoftDeletes;
    protected $table = 'tags';

    public function hotArticleTags()
    {
//        $prefix = config('database.connections.mysql.prefix');
        $data = $this->select(\DB::raw('tags.*, count(article_tags.article_id) as article_count'))
            ->join('article_tags as at', 'at.tag_id', 'tags.id')
            ->rightJoin('articles as a', 'a.id', 'at.article_id')
            ->where('a.deleted_at', null)
            ->orderBy('tags.id', 'desc')
            ->groupBy('tags.id')
            ->get();
        return $data;
    }

}
