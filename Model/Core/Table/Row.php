<?php
/**
 * 
 */
class Model_Core_Table_Row
{

	protected $data = [];
    protected $Table = null;
    protected $tableClass = 'Model_Core_Table';

    public function __construct()
    {
        
    }
	public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        return null;
    }

    public function __unset($key)
    {
    	if (array_key_exists($key, $this->data)) {
        unset($this->data[$key]);
    	}
    	return $this;
    }
  
    public function getData($key = null)
    {
        if(!$key)
        {
        return $this->data;
        }
        if(array_key_exists($key, $this->data))
        {
            return $this->data[$key];
        }
        return null;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function addData($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    	
    }

    public function removeData($key)
    {
    	if(array_key_exists($key, $this->data))
    	{
    		unset($this->data[$key]);
    	}
    	return $this;
    }

    public function load($id, $column = null)
    {
        if (!$column) 
        {
            $column = $this->getTable()->getPrimaryKey();
        }

        $query = "SELECT * FROM `{$this->getTable()->getTableName()}` 
                WHERE `{$column}` = '{$id}'";
        $table = new Model_Core_table();

        $result = $table->fetchRow($query);
        if ($result) 
        {
            $this->data = $result;
        }

        return $this;
    }

    public function fetchAll($sql)
    {
        $result = $this->getTable()->fetchAll($sql);
        if(!$result)
        {
            return false;
        }
        foreach ($result as &$row)
        {
            $row = (new $this)->setData($row)->setTable($this->getTable());
        }
        return $result;
    }

    public function fetchRow($sql)
    {
         $result = $this->getTable()->fetchRow($sql);
        if($result)
        {
            $this->setData($result);
        }
        return $this;
        
    }
    public function save()
    {

        if (array_key_exists($this->getTable()->getPrimaryKey(),$this->data))
        {
            $result = $this->getTable()->update($this->data ,$this->getData($this->getTable()->getPrimaryKey()));
            return $result;
        }
        $insertId = $this->getTable()->insert($this->data);
        // return $this->load($insertId);
        return $insertId;
    }

    public function delete()
    {
        if (array_key_exists($this->getTable()->getPrimaryKey(),$this->data))
        {
            $result = $this->getTable()->delete($this->getData($this->getTable()->getPrimaryKey()));
            return $result;
        }
    }

    public function getTable()
    {
        if ($this->table) {
            return $this->Table;
        }
        // $tableClass = rtrim(get_called_class(),'_Row');
        $tableClass = $this->tableClass;
        $table = new $tableClass();
        $this->setTable($table);
        return $table;
    }

    public function setTable($table)
    {
        $this->Table = $table;

        return $this;
    }

    public function getTableClass()
    {
        return $this->tableClass;
    }

    public function setTableClass($tableClass)
    {
        $this->tableClass = $tableClass;

        return $this;
    }

    public function getId()
    {
        $primaryKey = $this->getTable()->getPrimaryKey();
        return (int)$this->$primaryKey;
    }

    public function setId($id)
    {
        $this->data[$this->getTable()->getPrimaryKey()] = (int)$id;

        return $this;
    }
}
?>