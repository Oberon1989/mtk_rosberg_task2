<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Episode;
use App\Models\Location;
//use http\Env\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
//use Illuminate\Database\Eloquent\Model;
//use JetBrains\PhpStorm\NoReturn;
//use Laravel\Prompts\Table;
//use Illuminate\Support\Facades\Log;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

ini_set('max_execution_time', 3600);

class Controller extends \Illuminate\Routing\Controller
{
    public function init(): \Illuminate\Http\JsonResponse
    {
        /*

        //$start = microtime(true);
        //dump("получение всех персонажей");
        $characters = $this->getAllPagesFromUrl('https://rickandmortyapi.com/api/character');
        //$end = microtime(true);
        //dump("время получения всех персонажей: " . round($end - $start, 2) . " seconds");


       // $start = microtime(true);
        //dump("получаем локации");
        $locations = $this->getAllPagesFromUrl('https://rickandmortyapi.com/api/location');
        //$end = microtime(true);
        //dump("время получения всех локаций: " . round($end - $start, 2) . " seconds");


        //$start = microtime(true);
        //dump("получаем эпизоды");
        $episodes = $this->getAllPagesFromUrl('https://rickandmortyapi.com/api/episode');
       // $end = microtime(true);
        //dump("время получения эпизодов: " . round($end - $start, 2) . " seconds");

        DB::transaction(function () use ($characters, $locations, $episodes) {

            //$start = microtime(true);
           // dump("добавление локаций в бд");
            $locationDataArray = [];
            foreach ($locations as $locationData) {
                $locationDataArray[] = [
                    'id' => $locationData['id'],
                    'name' => $locationData['name'],
                    'type' => $locationData['type'],
                    'dimension' => $locationData['dimension'],
                    'url' => $locationData['url'],
                ];
            }
            Location::upsert($locationDataArray, ['id']);
           // $end = microtime(true);
            //dump("время вставки всех локаций: " . round($end - $start, 2) . " seconds");


            //$start = microtime(true);
           // dump("вставляем эпизоды");
            $episodeDataArray = [];
            foreach ($episodes as $episodeData) {
                $episodeDataArray[] = [
                    'id' => $episodeData['id'],
                    'name' => $episodeData['name'],
                    'air_date' => $episodeData['air_date'],
                    'episode_code' => $episodeData['episode'],
                    'url' => $episodeData['url'],
                ];
            }
            Episode::upsert($episodeDataArray, ['id']);
           // $end = microtime(true);
            //dump("время вставки всех эпизодов: " . round($end - $start, 2) . " seconds");


            //$start = microtime(true);
           // dump("вставляем персонажей");
            $characterDataArray = [];
            foreach ($characters as $characterData) {
                $originId = null;
                if (!empty($characterData['origin']['url'])) {
                    $originLocation = Location::where('url', $characterData['origin']['url'])->first();
                    if ($originLocation) {
                        $originId = $originLocation->id;
                    }
                }

                $locationId = null;
                if (!empty($characterData['location']['url'])) {
                    $currentLocation = Location::where('url', $characterData['location']['url'])->first();
                    if ($currentLocation) {
                        $locationId = $currentLocation->id;
                    }
                }

                $characterDataArray[] = [
                    'id' => $characterData['id'],
                    'name' => $characterData['name'],
                    'status' => $characterData['status'],
                    'species' => $characterData['species'],
                    'type' => $characterData['type'],
                    'gender' => $characterData['gender'],
                    'origin_id' => $originId,
                    'location_id' => $locationId,
                    'image' => $characterData['image'],
                    'url' => $characterData['url'],
                ];
            }
            Character::upsert($characterDataArray, ['id']);
           // $end = microtime(true);
           // dump("время вставки персонажей: " . round($end - $start, 2) . " seconds");


            //$start = microtime(true);
            foreach ($characters as $characterData) {
                $character = Character::find($characterData['id']);
                $episodeUrls = $characterData['episode'];
                $episodeIds = Episode::whereIn('url', $episodeUrls)->pluck('id')->toArray();
                $character->episodes()->sync($episodeIds);
            }
            //$end = microtime(true);
           // dump("время настроойки связей: " . round($end - $start, 2) . " seconds");

        });
        */

        $characters_count = Character::count();
        $locations_count = Location::count();
        $episodes_count = Episode::count();
        return response()->json(['characters_count' => $characters_count,'locations_count'=>$locations_count,
            'episodes_count'=>$episodes_count], 200);
    }

    function getFirstCharacter(){
        $character = Character::with(['origin', 'location', 'episodes'])->find(1);
        if($character){
            return response()->json($character);
        }
        else{
            return response()->json(['status' => 'character not found'], 404);
        }
    }

    public function getCharacterById($id)
    {
        $character = Character::with(['origin', 'location', 'episodes'])->find($id);

        if (!$character) {
            return response()->json(['error' => 'Персонаж не найден.'], 404);
        }
        $maxId=Character::count();
        return view('character', compact('character','maxId'));
    }






    //метод для получения всех страниц сущностей с api
    function getAllPagesFromUrl($url): array
    {
        $data = [];
        do {
            $response = Http::get($url);
            if ($response->successful()) {
                $jsonData = $response->json();
                $data = array_merge($data, $jsonData['results']);
                $url = $jsonData['info']['next'];
            } else {
                $url = null;
            }
        } while ($url);

        return $data;
    }

    public function exportCharacterByIdToExcel($id)
    {
        $character = Character::with(['origin', 'location', 'episodes'])->find($id);

        if($character){

            $this->exportCharacterToExcel($character);
        }
        else{
            return response()->json(['status' => 'failed','message'=>'character with'.$id.'not found'],200);
        }



    }

    public function exportCharacterToExcel($character)
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $sheet->setCellValue('A1', 'Имя персонажа');
        $sheet->setCellValue('B1', 'Статус');
        $sheet->setCellValue('C1', 'Вид');
        $sheet->setCellValue('D1', 'Пол');
        $sheet->setCellValue('E1', 'Название локации');
        $sheet->setCellValue('F1', 'URL локации');
        $sheet->setCellValue('G1', 'Название эпизода');
        $sheet->setCellValue('H1', 'Дата эпизода');


        $sheet->setCellValue('A2', $character->name);
        $sheet->setCellValue('B2', $character->status);
        $sheet->setCellValue('C2', $character->species);
        $sheet->setCellValue('D2', $character->gender);
        $sheet->setCellValue('E2', $character->location->name ?? 'Неизвестно');
        $sheet->setCellValue('F2', $character->location->url ?? 'Неизвестно');


        $row = 2;
        foreach ($character->episodes as $episode) {
            $sheet->setCellValue("G{$row}", $episode->name);
            $sheet->setCellValue("H{$row}", $episode->air_date);
            $row++;
        }


        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }


        $writer = new Xlsx($spreadsheet);
        $fileName = "character_{$character->id}.xlsx";


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

}
