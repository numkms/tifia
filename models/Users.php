<?php

namespace tifia\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property int $client_uid
 * @property string $email
 * @property string $gender
 * @property string $fullname
 * @property string $country
 * @property string $region
 * @property string $city
 * @property string $address
 * @property int $partner_id
 * @property string $reg_date
 * @property int $status
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_uid', 'partner_id', 'status'], 'integer'],
            [['reg_date'], 'safe'],
            [['email'], 'string', 'max' => 100],
            [['gender'], 'string', 'max' => 5],
            [['fullname'], 'string', 'max' => 150],
            [['country'], 'string', 'max' => 2],
            [['region', 'city'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_uid' => 'Client Uid',
            'email' => 'Email',
            'gender' => 'Gender',
            'fullname' => 'Fullname',
            'country' => 'Country',
            'region' => 'Region',
            'city' => 'City',
            'address' => 'Address',
            'partner_id' => 'Partner ID',
            'reg_date' => 'Reg Date',
            'status' => 'Status',
        ];
    }

    public function getAccount() {
        return $this->hasOne(Accounts::class, ['client_uid' => 'client_uid']);
    }
}
