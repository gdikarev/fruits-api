<?php

namespace App\Service;

use LogicException;

class AfterReadStorage
{
    private $object;

    /**
     * @return mixed
     */
    public function getObject()
    {
        if ($this->object === null) {
            throw new LogicException("No object to update");
        }

        return $this->object;
    }

    /**
     * @param mixed $object
     */
    public function setObject($object): void
    {
        $this->object = $object;
    }
}
