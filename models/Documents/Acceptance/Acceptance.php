<?php

namespace app\models\Documents\Acceptance;

use DateTime;

use Yii;

use app\models\Documents\Delivery\Delivery;
use app\models\LegalSubject\LegalSubject;
use app\models\Stock\Stock;

/**
 * This is the model class for table "acceptance".
 *
 * @property int $id
 * @property int $type_id Тип приёмки
 * @property int $delivery_id Поставка
 * @property int $parent_doc_id Старший документ
 * @property int $company_own_id Предприятие
 * @property int|null $stock_id Склад
 * @property string|null $acceptance_date Дата приёмки
 * @property string|null $date_close Дата закрытия
 * @property string|null $comment Комментарий
 * @property int|null $created_by Создатель
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата обновления
 *
 * @property LegalSubject $companyOwn
 * @property Stock $stock
 * @property Delivery $delivery
 * @property AcceptanceItem[] $items Состав приёмки
 */
class Acceptance extends \app\models\Base
{
    // Типы Приёмки -------------------------------------------------------------
    const TYPE_DELIVERY = 1;
    const TYPE_LIST = [
        self::TYPE_DELIVERY => 'По поставке',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acceptance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'stock_id',
                'acceptance_date',
                'comment',
                'created_by',
                'created_at',
                'updated_at'], 'default', 'value' => null
            ],

            [[
                'type_id',
                'parent_doc_id',
                'stock_id',
                'company_own_id'], 'required'
            ],

            [[
                'type_id',
                'delivery_id',
                'parent_doc_id',
                'company_own_id',
                'stock_id',
                'created_by'], 'integer'
            ],

            [[
                'acceptance_date',
                'date_close',
                'created_at',
                'updated_at'], 'safe'
            ],

            [['comment'], 'string'],

            [['company_own_id'], 'exist', 'skipOnError' => true, 'targetClass' => LegalSubject::class, 'targetAttribute' => ['company_own_id' => 'id']],
            [['stock_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stock::class, 'targetAttribute' => ['stock_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Тип',
            'delivery_id' => 'Поставка',
            'parent_doc_id' => 'По документу',
            'company_own_id' => 'Предприятие',
            'stock_id' => 'Склад',
            'acceptance_date' => 'Дата приёмки',
            'date_close' => 'Дата закрытия',
            'comment' => 'Комментарий',
            'created_by' => 'Создатель',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->acceptance_date = $this->acceptance_date
            ? date('d.m.Y', strtotime($this->acceptance_date)) : null;
    }

    public function beforeSave($insert)
    {
        $this->acceptance_date = $this->acceptance_date
            ? date('Y-m-d H:i', strtotime($this->acceptance_date)) : null;

        $now = (new DateTime('now'))->format('Y-m-d H:i');
        if ($insert) {
            $this->created_by = Yii::$app->user->id;
            $this->created_at = $now;
        }
        $this->updated_at = $now;

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert && $this->type_id && $this->parent_doc_id && $this->company_own_id && $this->stock_id) {
            switch ($this->type_id) {
                case self::TYPE_DELIVERY:
                    $delivery = Delivery::findOne($this->parent_doc_id);
                    foreach ($delivery->items as $item) {
                        $acceptanceItem = new AcceptanceItem();
                        $acceptanceItem->acceptance_id = $this->id;
                        $acceptanceItem->assortment_id = $item->assortment_id;
                        $acceptanceItem->quantity = .0;
                        $acceptanceItem->save();
                    }
                    break;
            }
        }
    }

    /**
     * ------------------------------------------- Поставка
     * Gets query for [[CompanyOwn]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDelivery()
    {
        return $this->hasOne(Delivery::class, ['id' => 'delivery_id']);
    }

    /**
     * ------------------------------------------- Старший документ
     * Gets query for [[ParentDoc]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParentDoc()
    {
        switch ($this->type_id) {
            case self::TYPE_DELIVERY:
                return $this->hasOne(Delivery::class, ['id' => 'parent_doc_id']);
            default:
                return null;
        }
    }

    /**
     * ------------------------------------------- Предприятие
     * Gets query for [[CompanyOwn]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyOwn()
    {
        return $this->hasOne(LegalSubject::class, ['id' => 'company_own_id']);
    }

    /**
     * ------------------------------------------- Склад
     * Gets query for [[Stock]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStock()
    {
        return $this->hasOne(Stock::class, ['id' => 'stock_id']);
    }

    // ------------------------------------------- Состав документа AcceptanceItem[]
    public function getItems()
    {
        return $this->hasMany(AcceptanceItem::class, ['acceptance_id' => 'id']);
    }
}
