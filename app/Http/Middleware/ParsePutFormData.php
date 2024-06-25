<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

class ParsePutFormData
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('PUT') && strpos($request->header('Content-Type'), 'multipart/form-data') !== false) {
            // FormData 파싱 로직
            $data = [];
            $input = file_get_contents('php://input');
            preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
            $boundary = $matches[1];

            $blocks = preg_split("/-+$boundary/", $input);
            array_pop($blocks);

            foreach ($blocks as $block) {
                if (empty(trim($block))) continue;

                if (strpos($block, 'application/octet-stream') !== FALSE) {
                    preg_match("/name=\"([^\"]*)\"; filename=\"([^\"]*)\"[\n|\r]+Content-Type: [\w+\/-]+[\n|\r]+[\n|\r](.*)$/s", $block, $matches);
                    $data[$matches[1]] = $matches[3];
                } else {
                    preg_match('/name=\"([a-zA-z0-9_]*)\"[\n|\r]+[\n|\r](.*)[\n|\r]$/s', $block, $matches);
                    $data[$matches[1]] = $matches[2];
                }
            }
            $request->merge($data);
        }
        return $next($request);
    }
}
