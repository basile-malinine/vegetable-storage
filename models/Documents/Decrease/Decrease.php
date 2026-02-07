<?php

namespace app\models\Documents\Decrease;

use DateTime;

use Yii;

use app\models\Assortment\Assortment;
use app\models\Base;
use app\models\Documents\Acceptance\Acceptance;
use app\models\Documents\Remainder\Remainder;
use app\models\Documents\Shipment\Shipment;
use app\models\Documents\Shipment\ShipmentAcceptance;
use app\models\LegalSubject\LegalSubject;
use app\models\Stock\Stock;
use app\models\User\User;

/**
 * This is the model class for table "decrease".
 *
 * @property int $id
 * @property int $type_id Тип списания
 * @property int $acceptance_id Приёмка
 * @property int $company_own_id Предприятие
 * @property int $stock_id Склад
 * @property string|null $date Дата списания
 * @property string|null $date_close Дата закрытия
 * @property string|null $comment Комментарий
 * @property int|null $created_by Создатель
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата обновления
 *
 * @property Acceptance $sourceAcceptance Исходная Приёмка (по которой делаем Перемещение)
 * @property Acceptance $acceptance Ссылка на Приёмку Перемещения
 * @property Shipment $shipment Ссылка на Отгрузку Перемещения
 * @property Assortment[] $assortments
 * @property LegalSubject $companyOwn
 * @property User $createdBy
 * @property DecreaseItem[] $items
 * @property Stock $stock
 * @property string $label
 * @property string $shortLabel
 */
class Decrease extends Base
{
    const TYPE_INVENTORY = 1;
    const TYPE_REJECT = 2;
    const TYPE_LIST = [
        self::TYPE_INVENTORY => 'Инвентарка',
        self::TYPE_REJECT => 'Отход',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'decrease';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'date_close', 'comment', 'created_by', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['type_id', 'acceptance_id', 'company_own_id', 'stock_id'], 'required'],
            [['type_id', 'acceptance_id', 'company_own_id', 'stock_id', 'created_by'], 'integer'],
            [['date', 'date_close', 'created_at', 'updated_at'], 'safe'],
            [['comment'], 'string'],
            [['company_own_id'], 'exist', 'skipOnError' => true, 'targetClass' => LegalSubject::class, 'targetAttribute' => ['company_own_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
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
            'type_id' => 'Тип списания',
            'acceptance_id' => 'Приёмка',
            'company_own_id' => 'Предприятие',
            'stock_id' => 'Склад',
            'date' => 'Дата списания',
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

        $this->date = $this->date ? date('d.m.Y', strtotime($this->date)) : null;
        $this->date_close = $this->date_close ? date('d.m.Y', strtotime($this->date_close)) : null;
    }

    public function beforeValidate()
    {
        if (isset($this->oldAttributes['acceptance_id'])) {
            $this->acceptance_id = $this->oldAttributes['acceptance_id'];
        }

        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        $this->date = $this->date ? date('Y-m-d H:i', strtotime($this->date)) : null;
        $this->date_close = $this->date_close ? date('Y-m-d H:i', strtotime($this->date_close)) : null;

        $now = (new DateTime('now'))->format('Y-m-d');
        if ($insert) {
            $this->created_by = Yii::$app->user->id;
            $this->created_at = $now;
        }
        $this->updated_at = $now;

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            // Отыскиваем Приёмку на остатке
            $remainder = Remainder::findOne(['acceptance_id' => $this->acceptance_id]);
            // В Перемещении создаём позицию
            $decreaseItem = new DecreaseItem();
            $decreaseItem->decrease_id = $this->id;
            $decreaseItem->assortment_id = $remainder->assortment_id;
            $decreaseItem->pallet_type_id = $remainder->pallet_type_id;
            $decreaseItem->quantity = Remainder::getFreeByAcceptance($remainder->acceptance_id, 'quantity');
            $decreaseItem->quantity_pallet = Remainder::getFreeByAcceptance($remainder->acceptance_id, 'quantity_pallet');
            $decreaseItem->quantity_paks = Remainder::getFreeByAcceptance($remainder->acceptance_id, 'quantity_paks');
        } else {
            $decreaseItem = $this->items[0];
        }
        $decreaseItem->save();

        // Отыскиваем Отгрузку по Списанию
        $shipment = $this->shipment;
        // Если ещё не создана
        if (!$shipment) {
            // Создаём Отгрузку с типом Перемещение
            $shipment = new Shipment();
            $shipment->type_id = Shipment::TYPE_DECREASE;
            $shipment->parent_doc_id = $this->id;
            $shipment->company_own_id = $this->company_own_id;
            $shipment->stock_id = $this->stock_id;
            $shipment->date = $this->date;
            $shipment->date_close = null;
            $shipment->comment = 'Created automatically';
            $shipment->save();

            // В отгрузке создаём позицию
            $shipmentAcceptance = new ShipmentAcceptance();
            $shipmentAcceptance->shipment_id = $shipment->id;
            $shipmentAcceptance->acceptance_id = $this->acceptance_id;
            $shipmentAcceptance->pallet_type_id = $decreaseItem->pallet_type_id;
        } else {
            $shipmentAcceptance = $shipment->shipmentAcceptances[0];
        }
        $shipmentAcceptance->quantity = $decreaseItem->quantity;
        $shipmentAcceptance->quantity_pallet = $decreaseItem->quantity_pallet;
        $shipmentAcceptance->quantity_paks = $decreaseItem->quantity_paks;
        $shipmentAcceptance->save();
    }

