<?php
/**
 * Created by PhpStorm.
 * User: songyaofeng
 * Date: 2018/10/19
 * Time: 11:22
 */

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function hotTags(Tag $tag)
    {
        $hotTags = $tag->hotArticleTags();
        return response()->json($hotTags, 200);
    }

    public function test(Request $request)
    {
        $params = $request->all();
        //对数组按键名排列
        ksort($params, SORT_STRING);
        //待加密字符串
        $string = '';
        foreach ($params as $k => $v) {
            $string .= $k;
            $string .= '=';
            $string .= is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : $v;
            $string .= '&';
        }
        $string = trim($string, '&');
        return md5($string . 'SyFHwy');

    }
}