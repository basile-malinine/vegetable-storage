<?php

namespace app\models\Documents\Acceptance;

use app\models\Base;
use app\models\Documents\Delivery\Delivery;
use app\models\Documents\Increase\Increase;
use app\models\Documents\Moving\Moving;
use app\models\Documents\Refund\Refund;
use app\models\Documents\Remainder\Remainder;
use app\models\Documents\Shipment\ShipmentAcceptance;
use app\models\LegalSubject\LegalSubject;
use app\models\Stock\Stock;
use DateTime;
use Yii;

/**
 * This is the model class for table "acceptance".
 *
 * @property int $id
 * @property int $type_id Тип приёмки
 * @property int $delivery_id Поставка
 * @property int $parent_doc_id Старший документ
 * @property int $company_own_id Предприятие
 * @property int|null $stock_id Склад
 * @property string|null $date Дата приёмки
 * @property string|null $date_close Дата закрытия
 * @property string|null $comment Комментарий
 * @property int|null $created_by Создатель
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата обновления
 *
 * @property mixed $parentDoc
 * @property LegalSubject $companyOwn
 * @property Stock $stock
 * @property Delivery $delivery
 * @property ShipmentAcceptance[] $shipments Состав приёмки
 * @property AcceptanceItem[] $items Состав приёмки
 * @property \app\models\Documents\Remainder\Remainder $remainder Остатки
 * @property string $label
 */
class Acceptance extends Base
{
    // Типы Приёмки -------------------------------------------------------------
    const TYPE_DELIVERY = 1;
    const TYPE_REFUND = 2;
    const TYPE_MOVING = 3;
    const TYPE_INCREASE = 4;
    const TYPE_LIST = [
        self::TYPE_DELIVERY => 'Поставка',
        self::TYPE_REFUND => 'Возврат',
        self::TYPE_MOVING => 'Перемещение',
        self::TYPE_INCREASE => 'Оприходование',
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
                'date',
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
                'date',
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
            'date' => 'Дата',
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

        $this->date = $this->date
            ? date('d.m.Y', strtotime($this->date)) : null;
    }

    public function beforeSave($insert)
    {
        $this->date = $this->date
            ? date('Y-m-d H:i', strtotime($this->date)) : null;

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
            $docItems = [];
            switch ($this->type_id) {
                // По поставке
                case self::TYPE_DELIVERY:
                    $delivery = Delivery::findOne($this->parent_doc_id);
                    $docItems = $delivery->items;
                    break;
                // По возврату
                case self::TYPE_REFUND:
                    $refund = Refund::findOne($this->parent_doc_id);
                    $docItems = $refund->items;
                    break;
                // По перемещению
                case self::TYPE_MOVING:
                    $moving = Moving::findOne($this->parent_doc_id);
                    $docItems = $moving->items;
                    break;
                // По оприходованию
                case self::TYPE_INCREASE:
                    $increase = Increase::findOne($this->parent_doc_id);
                    $docItems = $increase->items;
                    break;
            }
            foreach ($docItems as $item) {
                $acceptanceItem = new AcceptanceItem();
                $acceptanceItem->acceptance_id = $this->id;
                $acceptanceItem->quantity = .0;
                $acceptanceItem->assortment_id = $item->assortment_id;
                if ($this->type_id == self::TYPE_MOVING || $this->type_id == self::TYPE_INCREASE) {
                    $acceptanceItem->quantity = $item->quantity;
                    $acceptanceItem->pallet_type_id = $item->pallet_type_id;
                    $acceptanceItem->quantity_pallet = $item->quantity_pallet;
                    $acceptanceItem->quantity_paks = $item->quantity_paks;
                }
                $acceptanceItem->save();
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
                break;
            case self::TYPE_REFUND:
                return $this->hasOne(Refund::class, ['id' => 'parent_doc_id']);
                break;
            case self::TYPE_MOVING:
                return $this->hasOne(Moving::class, ['id' => 'parent_doc_id']);
                break;
            case self::TYPE_INCREASE:
                return $this->hasOne(Increase::class, ['id' => 'parent_doc_id']);
                break;
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

    /**
     * ------------------------------------------- Остатки
     * Gets query for [[Remainder]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRemainder()
    {
        return $this->hasOne(Remainder::class, ['acceptance_id' => 'id']);
    }

    // ------------------------------------------- Отгрузки ShipmentAcceptance[]
    public function getShipments()
    {
        return $this->hasMany(ShipmentAcceptance::class, ['acceptance_id' => 'id']);
    }

    // ------------------------------------------- Состав документа AcceptanceItem[]
    public function getItems()
    {
        return $this->hasMany(AcceptanceItem::class, ['acceptance_id' => 'id']);
    }

    public function getLabel()
    {
        $assortment = $this->items
            ? $this->items[0]->label
            : 'Нет состава';

        return '№' . $this->id
            . ' ' . $this->date
            . ', ' . $this->companyOwn->name
            . ', ' . $this->stock->name
            . ', ' . $assortment;
    }

    // Закрытие Оприходования
    public function applayIncrease()
    {
        // Отыскиваем исходную Приёмку на остатке
        $parentDoc = $this->parentDoc;
        $remainder = $parentDoc->sourceAcceptance;

        // Проводим Оприходование
        $remainder->applayIncrease($this->items[0]);

        // Закрываем Оприходование
        $this->date_close = (new DateTime('now'))->format('Y-m-d H:i');
        $this->save();

        return true;
    }

    // Отмена Оприходования
    public function cancelIncrease()
    {
        // Отыскиваем исходную Приёмку на остатке
        $parentDoc = $this->parentDoc;
        $remainder = Remainder::findOne(['acceptance_id' => $parentDoc->sourceAcceptance->acceptance_id]);

        // Отменяем Оприходование
        $remainder->cancelIncrease($this->items[0]);

        // Открываем Оприходование
        $this->date_close = null;
        $this->save();

        return true;
    }
}
