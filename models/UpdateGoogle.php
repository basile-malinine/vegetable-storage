<?php

namespace app\models;

use Google\Exception;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ClearValuesRequest;
use Google_Service_Sheets_ValueRange;

class UpdateGoogle
{
    private Google_Service_Sheets $service;

    public function __construct()
    {
        $configPath = \Yii::getAlias('@app/google-auth.json');
        $client = new Google_Client();

        $client->setApplicationName('Google Sheets API');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig($configPath);
        $this->service = new Google_Service_Sheets($client);
    }

    public function update(string $spreadsheetId, string $range, $values): void
    {
        if ($range && $values) {
            $clearRange = new Google_Service_Sheets_ClearValuesRequest();
            $valueRange = new Google_Service_Sheets_ValueRange();
            $valueRange->setMajorDimension('COLUMNS');
            $valueRange->setValues([$values]);

            $options = ['valueInputOption' => 'USER_ENTERED'];
            $this->service->spreadsheets_values->clear($spreadsheetId, $range, $clearRange);
            $this->service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);
        }
    }

    public function testSpreadsheet($spreadsheetId)
    {
        $title = '';
        try {
            $response = $this->service->spreadsheets->get($spreadsheetId);
            $title = $response->getProperties()->title;
        } catch (Exception $exception) {
            $msg = json_decode($exception->getMessage());

            return [
                'errorCode' => $msg->error->code,
                'title' => $title
            ];
        }

        return [
            'errorCode' => 0,
            'title' => $title
        ];
    }

    // Возвращает технический аккаунт
    public static function getAccount()
    {
        $configPath = \Yii::getAlias('@app/google-auth.json');
        $json = file_get_contents($configPath);
        $array = json_decode($json, true);

        return $array['client_email'];
    }
}