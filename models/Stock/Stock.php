<?php

namespace app\models\Stock;

use yii\db\ActiveRecord;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ClearValuesRequest;
use Google_Service_Sheets_ValueRange;

/**
 * This is the model class for table "stock".
 *
 * @property int $id ID
 * @property string $name Название
 * @property string|null $address Адрес
 * @property string|null $comment Комментарий
 *
 */
class Stock extends ActiveRecord
{
    public static function tableName()
    {
        return 'stock';
    }

    public function rules()
    {
        return [
            [['address', 'comment'], 'default', 'value' => null],
            [['name'], 'required'],
            [['comment'], 'string'],
            [['name'], 'string', 'max' => 30],
            [['address'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'address' => 'Адрес',
            'comment' => 'Комментарий',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->updateGoogle();
    }

    public function afterDelete()
    {
        $this->updateGoogle();
    }

    private function updateGoogle()
    {
        $configPath = \Yii::getAlias('@app/google-auth.json');
        $values = $this->getListForGoogle();

        $options = ['valueInputOption' => 'USER_ENTERED'];
        $client = new Google_Client();

        $client->setApplicationName('Google Sheets API');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig($configPath);
        $service = new Google_Service_Sheets($client);

        $range = 'DB!C4:C';
        $clearRange = new Google_Service_Sheets_ClearValuesRequest();
        $valueRange = new Google_Service_Sheets_ValueRange();
        $valueRange->setMajorDimension('COLUMNS');
        $valueRange->setValues([$values]);

        // Для таблицы Старший смены
//        $spreadsheetId = '1wzmRAhmt_PQufvNIzAsOvUnHfGCtzLuyx5UuncwdeNc';
//        $service->spreadsheets_values->clear($spreadsheetId, $range, $clearRange);
//        $service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);

        // Для таблицы Test Table Security
        $spreadsheetId = '1cr8nsLo9dq-f1n2Tw7rG2sqnS-TtyXoF-G9qfwPRZ4M';
        $service->spreadsheets_values->clear($spreadsheetId, $range, $clearRange);
        $service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);
    }

    // Список Складов
    public static function getList(): array
    {
        return self::find()
            ->select(['name', 'id'])
            ->indexBy('id')
            ->orderBy(['name' => SORT_ASC])
            ->column();
    }

    private function getListForGoogle(): array
    {
        return array_values(self::getList());
    }
}
