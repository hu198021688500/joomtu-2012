<?php

/**
 * This is the model class for table "{{msg_outbox}}".
 *
 * The followings are the available columns in table '{{msg_outbox}}':
 * @property string $id
 * @property string $uid
 * @property string $to_uid
 * @property string $msg_id
 * @property string $msg_title
 * @property integer $send_type
 * @property string $group_id
 * @property string $send_time
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Msg $msg
 * @property User $u
 */
class MessageOutbox extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MessageOutbox the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{msg_outbox}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid, msg_id, send_time', 'required'),
            array('send_type, status', 'numerical', 'integerOnly' => true),
            array('uid, msg_id, group_id, send_time', 'length', 'max' => 10),
            array('msg_title', 'length', 'max' => 50),
            array('to_uid', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, uid, to_uid, msg_id, msg_title, send_type, group_id, send_time, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'msg' => array(self::BELONGS_TO, 'Msg', 'msg_id'),
            'u' => array(self::BELONGS_TO, 'User', 'uid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'uid' => 'Uid',
            'to_uid' => 'To Uid',
            'msg_id' => 'Msg',
            'msg_title' => 'Msg Title',
            'send_type' => 'Send Type',
            'group_id' => 'Group',
            'send_time' => 'Send Time',
            'status' => 'Status',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('to_uid', $this->to_uid, true);
        $criteria->compare('msg_id', $this->msg_id, true);
        $criteria->compare('msg_title', $this->msg_title, true);
        $criteria->compare('send_type', $this->send_type);
        $criteria->compare('group_id', $this->group_id, true);
        $criteria->compare('send_time', $this->send_time, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}