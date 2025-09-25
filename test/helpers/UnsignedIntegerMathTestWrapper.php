<?php

namespace IPLib\Test\Helpers;

use IPLib\Service\UnsignedIntegerMath;

class UnsignedIntegerMathTestWrapper extends UnsignedIntegerMath
{
    /**
     * @var int|null
     */
    private $maxSignedInt;

    /**
     * @param int|null $value
     *
     * @return void
     */
    public function setMaxSignedInt($value)
    {
        $this->maxSignedInt = $value;
    }

    /**
     * {@inheritdoc}
     *
     * @see \IPLib\Service\UnsignedIntegerMath::getMaxSignedInt()
     */
    protected function getMaxSignedInt()
    {
        return $this->maxSignedInt === null ? parent::getMaxSignedInt() : $this->maxSignedInt;
    }
}
