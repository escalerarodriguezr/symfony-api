<?php

declare(strict_types=1);

namespace App\Http\Transformer;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequestBodyTransformer
{
    public function transform(Request $request)
    {

        switch ($request->headers->get('Content-Type')) {
            case 'application/json':
                $data = \json_decode($request->getContent(), true);
                if(!empty($data)){
                    $request->request = new ParameterBag($data);
                }
                break;
            default:
                if(!str_contains($request->headers->get('Content-Type'), 'multipart/form-data')){
                    throw new BadRequestHttpException('Invalid Content-Type');
                }
                $data = \json_decode($request->getContent(), true);
                if(!empty($data)){
                    $request->request = new ParameterBag($data);
                }
                break;
        }
    }
}