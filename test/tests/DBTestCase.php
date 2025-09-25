<?php

namespace IPLib\Test;

use PDO;

abstract class DBTestCase extends TestCase
{
    /**
     * @var \PDO|null
     */
    private static $connection;

    /**
     * {@inheritdoc}
     *
     * @see \IPLib\Test\TestCaseBase::doSetUp()
     *
     * @return void
     */
    protected function doSetUp()
    {
        $this->getConnection()->exec('DELETE FROM ranges');
    }

    /**
     * @return \PDO
     */
    protected function getConnection()
    {
        if (self::$connection === null) {
            $connection = new PDO('sqlite::memory:');
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $connection->exec('
                CREATE TABLE ranges (
                    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    rangeRepresentation TEXT(44) NOT NULL,
                    addressType INTEGER NOT NULL,
                    rangeFrom TEXT(39) NOT NULL,
                    rangeTo TEXT(39) NOT NULL
                )
            ');
            self::$connection = $connection;
        }

        return self::$connection;
    }
}
