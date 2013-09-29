<?php

/**
 * This is the model class for table "user_friendlist".
 *
 * The followings are the available columns in table 'user_friendlist':
 * @property string $id
 * @property integer $userid
 * @property string $new_fl
 * @property string $old_fl
 */
class FriendList extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_friendlist';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userid', 'numerical', 'integerOnly'=>true),
			array('new_fl, old_fl', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userid, new_fl, old_fl', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'userid' => 'Userid',
			'new_fl' => 'New Fl',
			'old_fl' => 'Old Fl',
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
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);

		$criteria->compare('userid',$this->userid);

		$criteria->compare('new_fl',$this->new_fl,true);

		$criteria->compare('old_fl',$this->old_fl,true);

		return new CActiveDataProvider('FriendList', array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @return FriendList the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function updateFriendList($friends_data) {
        $this->old_fl = $this->new_fl ;
        $this->new_fl = CJSON::encode($friends_data) ;
        $this->last_updated = date("Y-m-d H:i:s") ;
        $this->save(false);
    }
}
