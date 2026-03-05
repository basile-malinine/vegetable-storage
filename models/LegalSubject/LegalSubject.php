<?php

namespace app\models\LegalSubject;

use app\models\Base;
use app\models\Country\Country;
use app\models\DistributionCenter\DistributionCenter;
use app\models\Opf\Opf;

/**
 * This is the model class for table "legal_subject".
 *
 * @property int $id
 * @property int $type_id Тип контрагента
 * @property int $country_id Страна
 * @property int $opf_id ОПФ
 * @property int $is_own Собственное предприятие
 * @property int $is_supplier Поставщик
 * @property int $is_buyer Покупатель
 * @property int $is_not_nds Без НДС
 * @property string $name Краткое название предприятия или ФИО
 * @property string $full_name Полное название предприятия или ФИО
 * @property string $inn ИНН
 * @property string|null $director Директор
 * @property string|null $accountant Бухгалтер
 * @property string|null $address Адрес
 * @property string|null $contacts Контактная информация
 * @property string|null $comment Комментарий
 *
 * @property Country $country
 * @property DistributionCenter $distributionCenter
 */
class LegalSubject extends Base
{
    const TYPE_COMPANY = 1;
    const TYPE_BUSINESSMAN = 2;
    const TYPE_PERSON = 3;
    const TYPE_LIST = [
        self::TYPE_COMPANY => 'Юридическое лицо',
        self::TYPE_BUSINESSMAN => 'ИП',
        self::TYPE_PERSON => 'Физическое лицо',
    ];

    public static function tableName(): string
    {
        return 'legal_subject';
    }

    public function rules(): array
    {
        return [
            [[
                'type_id',
                'name',
                'full_name',
                'country_id',
                'is_own'], 'required'],

            [['name'], 'string', 'min' => 1, 'max' => 30],
            [['full_name'], 'string', 'min' => 1, 'max' => 100],
            [[
                'type_id',
                'country_id',
                'opf_id'], 'integer'],

            [['inn'], 'unique', 'targetAttribute' => ['inn', 'country_id'],
                'message' => 'Комбинация {attribute} и Страна уже существует'],

            [['is_own', 'is_supplier', 'is_buyer', 'is_not_nds'], 'boolean'],
            [['director'], 'string', 'max' => 255],
            [['accountant'], 'string', 'max' => 255],
            [['address'], 'string', 'max' => 255],
            [['contacts'], 'string'],
            [['comment'], 'string'],
            [['inn'], 'checkInnForCountry'],
        ];
    }

    public function checkInnForCountry($attribute, $param): void
    {
        switch ($this->type_id) {
            case self::TYPE_COMPANY:
                $innName = $this->country->inn_legal_name;
                $innSize = $this->country->inn_legal_size;
                break;
            case self::TYPE_BUSINESSMAN:
            case self::TYPE_PERSON:
                $innName = $this->country->inn_name;
                $innSize = $this->country->inn_size;
                break;
            default:
                // Как для Физ. лица РФ
                $innName = 'ИНН';
                $innSize = 12;
        }
        if (mb_strlen($this->inn) !== $innSize) {
            $this->addError('inn', 'Размер поля ' . $innName . ' ' . $innSize . ' знаков!');
        }
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'type_id' => 'Тип контрагента' ,
            'country_id' => 'Страна',
            'country' => 'Страна',
            'opf_id' => 'ОПФ',
            'is_own' => 'Собственное предприятие',
            'is_supplier' => 'Поставщик',
            'is_buyer' => 'Покупатель',
            'is_not_nds' => 'НДС',
            'name' => 'Название или ФИО',
            'full_name' => 'Полное название или ФИО',
            'inn' => 'ИНН',
            'director' => 'Директор',
            'accountant' => 'Бухгалтер',
            'address' => 'Адрес',
            'contacts' => 'Контактная информация',
            'comment' => 'Комментарий',
        ];
    }

    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    public function getOpf()
    {
        return $this->hasOne(Opf::class, ['id' => 'country_id']);
    }

    public function getDistributionCenter()
    {
        return $this->hasMany(DistributionCenter::class, ['legal_subject_id' => 'id']);
    }

    // Список Собственных предприятий
    public static function getListOwn(): array
    {
        return self::getList('is_own');
    }

    // Список Поставщиков
    public static function getListSupplier(): array
    {
        return self::getList('is_supplier');
    }

    // Список Покупателей
    public static function getListBuyer(): array
    {
        return self::getList('is_own');
    }
}