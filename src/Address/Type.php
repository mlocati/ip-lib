<?php

namespace IPLib\Address;

/**
 * Types of IP addresses.
 */
class Type
{
    /**
     * IPv4 address.
     *
     * @var int
     */
    const IPv4 = 4;

    /**
     * IPv6 address.
     *
     * @var int
     */
    const IPv6 = 6;

    /**
     * The following constants are kept only for backward compatibility
     */

    /**
     * @deprecated use IPv4 instead.
     */
    const T_IPv4 = 4;

    /**
     * @deprecated use IPv6 instead.
     */
    const T_IPv6 = 6;

    /**
     * Get the name of a type.
     *
     * @param int $type
     *
     * @return string
     */
    public static function getName($type)
    {
        switch ($type) {
            case static::IPv4:
                return 'IP v4';
            case static::IPv6:
                return 'IP v6';
            default:
                return sprintf('Unknown type (%s)', $type);
        }
    }
}
