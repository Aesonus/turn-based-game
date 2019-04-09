<?php

namespace Aesonus\TurnGame\Storage;

use Aesonus\PhpMagic\WillHaveMagicProperties;
use RuntimeException;
use Throwable;

/**
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface BaseStorageInterface extends WillHaveMagicProperties
{
    /**
     * MUST get the record by $id from the database
     * MUST throw exception if a record is already loaded
     * @param scalar $id
     * @return bool MUST return if the id exists
     * @throws RuntimeException
     */
    public function loadRecord($id): bool;

    /**
     * MUST save the record to the database and return the id
     * @return scalar
     * @throws Throwable MUST throw an exception if the save fails
     */
    public function save();
}
