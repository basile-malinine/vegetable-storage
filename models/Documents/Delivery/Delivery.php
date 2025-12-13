<?php

namespace app\models\Documents\Delivery;

use DateTime;

use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

use app\models\Base;
use app\models\LegalSubject\LegalSubject;
use app\models\Manager\Manager;
use app\models\Stock\Stock;

/**
 * This is the model class for table "delivery".
 *
 * @property int $id
 * @property int $supplier_id Поставщик
 * @property int $own_id Предприятие
 * @property int $stock_id Склад
 * @property int $manager_id Менеджер
 * @property string|null $date_wait Дата ожидания
 * @property string|null $date_close Дата закрытия
 * @property float|null $price Сумма
 * @property int|null $weight Вес
// * @property float|null $price_fact Фактическая сумма
// * @property int|null $weight_fact Фактический вес
 * @property string|null $comment Комментарий
 * @property int|null $created_by Создатель
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата обновления
 *
 * @property LegalSubject $supplier
 * @property LegalSubject $own
 * @property Stock $stock
 * @property Manager $manager
 *
 * @property array $deliveryItems Состав Доставки
 */
class Delivery extends Base
{
    public mixed $price = null;
    public mixed $weight = null;

    public static function tableName()
    {
        return 'delivery';
    }

    public function rules()
    {
        return [
            [['date_wait', 'date_close', 'comment', 'created_by', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['supplier_id', 'own_id', 'stock_id', 'manager_id'], 'required'],
            [['supplier_id', 'own_id', 'stock_id', 'manager_id', 'weight', 'created_by'], 'integer'],
            [['date_wait', 'date_close', 'created_at', 'updated_at'], 'safe'],
            [['price'], 'number'],
            [['comment'], 'string'],
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => LegalSubject::class, 'targetAttribute' => ['supplier_id' => 'id']],
            [['own_id'], 'exist', 'skipOnError' => true, 'targetClass' => LegalSubject::class, 'targetAttribute' => ['own_id' => 'id']],
            [['stock_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stock::class, 'targetAttribute' => ['stock_id' => 'id']],
            [['manager_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manager::class, 'targetAttribute' => ['manager_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplier_id' => 'Поставщик',
            'own_id' => 'Предприятие',
            'stock_id' => 'Склад',
            'manager_id' => 'Менеджер',
            'date_wait' => 'Дата доставки',
            'date_close' => 'Дата закрытия',
            'price' => 'Сумма',
            'weight' => 'Вес',
            'price_fact' => 'Сумма (факт)',
            'weight_fact' => 'Вес (факт)',
            'comment' => 'Комментарий',
            'created_by' => 'Создатель',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->date_wait = $this->date_wait ? date('d.m.Y H:i', strtotime($this->date_wait)) : null;
        $this->date_close = $this->date_close ? date('Y-m-d H:i', strtotime($this->date_close)) : null;
        $this->created_at = $this->created_at ? date('Y-m-d H:i', strtotime($this->created_at)) : null;

        //
        if (!$this->price) {
            $items = $this->items;
            $prices = ArrayHelper::getColumn($items, 'price_total');
            $this->price = array_sum($prices);
        }

        if (!$this->weight) {
            $items = $this->items;
            $weights = ArrayHelper::getColumn($items, 'weight');
            $this->weight = array_sum($weights);
        }
    }
    public function beforeSave($insert)
    {
        $this->date_wait = $this->date_wait ? date('Y-m-d H:i', strtotime($this->date_wait)) : null;
        $this->date_close = $this->date_close ? date('Y-m-d H:i', strtotime($this->date_close)) : null;

        $now = (new DateTime('now'))->format('Y-m-d');
        if ($insert) {
            $this->created_by = Yii::$app->user->id;
            $this->created_at = $now;
        }
        $this->updated_at = $now;

        return true;
    }

    /**
     * Gets query for [[Manager]].
     *
     * @return ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(Manager::class, ['id' => 'manager_id']);
    }

    /**
     * Gets query for [[Own]].
     *
     * @return ActiveQuery
     */
    public function getOwn()
    {
        return $this->hasOne(LegalSubject::class, ['id' => 'own_id']);
    }

    /**
     * Gets query for [[Stock]].
     *
     * @return ActiveQuery
     */
    public function getStock()
    {
        return $this->hasOne(Stock::class, ['id' => 'stock_id']);
    }

    /**
     * Gets query for [[Supplier]].
     *
     * @return ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(LegalSubject::class, ['id' => 'supplier_id']);
    }

    // Возврат состава документа DeliveryItem[]
    public function getItems()
    {
        return $this->hasMany(DeliveryItem::class, ['delivery_id' => 'id']);
    }
}
