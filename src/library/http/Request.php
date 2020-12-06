<?php
declare(strict_types=1);

namespace App\Http;

use Phalcon\Http\Request as PhRequest;

class Request extends PhRequest
{

    /**
     * Extract bearer token from authorization header
     * @return string
     */
    public function getBearerToken() : string
    {
        return str_replace('Bearer ', '', $this->getHeader('Authorization'));
    }

    /**
     * Check if bearer token in authorization header is empty
     * @return bool
     */
    public function isBearerTokenEmpty() : bool
    {
        return (true === empty($this->getBearerToken()));
    }

    /**
     * Check if request uri is login
     * @return bool
     */
    public function isLoginUri() : bool
    {
        return ('/login' === $this->getURI());
    }


    /**
     * Extracts sanitized filter values if they are set
     * @param array $filters
     * @return array
     */
    public function getFilterValues(array $filters) : array
    {
        $values = [];

        foreach ($filters as $key => $type) {
            $value = $this->getQuery($key, $type);

            if(false === empty($value)) {
                $values[$key] = $value;
            }
        }

        return $values;
    }
}