<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ParsePutFormData
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('PUT')) {
            $this->parsePutRequest($request);
        }

        return $next($request);
    }

    protected function parsePutRequest(Request $request)
    {
        $contentType = $request->header('Content-Type');
        if (strpos($contentType, 'multipart/form-data') !== false) {
            $boundary = $this->extractBoundary($contentType);
            if ($boundary) {
                $this->parseMultipartFormData($request, $boundary);
            }
        }
    }

    protected function extractBoundary($contentType)
    {
        $matches = []; // matches 배열 초기화
        if (preg_match('/boundary=(.*)$/', $contentType, $matches)) { // 정규 표현식 결과를 matches 배열에 저장
            return $matches[1]; // 경계 문자열 반환
        }
        return null; // 경계 문자열이 없는 경우 null 반환
    }

    protected function parseMultipartFormData(Request $request, $boundary)
    {
        $input = file_get_contents('php://input');
        $blocks = preg_split("/-+$boundary/", $input);
        array_pop($blocks); // 마지막 빈 블록 제거

        foreach ($blocks as $block) {
            if (empty(trim($block))) {
                continue;
            }

            if (strpos($block, 'filename=') !== false) {
                $this->extractFile($block, $request);
            } else {
                $this->extractData($block, $request);
            }
        }
    }

    protected function extractFile($block, Request $request)
    {
        if (preg_match('/name="([^"]+)"; filename="([^"]+)"[\r\n]+Content-Type: ([\w\/]+)[\r\n]+[\r\n](.*)[\r\n]--$/s', $block, $matches)) {
            $name = $matches[1];
            $filename = $matches[2];
            $contentType = $matches[3];
            $fileContent = $matches[4];

            $tmpFilePath = tempnam(sys_get_temp_dir(), 'PUT');
            file_put_contents($tmpFilePath, $fileContent);

            $file = new UploadedFile(
                $tmpFilePath,
                $filename,
                $contentType,
                null,
                true
            );

            $request->files->set($name, $file);
        }
    }

    protected function extractData($block, Request $request)
    {
        if (preg_match('/name="([^"]+)"[\r\n]+[\r\n](.*)[\r\n]--$/s', $block, $matches)) {
            $name = $matches[1];
            $data = $matches[2];
            $request->request->set($name, $data);
        }
    }
}
