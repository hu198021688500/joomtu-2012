<?php

/**
 * 2012-7-25 14:06:23 UTF-8
 * @package
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
 * Description of Mysql
 */
class Mysql {

    private $connectionString;
    private $username = '';
    private $password = '';
    private $instance = null;
    private $DB = null;
    private $version = 0;
    //
    public static $stmt = null;
    public static $querycount = 0;
    public static $DB = null;
    public static $version = 0;
    public static $debug = 0;

    private function __construct($config) {
        $this->connectionString = $config['dsn'];
        $this->username = $config['username'];
        $this->password = $password['config'];
        $this->connect();
    }

    public static function getInstance() {
        if ($this->instance == null) {
            $this->instance = new Database();
        }
        return $this->instance;
    }

    private function connect() {
        $this->DB = new PDO($this->connectionString, $this->username, $this->password);
        if ($this->DB) {
            $this->version = $this->DB->getAttribute(PDO::ATTR_SERVER_VERSION);
            if ($this->version > '4.1') {
                $this->DB->exec("SET NAMES 'utf8'");
            }
            if ($this->version > '5.0.1') {
                $this->DB->exec("SET sql_mode=''");
            }
        } else {
            self::halt('Can not connect MySQL Server or DataBase.');
        }
    }

    private function getErrInfo() {
        if (self::getErrNo() != '00000') {
            $info = (self::$stmt) ? self::$stmt->errorInfo() : self::$DB->errorInfo();
            self::halt($info[2]);
        }
    }

    function getErrNo() {
        if (self::$stmt) {
            return self::$stmt->errorCode();
        } else {
            return self::$DB->errorCode();
        }
    }

    /*     * *
     * 输出数据库出错信息
     * * */

    private function halt($msg = '') {
        $message = "<html>\n<head>\n";
        $message .= "<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">\n";
        $message .= "<style type=\"text/css\">\n";
        $message .= "* {font:12px Verdana;}\n";
        $message .= "</style>\n";
        $message .= "</head>\n";
        $message .= "<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#006699\" vlink=\"#5493B4\">\n";
        $message .= "<p>Mysql error:</p><pre><b>" . htmlspecialchars($msg) . "</b></pre>\n";
        $message .= "<b>Mysql error description</b>: " . htmlspecialchars(self::getErrInfo()) . "\n<br />";
        $message .= "<b>Date</b>: " . date("Y-m-d @ H:i") . "\n<br />";
        $message .= "<b>Script</b>: http.//" . $_SERVER['HTTP_HOST'] . getenv("REQUEST_URI") . "\n<br />";
        $message .= "</body>\n</html>";
        echo $message;
        exit;
    }

    /*     * *
     * 作用:获取当前库的所有表名
     * 返回:当前库的所有表名
     * 类型:数组
     * * */

    public function getTablesName() {
        self::$stmt = self::$DB->query('SHOW TABLES FROM ' . self::$dbname);
        self::getErrInfo();
        $result = self::$stmt->fetchAll(PDO::FETCH_NUM);
        self::$stmt = null;
        return $result;
    }

    /*     * *
     * 作用:获取数据表里的字段
     * 返回:表字段结构
     * 类型:数组
     * * */

    public function getFields($table) {
        self::$stmt = self::$DB->query("DESCRIBE $table");
        self::getErrInfo();
        $result = self::$stmt->fetchAll(PDO::FETCH_ASSOC);
        self::$stmt = null;
        return $result;
    }

    /*     * *
     * 作用:获取所有数据
     * 返回:表内记录
     * 类型:数组
     * 参数:select * from table
     * * */

    public function getAll($sql, $type = PDO::FETCH_ASSOC) {
        if (self::$debug) {
            echo $sql . '<br />';
        }
        $result = array();
        self::$stmt = self::$DB->query($sql);
        self::getErrInfo();
        self::$querycount++;
        $result = self::$stmt->fetchAll($type);
        self::$stmt = null;
        return $result;
    }

    /*     * *
     * 作用:获取单行数据
     * 返回:表内记录
     * 类型:数组
     * 参数:select * from table where id='1'
     * * */

    public function getOne($sql, $type = PDO::FETCH_ASSOC) {
        if (self::$debug) {
            echo $sql . '<br />';
        }
        $result = array();
        self::$stmt = self::$DB->query($sql);
        self::getErrInfo();
        self::$querycount++;
        $result = self::$stmt->fetch($type);
        self::$stmt = null;
        return $result;
    }

    /*     * *
     * 获取记录总数
     * 返回:记录数
     * 类型:数字
     * 参数:select count(*) from table
     * * */

    public function getRows($sql = '') {
        if ($sql) {
            if (self::$debug) {
                echo $sql . '<br />';
            }
            self::$stmt = self::$DB->query($sql);
            self::getErrInfo();
            self::$querycount++;
            $result = self::$stmt->fetchColumn();
            self::$stmt = null;
        } elseif (self::$stmt) {
            $result = self::$stmt->rowCount();
        } else {
            $result = 0;
        }
        return $result;
    }

// 获得最后INSERT的主键ID
    public function getLastId() {
        return self::$DB->lastInsertId();
    }

// 执行INSERT\UPDATE\DELETE,返回执行语句影响行数,数字类型
    public function Execute($sql) {
        $return = self::$DB->exec($sql);
        self::getErrInfo();
        self::$querycount++;
        return $return;
    }

// 关闭数据连接
    public function CloseDB() {
        self::$DB = null;
    }

}

