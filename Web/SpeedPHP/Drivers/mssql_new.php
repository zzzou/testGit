 
<?php
/////////////////////////////////////////////////////////////////
// SpeedPHP中文PHP框架, Copyright (C) 2008 - 2010 SpeedPHP.com //
/////////////////////////////////////////////////////////////////
/**
 * db_sqlsrv sqlsrv2005数据库的驱动支持，修改自SpeedPHP MsSQL数据库驱动
 * 文件名：mssql2005.php
 */
class db_mssql_new
{
    /**
     * 数据库链接句柄
     */
    public $conn;
    /**
     * 执行的SQL语句记录
     */
    public $arrSql;
    /**
     * 按SQL语句获取记录结果，返回数组
     * 
     * @param sql  执行的SQL语句
     */
    public function getArray($sql)
    {
        if (!$result = $this->exec($sql))
            return FALSE;
        if (!sqlsrv_num_rows($result))
            return FALSE; //所有MSSQL函数修改为SQLSRV函数，下同
        $rows = array();
        while ($rows[] = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        } //原MSSQL_ASSOC，改为SQLSRV_FETCH_ASSOC
        sqlsrv_free_stmt($result); //原为mssql_free_result($result)
        array_pop($rows);
        return $rows;
    }
    
    /**
     * 返回当前插入记录的主键ID
     */
    public function newinsertid()
    {
        $result = $this->getArray("select @@IDENTITY as sptmp_newinsert_id");
        return $result[0]['sptmp_newinsert_id'];
    }
    
    /**
     * 格式化带limit的SQL语句。
     */
    public function setlimit($sql, $limit)
    {
        //if(!eregi(",", $limit))$limit = '0,'.$limit;
        //$sql .= " LIMIT {$limit}";
        //return $this->translimit($sql);
        return $sql; //不作处理，直接返回，以兼容spModel。sql2005不支持limit，此处舍弃
    }
    /**
     * 执行一个SQL语句
     * 
     * @param sql 需要执行的SQL语句
     */
    public function exec($sql)
    {
        $this->arrSql[] = $sql;
        if ($result = sqlsrv_query($this->conn, $sql, array(), array(
            "Scrollable" => SQLSRV_CURSOR_KEYSET
        ))) {
            //原为mssql_query($sql, $this->conn)，参数次序有变
            return $result;
        } else {
            spError("{$sql}<br />执行错误. "); // 这里舍弃了 mssql_get_last_message()，未找到合适函数
        }
    }
    
    /**
     * 返回影响行数
     */
    public function affected_rows()
    {
        return sqlsrv_rows_affected($this->conn);
    }
    /**
     * 获取数据表结构
     *
     * @param tbl_name  表名称
     */
    public function getTable($tbl_name)
    {
        $result  = $this->getArray("SELECT syscolumns.name FROM syscolumns, systypes WHERE syscolumns.xusertype = systypes.xusertype AND syscolumns.id = object_id('{$tbl_name}')");
        $columns = array();
        foreach ($result as $column)
            $columns[] = array(
                'Field' => $column['name']
            );
        return $columns;
    }
    /**
     * 构造函数
     *
     * @param dbConfig  数据库配置
     */
    public function __construct($dbConfig) //此函数修改多处
    {
        if (!function_exists('sqlsrv_connect'))
            spError('PHP环境未安装sqlsrv函数库！');
        $connstr        = array(
            "Database" => $dbConfig['database'],
            "ConnectionPooling" => false,
            "CharacterSet" => "UTF-8"
        );
        if()
        $connstr['UID'] = $dbConfig['login'];
        $connstr['PWD'] = $dbConfig['password'];
        $this->conn = sqlsrv_connect($dbConfig['host'], $connstr) or spError("SQLserver2005数据库链接错误，或无法找到数据库，请确认链接正常，数据库名称正确！");
    }
    /**
     * 对特殊字符进行过滤
     *
     * @param value  值
     */
    public function __val_escape($value)
    {
        if (is_null($value))
            return 'NULL';
        if (is_bool($value))
            return $value ? 1 : 0;
        if (is_int($value))
            return (int) $value;
        if (is_float($value))
            return (float) $value;
        if (@get_magic_quotes_gpc())
            $value = stripslashes($value);
        $search  = array(
            "\\",
            "\0",
            "\n",
            "\r",
            "\x1a",
            "'",
            '"'
        );
        $replace = array(
            "\\\\",
            "[NULL]",
            "\\n",
            "\\r",
            "\Z",
            "''",
            '\"'
        );
        return '\'' . str_replace($search, $replace, $value) . '\'';
    }
    /**
     * 析构函数
     */
    public function __destruct()
    {
        if (TRUE != $dbConfig['persistent'])
            @sqlsrv_close($this->conn);
    }
    //function translimit($sql) {...} 此函数舍弃
}
