<?php

namespace app\models\Manager;

use app\models\Base;
use app\models\Documents\Delivery\Delivery;

/**
 * This is the model class for table "manager".
 *
 * @property int $id
 * @property string $name Имя
 * @property int $is_purchasing_mng Менеджер по закупкам
 * @property int $is_sales_mng Менеджер по реализации
 * @property int $is_support Отдел сопровождения
 * @property int $is_purchasing_agent Агент по закупкам
 * @property int $is_sales_agent Агент по реализации
 * @property string|null $comment Комментарий
 *
 * @property string|null $error Комментарий
 */
class Manager extends Base
{
    public $error = '';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'manager';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 30],
            [['name'], 'unique'],
            [[
                'is_purchasing_mng',
                'is_sales_mng',
                'is_support',
                'is_purchasing_agent',
                'is_sales_agent'], 'integer'
            ],
            [[
                'is_purchasing_mng',
                'is_sales_mng',
                'is_support',
                'is_purchasing_agent',
                'is_sales_agent'], 'testOnlyOne'
            ],
            [['comment'], 'string'],
            [['comment'], 'default', 'value' => null],
        ];
    }

    public function testOnlyOne($attribute)
    {
        $types = $this->getTypes();
        if (empty($types)) {
            $this->addError('error', 'Необходимо заполнить хотя бы один Тип.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'is_purchasing_mng' => 'Менеджер по закупкам',
            'is_sales_mng' => 'Менеджер по реализации',
            'is_support' => 'Отдел сопровождения',
            'is_purchasing_agent' => 'Агент по закупкам',
            'is_sales_agent' => 'Агент по реализации',
            'comment' => 'Комментарий',
        ];
    }

    // Получить Доставки
    public function getDeliveries()
    {
        return $this->hasMany(Delivery::class, ['manager_id' => 'id']);
    }

    public function getTypes(): array
    {
        $attrLabels = $this->attributeLabels();
        $types = [];
        $types[] = $this->is_purchasing_mng ? $attrLabels['is_purchasing_mng'] : null;
        $types[] = $this->is_sales_mng ? $attrLabels['is_sales_mng'] : null;
        $types[] = $this->is_support ? $attrLabels['is_support'] : null;
        $types[] = $this->is_purchasing_agent ? $attrLabels['is_purchasing_agent'] : null;
        $types[] = $this->is_sales_agent ? $attrLabels['is_sales_agent'] : null;
        $types = array_filter($types);

        return $types;
    }
}
