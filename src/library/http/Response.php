<?php
declare(strict_types=1);

namespace App\Http;

use Phalcon\Http\Response as PhResponse;
use Phalcon\Http\ResponseInterface;

class Response extends PhResponse
{

    /**
     * @return ResponseInterface
     */
    public function notFound() : ResponseInterface
    {
        return $this->setStatusCode(404, 'Not found')
            ->setJsonContent([]);
    }

    /**
     * @param string $message
     * @return ResponseInterface
     */
    public function badRequest(string $message) : ResponseInterface
    {
        return $this->setStatusCode(400, 'Bad request')
            ->setJsonContent(['error' => $message]);
    }

    /**
     * @param $content
     * @return ResponseInterface
     */
    public function ok($content) : ResponseInterface
    {
        return $this->setStatusCode(200, 'OK')
            ->setJsonContent($content);
    }

    /**
     * @param string $message
     * @return ResponseInterface
     */
    public function unauthorized(string $message) : ResponseInterface
    {
        return $this->setStatusCode(401, 'Unauthorized')
            ->setJsonContent(['error' => $message]);
    }
}