<?php

namespace Abimo\Session;

class Database implements \SessionHandlerInterface
{
    public function __construct(\Abimo\Config $config, \Abimo\Database $database)
    {
        $this->config = $config;
        $this->database = $database;

        $this->statement['table'] = $this->config->session['table'];
        $this->statement['id'] = $this->database->backtick('id');
        $this->statement['data'] = $this->database->backtick('data');
        $this->statement['updated'] = $this->database->backtick('updated');
        $this->statement['created'] = $this->database->backtick('created');
    }

    public function open($path, $id)
    {
        //
    }

    public function close()
    {
        //
    }

    public function read($id)
    {
        $statement = $this->database->handle
            ->prepare(
                'SELECT
                    *
                FROM '.$this->statement['table'].'
                WHERE '.$this->statement['id'].' = :id
                ');

        $statement->execute(['id' => $id]);

        return $statement->fetchAll();
    }

    public function write($id, $data)
    {
        $statement = $this->database->handle
            ->prepare(
                'INSERT INTO '.$this->statement['table'].' (
                    '.$this->statement['id'].',
                    '.$this->statement['data'].',
                    '.$this->statement['created'].')
                VALUES (
                    :idInsert,
                    :dataInsert,
                    :createdInsert)
                ON DUPLICATE KEY UPDATE
                    '.$this->statement['data'].' = :dataUpdate
                ');

        $statement->execute([
            //insert params
            'idInsert' => $id,
            'dataInsert' => $data,
            'createdInsert' => date('Y-m-d H:i:s', time()),
            //update params
            'dataUpdate' => $data
        ]);
    }

    public function destroy($id)
    {
        $statement = $this->database->handle
            ->prepare(
                'DELETE FROM '.$this->statement['table'].'
                WHERE '.$this->statement['id'].' = :id
                ');

        $statement->execute([
            'id' => $id
        ]);

        setcookie(session_name(), '', time() - 3600);
    }

    public function gc($lifetime)
    {
        $statement = $this->database->handle
            ->prepare(
                'DELETE FROM '.$this->statement['table'].'
                WHERE '.$this->statement['updated'].' < DATE_SUB(NOW(), INTERVAL '.$lifetime.' SECOND)
                ');

        $statement->execute();
    }
}