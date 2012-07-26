<?php

/**
 * 2012-3-7 01:32:55
 * @package protected.models
 * @version 1.0
 *
 * @author hadoop <hu198021688500@163.com>
 * @copyright (c) 2011 http://www.test.com
 * @license (http://www.apache.org/licenses/LICENSE-2.0)
 *
 * @$Id$
 *
 */
YiiBase::import('ext.Neo4j');

class UserRel {

    public $rels;
    public $graphDb;

    public function __construct() {
        $this->rels = array(
            1 => 'stranger', // 陌生人
            2 => 'friend', // 好友
            3 => 'worker', // 同事
            4 => 'relative', // 亲戚
            5 => 'father', // 父亲
            6 => 'mother', // 母亲
            7 => 'brothers', // 兄弟
            8 => 'sisters', // 姐妹
            9 => 'brothers-sisters', // 兄妹
            10 => 'sisters-brothers', // 姐弟
            11 => 'son', // 儿子
            12 => 'daughter' // 女儿
        );
        $this->neo4j = new Neo4j(Yii::app()->params['neo4jRestUrl']);
    }

    /**
     * 获取neo4j节点
     * @param int $nodeId
     * @return object
     */
    public function getNode($nodeId, $returnAll = false) {
        $result = $this->neo4j->getNode($nodeId);
        if ($result) {
            if ($returnAll) {
                return $result;
            } else {
                return $result->data;
            }
        } else {
            return null;
        }
    }

    /**
     * 根据用户ID在neoj中创建节点
     * @param int $uid
     * @return boolean
     */
    public function createNodeByUID($uid) {
        $user = User::model()->findByPk($uid);
        if ($user != null) {
            $nid = $this->createNode(array('nickname' => $user->nickname));
            if ($nid > 0) {
                return User::model()->updateByPk($uid, array('nid' => $nid));
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 获取关系
     * @param int $relId
     * @return object
     */
    public function getRelById($relId, $returnAll = false) {
        $result = $this->neo4j->getRelationship($relId);
        if ($result) {
            if ($returnAll) {
                return $result;
            } else {
                return $result->data;
            }
        } else {
            return null;
        }
    }

    /**
     * 根据email查找节点
     * @param string $email
     * @return array
     */
    public function getNodeByEmail($email, $returnAll = false) {
        $queryStr = 'start x  = node:node_auto_index(email="' . $email . '") return x';
        $result = $this->neo4j->cypherQuery($queryStr);
        if ($result && isset($result->data[0][0])) {
            if ($returnAll) {
                return $result->data[0][0];
            } else {
                return $result->data[0][0]->data;
            }
        } else {
            return null;
        }
    }

    /**
     * 创建neo4j节点
     * @param array $data
     * @return int|boolean
     */
    public function createNode($data) {
        return $this->neo4j->createNodeWithProperties($data);
    }

    /**
     * 替换neo4j节点的所有属性
     * @param int $nodeId
     * @param array $data
     * @return boolean
     */
    public function replacesNodeAllAttr($nodeId, $data) {
        return $this->neo4j->setPropertiesOnNode($nodeId, $data);
    }

    /**
     * 设置neo4j节点的单个属性
     * @param int $nid
     * @param string $propertyName
     * @param int|string $value
     * @return boolean
     */
    public function setNodeAttr($nid, $propertyName, $value) {
        return $this->neo4j->setPropertyOnNode($nid, $propertyName, $value);
    }

    /**
     * 测试
     * @deprecated
     * @param int $email
     */
    public function getRecommendUserByEmail($email) {
        return User::model()->findAll();
    }

    /**
     * 添加关系
     * @param string $type
     * @param int $uid
     * @param int $myUid
     * @return boolean
     */
    public function addUserRel($relName, $uid, $myUid = null, $relAttr = null) {
        if (!in_array($relName, $this->rels)) {
            return false;
        }
        if (!is_numeric($uid)) {
            return false;
        }
        if ($myUid === null && !Yii::app()->user->id) {
            return false;
        }
        $myUid = $myUid === null ? Yii::app()->user->id : $myUid;
        if (!$this->isHasRel($uid, $myUid, $relName)) {
            if ($relAttr == null) {
                $relAttr = array('date' => time());
            } else {
                $relAttr['date'] = time();
            }
            return $this->neo4j->createRelationship($uid, $myUid, $relName, $relAttr);
        }
        return true;
    }

    /**
     * 获取两个用户之间的所有关系名称
     * @param int $uid
     * @param int $toUid
     * @return array
     */
    public function getUserRelsNames($uid, $toUid) {
        $attrNames = array();
        $result = $this->neo4j->cypherQuery('start x  = node(' . $uid . '), y = node(' . $toUid . ') match (x) -[r]->(y) return r');
        if (!$result) {
            return $attrNames;
        }
        if (count($result->data)) {
            foreach ($result->data as $value) {
                $attrNames[] = $value[0]->type;
            }
        }
        return $attrNames;
    }

    /**
     * 判断两个用户之间是否存在指定关系
     * @param int $uid
     * @param int $toUid
     * @param string $relName
     * @return boolean
     */
    public function isHasRel($uid, $toUid, $relName) {
        $attrNames = $this->getUserRelsNames($uid, $toUid);
        return in_array($relName, $attrNames);
    }

    /**
     * 获取两个用户之间的路径
     * @param int $uid
     * @param int $toUid
     * @return array
     */
    public function getPathByQuery($uid, $toUid) {
        $arr = $names = $rels = array();
        $result = $this->neo4j->findingPathBetweenTwoNodes($uid, $toUid, null, 15, 'shortestPath');
        //$result = $this->neo4j->cypherQuery('start d  = node(' . $uid .'), e = node(' . $toUid . ') match p = shortestPath( d-[*..15]->e) return p');
        if (!$result) {
            return null;
        }

        /* $result = $this->neo4j->cypherQuery('start d  = node(' . $uid .'), e = node(' . $toUid . ') match p = shortestPath( d-[*..15]->e) return p');
          if ($result && isset($result->data[0][0])) {
          $result = $result->data[0][0];
          } else {
          return $arr;
          } */

        $nodes = $result->nodes;
        foreach ($nodes as $value) {
            $nodeId = end(explode('/', $value));
            $node = $this->getNode($nodeId);
            $names[] = $node === null ? '' : $node->name;
        }
        $relationships = $result->relationships;
        foreach ($relationships as $value) {
            $relId = end(explode('/', $value));
            $rel = $this->getRelById($relId, true);
            //var_dump($rel);die();
            $rels[] = $rel === null ? '' : $rel->type;
        }
        $str = '';
        $count = count($names);
        if ($count) {
            foreach ($names as $key => $value) {
                $str .= $value;
                if ($key < $count - 1) {
                    $str .= '->';
                    $str .= $rels[$key];
                    $str .= '->';
                }
            }
        }
        return $str;
    }

    /**
     * 获取两个用户之间的路径
     * @param int $uid
     * @param int $toUid
     * @return array
     */
    public function getPath($uid, $toUid) {
        $attrNames = array();
        $relationships = array(
            'type' => 'father',
            'direction' => 'out'
        );
        $result = $this->neo4j->findingPathsBetweenTwoNodes($uid, $toUid, $relationships, 3, 'shortestPath');
        var_dump($result);
        die();
        if (!$result) {
            return $attrNames;
        }
        if (count($result->data)) {
            foreach ($result->data as $value) {
                $attrNames[] = $value[0]->type;
            }
        }
        return $attrNames;
    }

}