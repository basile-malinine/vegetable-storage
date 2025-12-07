<?php

namespace app\models;

use app\models\SystemObject\SystemObject;
use Google\Exception;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ClearValuesRequest;
use Google_Service_Sheets_ValueRange;
use yii\base\Model;

class GoogleBase extends Base
{
    private static Google_Service_Sheets $service;

    protected static function initGoogleSheet(): void
    {
        $configPath = \Yii::getAlias('@app/google-auth.json');
        $client = new Google_Client();

        $client->setApplicationName('Google Sheets API');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig($configPath);
        self::$service = new Google_Service_Sheets($client);
    }

    // Проверка наличия / доступности Google таблицы
    public static function testGoogleSpreadsheet($spreadsheetId): array
    {
        self::initGoogleSheet();

        $title = '';
        try {
            $response = self::$service->spreadsheets->get($spreadsheetId);
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

    // Обновляет данные на Google листе
    public static function updateGoogleSheet(self $classModel): array
    {
        $systemObjectModel = SystemObject::findOne(['table_name' => $classModel::tableName()]);
        $links = $systemObjectModel->systemObjectGoogleSheets;

        self::initGoogleSheet();

        $values = $classModel::getListForGoogle();

        $errors = [];
        foreach ($links as $link) {
            try {
                $clearRange = new Google_Service_Sheets_ClearValuesRequest();
                $valueRange = new Google_Service_Sheets_ValueRange();
                $valueRange->setMajorDimension('COLUMNS');
                $valueRange->setValues([$values]);

                $options = ['valueInputOption' => 'USER_ENTERED'];
                self::$service->spreadsheets_values
                    ->clear($link->googleSheet->sheet_id, $link->google_sheet_range, $clearRange);
                self::$service->spreadsheets_values
                    ->update($link->googleSheet->sheet_id, $link->google_sheet_range, $valueRange, $options);
            } catch (\Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        return $errors;
    }

    // Возвращает технический аккаунт
    public static function getAccount()
    {
        $configPath = \Yii::getAlias('@app/google-auth.json');
        $json = file_get_contents($configPath);
        $array = json_decode($json, true);

        return $array['client_email'];
    }

    private static function getListForGoogle(): array
    {
        return array_values(self::getList());
    }
}