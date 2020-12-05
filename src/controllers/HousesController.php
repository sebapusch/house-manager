<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\KeyNotFoundException;
use App\Models\Houses;
use App\Models\Rooms;
use Phalcon\Mvc\Controller;

class HousesController extends Controller
{
    public function view()
    {

    }

    public function viewById(int $id)
    {

    }

    public function insert()
    {
        $rawData = $this->request->getJsonRawBody(true);
        if(false === isset($rawData)) {
            $rawData = [];
        }

        try {
            $data = $this->sanitize->getSanitized(
                $rawData,
                ['street' => 'string', 'number' => 'int', 'zipcode' => 'string', 'city' => 'string']
            );
        } catch (KeyNotFoundException $e) {
            return $this->response
                ->badRequest($e->getMessage());
        }

        $data['user'] = $this->loggedUser->getUsername();

        if(isset($rawData['addition']))
        {
            $data['addition'] = $this->filter->sanitize($rawData['addition'], 'string');
        }

        $house = Houses::fillHouse($data);

        $rooms = [];
        if(isset($rawData['rooms'])) {
            foreach ($rawData['rooms'] as $rawRoomData) {
                try {
                    $roomData = $this->sanitize->getSanitized(
                        $rawRoomData,
                        ['type' => 'string', 'width' => 'int', 'length' => 'int', 'height' => 'length']
                    );
                } catch (KeyNotFoundException $e) {
                    continue;
                }

                $rooms[] = Rooms::fillRoom($roomData);
            }
            $house->rooms = $rooms;
        }

        if(false === $house->save()) {
            $messages = '';
            foreach ($house->getMessages() as $message) {
                $messages .= ($messages===''?'':', ') . $message->getMessage();
            }
            return $this->response
                ->badRequest($messages);
        }

        return $this->response
            ->ok([$house, 'rooms' => $house->rooms]);
    }

    public function updateById(int $id)
    {

    }

    public function deleteById(int $id)
    {
        $house = Houses::findFirstById($id);

        if(empty($house)) {
            return $this->response
                ->notFound();
        }

        if(false === $this->loggedUser->isAdmin() && $house->user !== $this->loggedUser->getUsername()) {
            return $this->response
                ->unauthorized('You are not authorized to delete this house');
        }

        if(false === $house->delete()) {
            $messages = '';
            foreach ($house->getMessages() as $message) {
                $messages .= ($messages===''?'':', ') . $message->getMessage();
            }
            return $this->response
                ->badRequest($messages);
        }

        return $this->response
            ->ok([$house]);
    }

}