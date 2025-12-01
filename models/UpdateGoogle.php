<?php

namespace app\models;

use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ClearValuesRequest;
use Google_Service_Sheets_ValueRange;

class UpdateGoogle
{
    private $clearRange;
    private $valueRange;
    private $service;
    private $range;

    public function __construct(string $range, array $values)
    {
        $configPath = \Yii::getAlias('@app/google-auth.json');
        $client = new Google_Client();

        $client->setApplicationName('Google Sheets API');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig($configPath);
        $this->service = new Google_Service_Sheets($client);

        $this->range = $range;
        $this->clearRange = new Google_Service_Sheets_ClearValuesRequest();
        $this->valueRange = new Google_Service_Sheets_ValueRange();
        $this->valueRange->setMajorDimension('COLUMNS');
        $this->valueRange->setValues([$values]);
    }

    public function update(string $spreadsheetId): void
    {
        $options = ['valueInputOption' => 'USER_ENTERED'];
        $this->service->spreadsheets_values->clear($spreadsheetId, $this->range, $this->clearRange);
        $this->service->spreadsheets_values->update($spreadsheetId, $this->range, $this->valueRange, $options);
    }
}