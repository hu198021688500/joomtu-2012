<?php

/**
 * 2012-7-16 11:32:59 UTF-8
 * @package protected.behaviors
 * @version 1.0
 *
 * @author hugb <hu198021688500@163.com>
 * @copyright (c) 2011-2015
 * @license ()
 *
 * $Id$
 *
 */

/**
 * 站内信
 */
class MessageBehavior extends ErrorBehavior {

    /**
     * 发送站内信
     * @param int $type
     * @param array $params
     * @param string $content
     * @param string $title
     * @return boolean
     */
    public function sendMessage($type, $params, $content, $title = null) {
        // 发送者
        if (empty($params['uid'])) {
            $userBehavior = new UserBehavior();
            $params['uid'] = $userBehavior->getLoginUserId();
        }
        if (empty($params['uid'])) {
            return $this->addError('未设置发件人');
        }
        //自定义接收者UID，多个以英文逗号分隔
        if ($type == 1) {
            if (empty($params['to_uids'])) {
                return $this->addError('未设置收件人');
            }
            if (!preg_match('/[\d,]/', $params['to_uids'])) {
                return $this->addError('收件人格式错误');
            }
            $params['to_uids'] = preg_replace('/,{2}/', ',', $params['to_uids']);
            if ($params['to_uids'] == '') {
                return $this->addError('收件人为空');
            }
            $toUids = explode(',', $params['to_uids']);
            $userBehavior = new UserBehavior();
            foreach ($toUids as $key => $toUid) {
                if (!$userBehavior->isExistence($toUid)) {
                    unset($toUids[$key]);
                    continue;
                }
            }
            if (!count($toUids)) {
                return $this->addError('所有的收件人UID不存在');
            }
            $params['to_uids'] = implode(',', $toUids);
        } elseif ($type == 2) {
            if (empty($params['group_id'])) {
                return $this->addError('未设置组ID');
            }
            $params['to_uids'] = null;
        } else {
            return $this->addError('未定义的类型');
        }
        //保存到数据库
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $message = new Message();
            $message->title = $title;
            $message->content = $content;
            $message->status = 0;
            $message->save();

            $messageOutbox = new MessageOutbox();
            $messageOutbox->uid = $params['uid'];
            $messageOutbox->to_uid = $params['to_uids'];
            $messageOutbox->msg_id = $message->mid;
            $messageOutbox->msg_title = $message->title;
            $messageOutbox->send_type = $type;
            $messageOutbox->group_id = ($type == 1 ? null : $params['group_id']);
            $messageOutbox->send_time = time();
            $messageOutbox->status = 0;
            $messageOutbox->save();

            $logMessage = $params['uid'] . '给' . ($type == 1 ? $params['to_uids'] : '组(' . $params['group_id'] . ')群') . '发了一条站内信';
            Yii::log($logMessage, 'trace', 'Message');

            return true;
        } catch (Exception $e) {
            $transaction->rollback();

            Yii::log($e->getMessage(), 'warning', 'Message');

            return false;
        }
    }

    public function getOutboxList() {

    }

    public function getInboxLIst() {

    }

    public function getOutboxMessage() {

    }

    public function getInboxMessage() {

    }

    public function groupMessageDistribute($uid) {
        $userBehavior = new UserBehavior();
        $user = $userBehavior->getUserDetail($uid);
        if ($user == null) {
            return false;
        }
        $criteria = new CDbCriteria();
        $criteria->addCondition('send_time > :sendTime');
        $criteria->addCondition('send_type == 1 AND status == 0');
        $outboxMessage = MessageOutbox::model()->findAll($criteria);
        if ($outboxMessage == null) {
            return false;
        }
        foreach ($outboxMessage as $message) {
            $toUids = explode(',', $message->to_uid);
            foreach ($toUids as $toUid) {
                if ($toUid != $uid) {
                    continue;
                }
                $messageInbox = new MessageInbox();
                $messageInbox->uid = $uid;
                $messageInbox->from_uid = $message->uid;
                $messageInbox->msg_id = $message->msg_id;
                $messageInbox->msg_title = $message->msg_title;
                $messageInbox->update_time = $message->send_time;
                $messageInbox->status = 0;
                $messageInbox->save();
            }
        }
        //组内分发
        return true;
    }

    public function getMessageList() {

    }

    public function getMessage() {

    }

}