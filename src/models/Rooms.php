<?php
declare(strict_types=1);

namespace App\Models;

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\InclusionIn;

class Rooms extends Model
{
    public ?int $id = null;
    public string $type;
    public int $width;
    public int $length;
    public int $height;
    public int $id_house;

    public function initialize() : void
    {
        $this->belongsTo(
            'id_house',
            Houses::class,
            'id',
            ['alias' => 'houses']
        );
    }

    /**
     * @return bool
     */
    public function validation() : bool
    {
        $validator = new Validation();
        $validator->add(
            'type',
            new InclusionIn([
                'domain' => [
                    'living room',
                    'bedroom',
                    'toilet',
                    'storage',
                    'bathroom'
                ]
            ])
        );
        return $this->validate($validator);
    }

    public static function fillRoom(array $data, array $room = null)
    {
        if($room === null) {
            $room = new self();
        }

        $room->type = $data['type'];
        $room->width = $data['width'];
        $room->length = $data['length'];
        $room->height = $data['height'];

        return $room;
    }
}