<?php

namespace app\models\Documents\Refund;

use DateTime;

use Yii;

use app\models\Assortment\Assortment;
use app\models\Base;
use app\models\Documents\Acceptance\Acceptance;
use app\models\Documents\Order\Order;
use app\models\LegalSubject\LegalSubject;
use app\models\Stock\Stock;

/**
 * This is the model class for table "refund".
 *
 * @property int $id
 * @property int $type_id Тип возврата
 * @property int $order_company_own_id Предприятие в заказе
 * @property int $order_stock_id Склад в заказе
 * @property int $order_executor_id Исполнитель в заказе
 * @property int $status_id Статус возврата
 * @property int $order_id Заказ
 * @property int $company_own_id Предприятие
 * @property int $stock_id Склад
 * @property string|null $date Дата возврата
 * @property string|null $date_close Дата закрытия
 * @property string|null $comment Комментарий
 * @property int|null $created_by Создатель
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата обновления
 *
 * @property Assortment[] $assortments
 * @property LegalSubject $companyOwn
 * @property Order $order
 * @property RefundItem[] $items
 * @property Stock $stock
 * @property string $label
 * @property Acceptance $acceptance
 */
class Refund extends Base
{
    public float|int $accepted = .0;

    const STATUS_REFUND = 1;
    const STATUS_REFUND_FROM_REMAINDER = 2;
    const STATUS_LIST = [
        self::STATUS_REFUND => 'Возврат',
        self::STATUS_REFUND_FROM_REMAINDER => 'Возврат с остатка',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'refund';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'date',
                'date_close',
                'comment',
                'created_by',
                'created_at',
                'updated_at'], 'default', 'value' => null
            ],

            [[
                'type_id',
                'order_company_own_id',
                'status_id',
                'order_id',
                'stock_id'], 'required'
            ],

            [[
                'type_id',
                'order_id',
                'order_company_own_id',
                'order_stock_id',
                'order_executor_id',
                'status_id',
                'company_own_id',
                'stock_id',
                'created_by'], 'integer'
            ],

            [[
                'date',
                'date_close',
                'created_at',
                'updated_at'], 'safe'],

            [['comment'], 'string'],

            [['company_own_id'], 'exist', 'skipOnError' => true, 'targetClass' => LegalSubject::class, 'targetAttribute' => ['company_own_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::class, 'targetAttribute' => ['order_id' => 'id']],
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
            'type_id' => 'Тип заказа',
            'status_id' => 'Статус возврата',
            'order_id' => 'Заказ',
            'order_company_own_id' => 'Предприятие в заказе',
            'order_stock_id' => 'Склад в заказе',
            'order_executor_id' => 'Исполнитель в заказе',
            'company_own_id' => 'Предприятие получатель',
            'stock_id' => 'Склад получатель',
            'date' => 'Дата возврата',
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
        $this->date_close = $this->date_close ? date('Y-m-d H:i', strtotime($this->date_close)) : null;
        $this->created_at = $this->created_at ? date('Y-m-d H:i', strtotime($this->created_at)) : null;
    }

    public function beforeSave($insert)
    {
        $this->date = $this->date ? date('Y-m-d H:i', strtotime($this->date)) : null;
        $this->date_close = $this->date_close ? date('Y-m-d H:i', strtotime($this->date_close)) : null;

        $now = (new DateTime('now'))->format('Y-m-d H:i:s');
        if ($insert) {
            $this->created_by = Yii::$app->user->id;
            $this->created_at = $now;
        }
        $this->updated_at = $now;

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert && $this->type_id && $this->order_id && $this->company_own_id && $this->stock_id) {
            switch ($this->type_id) {
                case Order::TYPE_STOCK:
                case Order::TYPE_EXECUTOR:
                    $order = Order::findOne($this->order_id);
                    foreach ($order->items as $item) {
                        $refundItem = new RefundItem();
                        $refundItem->refund_id = $this->id;
                        $refundItem->assortment_id = $item->assortment_id;
                        $refundItem->quantity = .0;
                        $refundItem->save();
                    }
                    break;
            }
        }
    }

    public function beforeDelete() {
        if ($this->acceptance) {
            if ($this->acceptance->date_close) {
                Yii::$app->session->setFlash('error', 'Удаление не возможно. По Возврату закрыта Приёмка.');
                return false;
            } else {
                $this->acceptance->delete();
            }
        }

        return true;
    }

    /**
     * Gets query for [[Assortments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssortments()
    {
        return $this->hasMany(Assortment::class, ['id' => 'assortment_id'])->viaTable('refund_item', ['refund_id' => 'id']);
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
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    /**
     * Gets query for [[RefundItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(RefundItem::class, ['refund_id' => 'id']);
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

    public function getLabel()
    {
        $assortment = $this->items
            ? $this->items[0]->label
            : 'Нет состава';

        return '№' . $this->id
            . ' ' . $this->date
            . ', ' . $this->companyOwn->name
            . ', ' . $assortment;
    }

    public function getShortLabel()
    {
        return '№' . $this->id
            . ' ' . $this->date;
    }

    // Ссылка на Приёмку
    public function getAcceptance()
    {
        return $this->hasOne(Acceptance::class, ['parent_doc_id' => 'id'])
            ->where(['type_id' => Acceptance::TYPE_REFUND]);
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
