<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\Rooms;
use Phalcon\Mvc\Model;

class Houses extends Model
{
    public ?int $id = null;
    public string $street;
    public int $number;
    public ?string $addition = null;
    public string $zipcode;
    public string $city;
    public string $user;

    public function initialize(): void
    {
        $this->hasMany(
            'id',
            Rooms::class,
            'id_house',
            ['alias' => 'rooms']);
    }


    public static function fillHouse(array $data, $house = null): Houses
    {
        if($house === null) {
            $house = new self();
            $house->user = $data['user'];
        }

        $house->street = $data['street'];
        $house->number = $data['number'];
        $house->zipcode = $data['zipcode'];
        $house->city = $data['city'];
        if(isset($data['addition'])) {
            $house->addition = $data['addition'];
        }

        return $house;
    }
}