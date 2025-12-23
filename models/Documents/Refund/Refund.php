<?php

namespace app\models\Documents\Refund;

use DateTime;

use Yii;

use app\models\Assortment\Assortment;
use app\models\Base;
use app\models\Documents\Order\Order;
use app\models\LegalSubject\LegalSubject;
use app\models\Stock\Stock;

/**
 * This is the model class for table "refund".
 *
 * @property int $id
 * @property int $type_id Тип возврата
 * @property int $order_id Заказ
 * @property int $company_own_id Предприятие
 * @property int $stock_id Склад
 * @property string|null $refund_date Дата возврата
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
 */
class Refund extends Base
{
    const TYPE_EXECUTOR = 2;
    const TYPE_LIST = [
        self::TYPE_EXECUTOR => 'Исполнитель',
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
                'refund_date',
                'date_close',
                'comment',
                'created_by',
                'created_at',
                'updated_at'], 'default', 'value' => null
            ],

            [['company_own_id'], 'validateCompanyOwn', 'skipOnEmpty' => false],

            [[
                'type_id',
                'order_id',
                'stock_id'], 'required'
            ],

            [[
                'type_id',
                'order_id',
                'company_own_id',
                'stock_id',
                'created_by'], 'integer'
            ],

            [[
                'refund_date',
                'date_close',
                'created_at',
                'updated_at'], 'safe'],

            [['comment'], 'string'],

            [['company_own_id'], 'exist', 'skipOnError' => true, 'targetClass' => LegalSubject::class, 'targetAttribute' => ['company_own_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::class, 'targetAttribute' => ['order_id' => 'id']],
            [['stock_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stock::class, 'targetAttribute' => ['stock_id' => 'id']],
        ];
    }

    public function validateCompanyOwn($attribute, $params)
    {
        $attr = $attribute;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Тип возврата',
            'order_id' => 'Заказ',
            'company_own_id' => 'Предприятие',
            'stock_id' => 'Склад',
            'refund_date' => 'Дата возврата',
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

        $this->refund_date = $this->refund_date ? date('d.m.Y', strtotime($this->refund_date)) : null;
        $this->date_close = $this->date_close ? date('Y-m-d H:i', strtotime($this->date_close)) : null;
        $this->created_at = $this->created_at ? date('Y-m-d H:i', strtotime($this->created_at)) : null;
    }

    public function beforeSave($insert)
    {
        $this->refund_date = $this->refund_date ? date('Y-m-d H:i', strtotime($this->refund_date)) : null;
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
        if ($insert && $this->type_id && $this->order_id && $this->company_own_id && $this->stock_id) {
            switch ($this->type_id) {
                case self::TYPE_EXECUTOR:
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
}
