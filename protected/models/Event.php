<?php

/**
 * This is the model class for table "event".
 *
 * The followings are the available columns in table 'event':
 * @property string $id
 * @property string $name
 * @property string $printer_id
 * @property string $hashtag
 * @property string $user_id
 * @property integer $active
 * @property string $location
 * @property integer $offset
 * @property string $logo
 * @property string $start_time
 */
class Event extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, printer_id, hashtag, user_id, location, logo, start_time', 'required'),
			array('active, offset', 'numerical', 'integerOnly'=>true),
			array('name, hashtag, location, logo', 'length', 'max'=>255),
			array('printer_id, user_id, start_time', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, printer_id, hashtag, user_id, active, location, offset, logo, start_time', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'printer' => array(self::BELONGS_TO, 'Printer', 'printer_id'),
			'photo' => array(self::HAS_MANY, 'Photo', 'event_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'printer_id' => 'Принтер',
			'hashtag' => 'Хэштег',
			'user_id' => 'Пользователь',
			'active' => 'Активность',
			'location' => 'Местоположение',
			'start_time' => 'Дата начала мероприятия',
			'offset' => 'Время на модерацию (в секундах)',
			'logo' => 'Логотип мероприятия'
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
		$criteria->compare('printer_id',$this->printer_id,true);
		$criteria->compare('hashtag',$this->hashtag,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('offset',$this->offset);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('start_time',$this->start_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function beforeDelete() {
		Photo::model()->deleteAll("event_id=".$this->id);
        return true;
    }
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Event the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
