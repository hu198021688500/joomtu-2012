<?php
/*
 *	话题数据模型
 *	author		yanxf
 *	copyright	2011-2012
 *	version		0.1
 *	package		protected.models
 */

class Topic extends CActiveRecord{
	
	public $user_id = 0; // 用户Id
	public $category_id = 0; // 话题分类ID
	public $content = ''; // 话题内容
	public $read_count = 0; // 阅读次数
	public $comment_count = 0; // 评论次数
	public $recommend_count = 0; // 推荐次数
	public $accuse_count = 0; // 举报次数
	public $is_top = 0; // 是否置顶
	public $posids = 0;  // 推荐位置
	public $status = 0; // 状态
	public $create_time = 0; // 创建时间
	public $update_time = 0; // 更新时间
	public $source = 'web'; // 话题来源
	public $order_time = 0; // 排序控制
	
	public function getDbConnection(){
		return Yii::app()->db_slave;
	}
	
	public function init(){
		parent::init();
		$this->create_time = time();
		$this->update_time = time();
	}
	
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	public function tableName(){
		return '{{app_topic}}';
	}
	
	public function relations(){
		return array(
            'comment'=>array(self::HAS_MANY, 'Comment', 'id_in_app'),
            'follow'=>array(self::HAS_MANY, 'TopicFollow', 'target_id')
        );
	}

	public function rules(){
		return array(
			array('user_id,content', 'safe'),
		);
	}

	protected function afterSave() {
		parent::afterSave();
		#@ShellComponent::run('user', 'updateStatistics', 'updateStatistics', $this->user_id);
		$data = array();
		$data['title'] = $this->content;
		$data['createtime'] = $this->create_time;
		$data['sid'] = $this->id;
		$data['type'] = $this->category_id;
		$data['source'] = 2;
		@ShellComponent::run('search', 'SeachData', 'datasource', $data);
	}
	
	protected function afterDelete(){
		parent::afterDelete();
		#@ShellComponent::run('user', 'updateStatistics', 'updateStatistics', $this->user_id);
	}
}
?>
