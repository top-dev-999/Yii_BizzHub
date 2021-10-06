<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contact_user".
 *
 * @property int $id
 * @property int|null $contact_id
 * @property int|null $user_id
 */
class ContactUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contact_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['contact_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contact_id' => 'Contact ID',
            'user_id' => 'User ID',
        ];
    }
}
