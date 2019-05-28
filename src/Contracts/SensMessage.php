<?php

namespace Seungmun\Sens\Contracts;

interface SensMessage
{
    /**
     * Serialize to Array.
     *
     * @return array
     */
    public function toArray();
}