    public function beforeDelete()
    {
        if ($this->date_close) {
            return false;
        }
        $shipment = $this->shipment;
        $shipment->delete();

        return true;
    }

    // Закрытие Отгрузки по Списанию
    public function apply()
    {
        $shipment = $this->shipment;
        if (!$shipment) {
            return false;
        }

        if ($shipment->applay()) {
            $this->date_close = $shipment->date_close;
            $this->save();
            return true;
        }

        return false;
    }

    public function revertRemainder()
    {
        $shipment = $this->shipment;
        $shipmentAcceptance = $shipment->shipmentAcceptances[0];

        if (Remainder::acceptanceFromShipped($shipmentAcceptance)) {
            $shipment->date_close = null;
            $shipment->save();
            $this->date_close = null;
            $this->save();
        }
    }

    /**
     * ------------------------------------------- Исходная Приёмка на остатке (по которой делаем Списание)
     * Gets query for [[Acceptance]].
     *
     * @return \yii\db\ActiveQuery
     */
    public
    function getSourceAcceptance()
    {
        return $this->hasOne(Remainder::class, ['acceptance_id' => 'acceptance_id']);
    }

    /**
     * ------------------------------------------- Ссылка на Отгрузку Списания
     * @return \yii\db\ActiveQuery
     */
    public function getShipment()
    {
        return $this->hasOne(Shipment::class, ['parent_doc_id' => 'id'])
            ->where(['type_id' => Shipment::TYPE_DECREASE]);
    }

    /**
     * Gets query for [[Assortments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public
    function getAssortments()
    {
        return $this->hasMany(Assortment::class, ['id' => 'assortment_id'])->viaTable('decrease_item', ['decrease_id' => 'id']);
    }

    /**
     * Gets query for [[CompanyOwn]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyOwn()
    {
        return $this->hasOne(LegalSubject::class, ['id' => 'company_own_id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[DecreaseItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(DecreaseItem::class, ['decrease_id' => 'id']);
    }

    /**
     * Gets query for [[Stock]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStock()
    {
        return $this->hasOne(Stock::class, ['id' => 'stock_id']);
    }

    // ------------------------------------------- Label
    public function getLabel(): string
    {
        $assortment = $this->items
            ? $this->items[0]->label
            : 'Нет состава';

        return '№' . $this->id
            . ' ' . $this->date
            . ', ' . $this->companyOwn->name
            . ', склад ' . $this->stock->name
            . ', ' . $assortment;
    }

    public function getShortLabel()
    {
        return '№' . $this->id
            . ' ' . $this->date;
    }
}
