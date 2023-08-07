<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ForecastController extends Controller
{

    /**
     * Display the specified resource.
     * @param Request $request
     * @return string
     */
    public function show(Request $request) :string
    {
        $unitsForApi = $this->getUnitsForApi($request->input('units'));

        $forecastData = Http::get('https://api.openweathermap.org/data/2.5/forecast', [
            'q' => $request->input('city'),
            'units' => $unitsForApi,
            'lang' => 'ru',
            'appid' => config('app.api_key')
        ]);

        $httpResponseCode = $forecastData->status();

        if ($httpResponseCode != 200) {
            return \response($forecastData->body())->setStatusCode($httpResponseCode);
        }

        $modifiedForecastData = $this->modifyData($forecastData);
        return response()->json($modifiedForecastData)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    private function modifyData(Response $response) :array
    {
        $data = $response->json()['list'][0];

        $temp = round($data['main']['temp']);
        $description = $data['weather'][0]['description'];
        $humidity = $data['main']['humidity'];
        $atmosphericPressure = round($data['main']['pressure'] * 0.750063755419210632903796822729932044);
        $windSpeed = round($data['wind']['speed']);
        $windDirection = $this->getWindDirection($data['wind']['deg']);
        $precipitationProb = ($data['pop'] * 100);

        return [
            'description' => $description,
            'temperature' => $temp,
            'humidity' => $humidity,
            'pressure' => $atmosphericPressure,
            'wind' => [
              'speed' => $windSpeed,
              'direction' => $windDirection
            ],
            'precipitations' => $precipitationProb
        ];
    }

    private function getUnitsForApi(string $userUnits) :string
    {
        return match ($userUnits) {
            'celsius' => 'metric',
            'fahrenheit' => 'imperial'
        };
    }

    private function getWindDirection(float $windDeg) :string
    {
        $direction = '';

        if ( $windDeg > 337.5 || $windDeg <= 22.5) {
            $direction = 'северный';
        }

        if ($windDeg > 22.5 && $windDeg < 67.5) {
            $direction = 'восточный';
        }

        if ($windDeg > 112.5 && $windDeg < 157.5) {
            $direction = 'юго-восточный';
        }

        if ($windDeg > 157.5 && $windDeg < 202.5) {
            $direction = 'южный';
        }

        if ($windDeg > 202.5 && $windDeg < 247.5) {
            $direction = 'юго-западный';
        }

        if ($windDeg > 247.5 && $windDeg < 292.5) {
            $direction = 'западный';
        }

        if ($windDeg > 292.5 && $windDeg < 337.5) {
            $direction = 'северо-западный';
        }

        return $direction;
    }

}
