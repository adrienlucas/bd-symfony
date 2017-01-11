<?php

namespace AppBundle\DAO;

use Doctrine\DBAL\Connection;
use Symfony\Component\Translation\Catalogue\TargetOperation;

class TodoDAO
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getAll()
    {
        $todos = [];

        $databaseResource = $this->query('SELECT * FROM todo');
        return $databaseResource->fetchAll();
    }

    public function get($id)
    {
        return $this->query('SELECT * FROM todo WHERE id = '.$id)->fetch();
    }

    public function testCreate()
    {
        $connection = $this->createMock(Connection::class);
        $connection->method('query')
            ->willReturn(true)
            ->expect(1);

        $dao = new TodoDAO($connection);

        $this->assertTrue($dao->create('toto'));
    }

    public function create($title)
    {
        if(empty($title)) {
            throw new \InvalidArgumentException('The title should not be empty.');
        }

        return $this->query('INSERT INTO todo (title) VALUES(\''.$title.'\');');
    }

    public function close($id)
    {
        return $this->query('UPDATE todo SET is_done = 1 WHERE id = '.$id);
    }

    public function delete($id)
    {
        return $this->query('DELETE FROM todo WHERE id = '.$id);
    }


    private function query($query)
    {
        $result = $this->connection->query($query);
        if($result === false) {
            throw new \RuntimeException('MySQL query is not valid.');
        }

        return $result;
    }
}