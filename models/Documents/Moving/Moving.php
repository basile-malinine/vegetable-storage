<?php

namespace app\models\Documents\Moving;

use app\models\Assortment\Assortment;
use app\models\Base;
use app\models\Documents\Acceptance\Acceptance;
use app\models\Documents\Remainder\Remainder;
use app\models\Documents\Shipment\Shipment;
use app\models\Documents\Shipment\ShipmentAcceptance;
use app\models\LegalSubject\LegalSubject;
use app\models\Stock\Stock;
use app\models\User\User;
use DateTime;
use Yii;
use yii\db\IntegrityException;

/**
 * This is the model class for table "moving".
 *
 * @property int $id
 * @property int $acceptance_id Приёмка
 * @property int $company_sender_id Предприятие отправитель
 * @property int $stock_sender_id Склад отправитель
 * @property int $company_recipient_id Предприятие получатель
 * @property int $stock_recipient_id Склад получатель
 * @property string $moving_date Дата перемещения
 * @property string $date_close Дата закрытия
 * @property string|null $comment Комментарий
 * @property int|null $created_by Создатель
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата обновления
 *
 * @property Acceptance $sourceAcceptance Исходная Приёмка (по которой делаем Перемещение)
 * @property Acceptance $acceptance Ссылка на Приёмку
 * @property Assortment[] $assortments
 * @property LegalSubject $companyRecipient
 * @property LegalSubject $companySender
 * @property User $createdBy
 * @property MovingItem[] $items
 * @property Stock $stockRecipient
 * @property Stock $stockSender
 * @property string $label
 * @property string $shortLabel
 */
