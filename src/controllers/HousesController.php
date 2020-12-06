<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\KeyNotFoundException;
use App\Http\Response;
use App\Models\Houses;
use App\Models\Rooms;
use Phalcon\Mvc\Controller;

class HousesController extends Controller
{
    public function view()
    {
        $filerValues = $this->request->getFilterValues(
            ['search' => 'string', 'minimalBedroomsCount' => 'int', 'minimalToiletCount' => 'int']
        );

        $findParams = [];
        if(isset($filerValues['search'])) {
            $findParams = $this->prepareFindParams($filerValues['search']);
        }

        $houses = Houses::find($findParams);

        if(isset($filerValues['minimalBedroomsCount']) ||  isset($filerValues['minimalToiletCount'])) {
            $houses = $houses->filter(function ($house) use ($filerValues) {
                $bedrooms = 0;
                $toilets = 0;
                foreach ($house->rooms as $room) {
                    if ($room->type === 'bedroom') {
                        $bedrooms++;
                    } else if ($room->type === 'toilet') {
                        $toilets++;
                    }
                }
                if(isset($filerValues['minimalBedroomsCount']) && $bedrooms < $filerValues['minimalBedroomsCount']) {
                    return false;
                }
                if(isset($filerValues['minimalToiletCount']) && $toilets < $filerValues['minimalToiletCount']) {
                    return false;
                }
                return $house;
            });
        }


        if(empty($houses)) {
            return $this->response
                ->notFound();
        }

        return $this->response
            ->ok(['houses' => $houses]);
    }

    public function viewById(int $id)
    {
        $house = Houses::findFirstById($id);
        $rooms = [];

        if(empty($house)) {
            return $this->response
                ->notFound();
        }

        if(false === empty($house->rooms)) {
            $rooms = $house->rooms;
        }

        return $this->response
            ->ok(['house' => $house, 'rooms' => $rooms]);
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

    /**
     * Update house and rooms corresponding to given house id
     * @todo sometimes doesn't respond as expected
     * @param int $id
     * @return Response
     */
    public function updateById(int $id)
    {
        $house = Houses::findFirstById($id);

        if(empty($house)) {
            return $this->response
                ->notFound();
        }

        if(false === $this->loggedUser->isAdmin() && $house->user !== $this->loggedUser->getUsername()) {
            return $this->response
                ->unauthorized('You are not authorized to update this house');
        }

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

        if(isset($rawData['addition']))
        {
            $data['addition'] = $this->filter->sanitize($rawData['addition'], 'string');
        }

        $house = Houses::fillHouse($data, $house);

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

    /**
     * Prepares the search param array based on the search value
     * @param string $searchVal
     * @return array
     */
    private function prepareFindParams(string $searchVal) : array
    {
        $params = [
            'conditions' =>  'street LIKE :search: OR ' .
                            'addition LIKE :search: OR ' .
                            'city LIKE :search: OR ' .
                            'zipcode LIKE :search: OR ' .
                            'number LIKE :search:',
            'bind' => [
                'search' => "%$searchVal%"
            ]
        ];

        return $params;
    }
}