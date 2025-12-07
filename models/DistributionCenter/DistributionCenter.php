<?php

namespace app\models\DistributionCenter;

use app\models\Base;
use app\models\LegalSubject\LegalSubject;

/**
 * This is the model class for table "distribution_center".
 *
 * @property int $id
 * @property int $legal_subject_id Владелец
 * @property string $name Название
 * @property string|null $comment Комментарий
 *
 * @property LegalSubject $legalSubject
 */
class DistributionCenter extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'distribution_center';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['legal_subject_id', 'name'], 'required'],
            [['legal_subject_id'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['name'], 'unique'],
            [['comment'], 'default', 'value' => null],
            [['comment'], 'string'],
            [['legal_subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => LegalSubject::class, 'targetAttribute' => ['legal_subject_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'legal_subject_id' => 'Владелец',
            'name' => 'Название',
            'comment' => 'Комментарий',
        ];
    }

    /**
     * Gets query for [[LegalSubject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLegalSubject()
    {
        return $this->hasOne(LegalSubject::class, ['id' => 'legal_subject_id']);
    }
}
