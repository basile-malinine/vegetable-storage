<?php

namespace app\models;

use Google\Exception;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ClearValuesRequest;
use Google_Service_Sheets_ValueRange;

class UpdateGoogle
{
    private Google_Service_Sheets_ClearValuesRequest $clearRange;
    private Google_Service_Sheets_ValueRange $valueRange;
    private Google_Service_Sheets $service;
    private string $range;

    public function __construct(string $range = null, array $values = null)
    {
        $configPath = \Yii::getAlias('@app/google-auth.json');
        $client = new Google_Client();

        $client->setApplicationName('Google Sheets API');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig($configPath);
        $this->service = new Google_Service_Sheets($client);

        if ($range && $values) {
            $this->range = $range;
            $this->clearRange = new Google_Service_Sheets_ClearValuesRequest();
            $this->valueRange = new Google_Service_Sheets_ValueRange();
            $this->valueRange->setMajorDimension('COLUMNS');
            $this->valueRange->setValues([$values]);
        }
    }

    public function update(string $spreadsheetId): void
    {
        $options = ['valueInputOption' => 'USER_ENTERED'];
        $this->service->spreadsheets_values->clear($spreadsheetId, $this->range, $this->clearRange);
        $this->service->spreadsheets_values->update($spreadsheetId, $this->range, $this->valueRange, $options);
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