<?php

namespace app\models\Assortment;

use DateTime;
use Yii;
use app\models\Product\Product;
use app\models\Unit\Unit;
use app\models\User\User;

/**
 * This is the model class for table "assortment".
 *
 * @property int $id
 * @property int $unit_id Единица измерения
 * @property int|null $product_id Продукт
 * @property string $name Название
 * @property float $weight Вес
 * @property string|null $comment Комментарий
 * @property int $created_by Создатель
 * @property string $created_at Дата создания
 * @property string $updated_at Дата обновления
 *
 * @property Unit $unit
 * @property Product $product
 * @property User $createdBy
 */
class Assortment extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'assortment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id'], 'default', 'value' => null],
            [['unit_id', 'name', 'weight'], 'required'],
            [['unit_id', 'product_id', 'created_by'], 'integer'],
            [['weight'], 'number', 'numberPattern' => '/^\d+(.\d+)?$/', 'min' => 0.001],
            [['comment'], 'string'],
            [['comment'], 'default', 'value' => null],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['name'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::class, 'targetAttribute' => ['unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'unit_id' => 'Единица измерения',
            'product_id' => 'Базовый продукт',
            'name' => 'Название',
            'weight' => 'Вес',
            'comment' => 'Комментарий',
            'created_by' => 'Создатель',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function beforeValidate()
    {
        if (isset($this->weight) && $this->weight) {
            $this->weight = str_replace(',', '.', $this->weight);
        }
        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        $now = (new DateTime('now'))->format('Y-m-d');
        if ($insert) {
            $this->created_by = Yii::$app->user->id;
            $this->created_at = $now;
        }
        $this->updated_at = $now;

        return true;
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
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * Gets query for [[Unit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::class, ['id' => 'unit_id']);
    }

}