class Moving extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'moving';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'comment',
                'date_close',
                'created_by',
                'created_at',
                'updated_at'], 'default', 'value' => null
            ],

            [[
                'acceptance_id',
                'moving_date',
                'company_sender_id',
                'stock_sender_id',
                'company_recipient_id',
                'stock_recipient_id'], 'required'
            ],

            [[
                'acceptance_id',
                'company_sender_id',
                'stock_sender_id',
                'company_recipient_id',
                'stock_recipient_id',
                'created_by'], 'integer'
            ],

            [['comment'], 'string'],

            [[
                'moving_date',
                'date_close',
                'created_at',
                'updated_at'], 'safe'
            ],

            [['acceptance_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acceptance::class, 'targetAttribute' => ['acceptance_id' => 'id']],
            [['company_recipient_id'], 'exist', 'skipOnError' => true, 'targetClass' => LegalSubject::class, 'targetAttribute' => ['company_recipient_id' => 'id']],
            [['company_sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => LegalSubject::class, 'targetAttribute' => ['company_sender_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['stock_recipient_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stock::class, 'targetAttribute' => ['stock_recipient_id' => 'id']],
            [['stock_sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stock::class, 'targetAttribute' => ['stock_sender_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'acceptance_id' => 'Приёмка',
            'company_sender_id' => 'Отправитель',
            'stock_sender_id' => 'Со склада',
            'company_recipient_id' => 'Получатель',
            'stock_recipient_id' => 'На склад',
            'moving_date' => 'Дата',
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

        $this->moving_date = $this->moving_date ? date('d.m.Y', strtotime($this->moving_date)) : null;
        $this->date_close = $this->date_close ? date('d.m.Y', strtotime($this->date_close)) : null;
//        $this->created_at = $this->created_at ? date('d.m.Y', strtotime($this->created_at)) : null;
    }

    public function beforeSave($insert)
    {
        $this->moving_date = $this->moving_date ? date('Y-m-d H:i', strtotime($this->moving_date)) : null;
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
            $movingItem = new MovingItem();
            $movingItem->moving_id = $this->id;
            $movingItem->assortment_id = $remainder->assortment_id;
            $movingItem->quantity = $remainder->quantity;
            $movingItem->pallet_type_id = $remainder->pallet_type_id;
            $movingItem->quantity_pallet = $remainder->quantity_pallet;
            $movingItem->quantity_paks = $remainder->quantity_paks;
            $movingItem->save();

            // Создаём Отгрузку с типом "По перемещению"
            $shipment = new Shipment();
            $shipment->type_id = Shipment::TYPE_MOVING;
            $shipment->parent_doc_id = $this->id;
            $shipment->company_own_id = $this->company_sender_id;
            $shipment->stock_id = $this->stock_sender_id;
            $shipment->shipment_date = $this->moving_date;
            $shipment->date_close = (new DateTime('now'))->format('Y-m-d H:i');
            $shipment->comment = 'Created automatically';
            $shipment->save();

            // В отгрузке создаём позицию
            $shipmentAcceptance = new ShipmentAcceptance();
            $shipmentAcceptance->shipment_id = $shipment->id;
            $shipmentAcceptance->acceptance_id = $this->acceptance_id;
            $shipmentAcceptance->pallet_type_id = $remainder->pallet_type_id;
            $shipmentAcceptance->quantity = $remainder->quantity;
            $shipmentAcceptance->quantity_pallet = $remainder->quantity_pallet;
            $shipmentAcceptance->quantity_paks = $remainder->quantity_paks;
            $shipmentAcceptance->save();

            // Списываем Приёмку с остатка
            $remainder->delete();
        }
    }

    public function beforeDelete()
    {
        // Если есть Приёмка по Перемещению, удаление не возможно
        $movingAcceptance = Acceptance::findOne([
            'type_id' => Acceptance::TYPE_MOVING,
            'parent_doc_id' => $this->id,
        ]);
        if ($movingAcceptance) {
            throw new IntegrityException('');
        }

        // Отыскиваем Отгрузку по Перемещению
        $shipment = Shipment::findOne([
            'type_id' => Shipment::TYPE_MOVING,
            'parent_doc_id' => $this->id,
        ]);

        // Получаем позицию по Отгрузке
        $shipmentAcceptance = $shipment->shipmentAcceptances[0];

        // Восстанавливаем Приёмку на остатке
        Remainder::acceptanceFromShipped($shipmentAcceptance);

        // Удаляем Отгрузку
        $shipment->delete();

        return true;
    }

    /**
     * ------------------------------------------- Исходная Приёмка (по которой делаем Перемещение)
     * Gets query for [[Acceptance]].
     *
     * @return \yii\db\ActiveQuery
     */
    public
    function getSourceAcceptance()
    {
        return $this->hasOne(Acceptance::class, ['id' => 'acceptance_id']);
    }

    /**
     * ------------------------------------------- Ссылка на Приёмку
     * @return \yii\db\ActiveQuery
     */
    public function getAcceptance()
    {
        return $this->hasOne(Acceptance::class, ['parent_doc_id' => 'id'])
            ->where(['type_id' => Acceptance::TYPE_MOVING]);
    }

    /**
     * Gets query for [[Assortments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public
    function getAssortments()
    {
        return $this->hasMany(Assortment::class, ['id' => 'assortment_id'])->viaTable('moving_item', ['moving_id' => 'id']);
    }

    /**
     * Gets query for [[CompanyRecipient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public
    function getCompanyRecipient()
    {
        return $this->hasOne(LegalSubject::class, ['id' => 'company_recipient_id']);
    }

    /**
     * Gets query for [[CompanySender]].
     *
     * @return \yii\db\ActiveQuery
     */
    public
    function getCompanySender()
    {
        return $this->hasOne(LegalSubject::class, ['id' => 'company_sender_id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public
    function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[MovingItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public
    function getItems()
    {
        return $this->hasMany(MovingItem::class, ['moving_id' => 'id']);
    }

    /**
     * Gets query for [[StockRecipient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public
    function getStockRecipient()
    {
        return $this->hasOne(Stock::class, ['id' => 'stock_recipient_id']);
    }

    /**
     * Gets query for [[StockSender]].
     *
     * @return \yii\db\ActiveQuery
     */
    public
    function getStockSender()
    {
        return $this->hasOne(Stock::class, ['id' => 'stock_sender_id']);
    }

    public static function getList($condition = null): array
    {
        return [];
    }

    // ------------------------------------------- Label
    public function getLabel(): string
    {
        $assortment = $this->items
            ? $this->items[0]->label
            : 'Нет состава';

        return '№' . $this->id
            . ' ' . $this->moving_date
            . ', ' . $this->companySender->name
            . ', со склада ' . $this->stockSender->name
            . ', на склад ' . $this->stockRecipient->name
            . ', ' . $assortment;
    }

    public function getShortLabel()
    {
        return '№' . $this->id
            . ' ' . $this->moving_date;
    }

    // Список документов для Приёмки
    public static function getListForAcceptance()
    {
        $list = self::find()
            ->select(['id'])
            ->where([
                'date_close' => null,
            ])
            ->indexBy('id')
            ->column();

        $notAcceptedList = [];
        foreach ($list as $item) {
            $model = self::findOne($item);
            if (!$model->acceptance && $model->items) {
                $notAcceptedList[$item] = $model->label;
            }
        }

        return $notAcceptedList;
    }
}
