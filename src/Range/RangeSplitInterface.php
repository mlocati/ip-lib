<?php

namespace IPLib\Range;

/**
 * Interface of Splitting the range.
 */
interface RangeSplitInterface
{
    /**
     * split the range into smaller ranges.
     *
     * @param int $networkPrefix
     * @return \Generator
     */
    public function split(int $networkPrefix): \Generator;
}
