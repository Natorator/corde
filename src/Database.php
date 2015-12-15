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
     * The database handle.
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
    public function __construct(Config $config)
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
     * Backtick the input.
     *
     * @param mixed $input
     *
     * @return mixed
     */
    public function backtick($input)
    {
        if (is_array($input)) {
            return array_map(array($this, 'backtick'), $input);
        }

        return '`'.$input.'`';
    }
}