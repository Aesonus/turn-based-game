<?php

namespace Aesonus\TurnGame\Storage;

use Aesonus\PhpMagic\WillHaveMagicProperties;
use RuntimeException;
use Throwable;

/**
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface BaseStorageInterface
{
    /**
     * MUST load the record by $id from the database
     * @param scalar $id
     * @return bool MUST return if the id exists
     */
    public function loadRecord($id): bool;

    /**
     * MUST save the record to the database and return the id
     * @return scalar
     * @throws Throwable MUST throw an exception if the save fails
     */
    public function save();

    /**
     * MUST return the id of the storage space or null if this is a new record
     * @return scalar|null
     */
    public function id();

    /**
     *
     * @return TurnStorageInterface MUST return a new instance of the current class
     * with all dependencies injected
     */
    public static function newInstance();
}
