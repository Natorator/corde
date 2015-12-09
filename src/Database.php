<?php

namespace Abimo;

class Database
{
     /**
     * The database config array.
     *
     * @var array
     */
    public $config = array();
    
    /**
     * Database handle.
     *
     * @var callable
     */
    public $handle;
    
    /**
     * Create a new pdo instance.
     *
     * @param \Abimo\Config $config
     *
     * @throws \BadFunctionCallException
     */
    public function __construct(\Abimo\Config $config)
    {
        if (!class_exists('\\PDO', false)) {
            throw new \BadFunctionCallException("Class PDO not found", 97);
        }
 
        $this->config['database'] = $config->database;

        switch ($this->config['database']['driver']) {
            case 'sqlite' :
                $handle = new \PDO(
                    $this->config['database']['driver'].':'.$this->config['database']['host']
                    );
                break;
            default :
                $handle = new \PDO(
                    $this->config['database']['driver'].':'.'host='.$this->config['database']['host'].
                    ';dbname='.$this->config['database']['schema'].
                    ';charset=utf8', $this->config['database']['user'],
                    $this->config['database']['password']);
                break;
        }

        $handle->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $handle->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        $this->handle = $handle;
    }
    
    /**
     * Prepare the identifier.
     *
     * @param string $identifier
     *
     * @return string
     */
    public function prepareIdentifier($identifier)
    {
        return "`$identifier`";
    }

    /**
     * Prepare fields for 'set' query.
     *
     * @param mixed $fields
     *
     * @return string
     */
    public function prepareSet($fields)
    {
        if (is_array($fields)) {
            $set = '';
            
            foreach ($fields as $field) {
                $set .= "`$field`=:$field, ";
            }

            return substr($set, 0, -2);
        }
        
        return "`$fields`=:$fields";
    }
}