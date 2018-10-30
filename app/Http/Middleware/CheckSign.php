<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSign
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $sign = $request->input('sign', '');
        if(empty($sign)) {
            return response()->json(['msg' => 'sign not exists', 'code' => 10010]);
        }
        $params = $request->all();
        $serveSign = $this->getSign($params);
        if($sign !== $serveSign) {
            return response()->json(['msg' => 'sign illegle', 'code' => 10011]);
        }

        return $next($request);
    }

    public function getSign($params)
    {
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
        $saltString = $string . 'SyFHwy';
        return md5($saltString);
    }
}
