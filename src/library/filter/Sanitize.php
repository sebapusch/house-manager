<?php
declare(strict_types=1);

namespace App\Filter;


use App\Exceptions\KeyNotFoundException;
use Phalcon\Di;

class Sanitize
{

    /**
     * Sanitizes given data with the given keys/types
     *
     * If a given key does not exist in the data an exception is thrown
     * @param array $data
     * @param array $fields
     * @return array
     * @throws KeyNotFoundException
     */
    public function getSanitized($data, array $fields) : array
    {
       $container = Di::getDefault();
       $errorMessage = '';
       $sanitizedData = [];
       $filter = $container->get('filter');

       if($data === null) {
           $data = [];
       }

        foreach ($fields as $key => $type) {
            if(empty($data[$key])) {
                $errorMessage .= ($errorMessage===''?'':', ') . $key . ' not set';
            } else {
                $sanitizedData[$key] = $filter->sanitize($data[$key], $type);
            }
        }

        if('' !== $errorMessage) {
            throw new KeyNotFoundException($errorMessage);
        }

        return $sanitizedData;
    }
}