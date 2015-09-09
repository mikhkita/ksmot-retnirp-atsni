<?php

/**
 * This is the model class for table "photo".
 *
 * The followings are the available columns in table 'photo':
 * @property string $id
 * @property string $name
 * @property string $user
 * @property string $user_photo
 * @property integer $social_id
 * @property string $soc_key
 * @property integer $status
 * @property string $event_id
 * @property string $print_time
 */
class Photo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'photo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, user, user_photo, social_id, soc_key, event_id, print_time', 'required'),
			array('social_id, status', 'numerical', 'integerOnly'=>true),
			array('name, user, user_photo', 'length', 'max'=>255),
			array('soc_key', 'length', 'max'=>25),
			array('event_id, print_time', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, user, user_photo, social_id, soc_key, status, event_id, print_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'event' => array(self::BELONGS_TO, 'Event', 'event_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Путь',
			'user' => 'Пользователь',
			'user_photo' => 'Фото профиля',
			'social_id' => 'Соц. сеть',
			'soc_key' => 'Ключ',
			'status' => 'Статус',
			'event_id' => 'Event',
			'print_time' => 'Время печати'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('user',$this->user,true);
		$criteria->compare('user_photo',$this->user_photo,true);
		$criteria->compare('social_id',$this->social_id);
		$criteria->compare('soc_key',$this->soc_key,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('event_id',$this->event_id,true);
		$criteria->compare('print_time',$this->print_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Photo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
