<?php
/**
 * Created by PhpStorm.
 * User: AOC
 * Date: 2018/4/27
 * Time: 11:18
 */

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;

/**
 * 允许跨域中间件
 *
 * Class CorsMiddleware
 * @package App\Http\Middleware
 */
class CheckCors
{
    public function handle($request, \Closure $next){
        if($request->method() == 'OPTIONS'){
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', 'localhost:8080');
            $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, multipart/form-data, application/json, timestamp, nonce, token, signature, retoken');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
            $response->headers->set('Access-Control-Max-Age', '1728000');
            return $response;
        }

        $response = $next($request);
        //允许请求来源ip
        if($response instanceof \Illuminate\Http\Response){
            $response->header('Access-Control-Allow-Origin', 'localhost:8080');
            //允许请求的header头
            $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, multipart/form-data, application/json, sign');
            //允许请求的方法
            $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
        }
        return $response;

    }

}