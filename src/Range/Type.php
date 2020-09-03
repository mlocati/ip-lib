<?php

namespace IPLib\Range;

/**
 * Types of IP address classes.
 */
class Type
{
    /**
     * Unspecified/unknown address.
     *
     * @var int
     */
    const UNSPECIFIED = 1;

    /**
     * Reserved/internal use only.
     *
     * @var int
     */
    const RESERVED = 2;

    /**
     * Refer to source hosts on "this" network.
     *
     * @var int
     */
    const THIS_NETWORK = 3;

    /**
     * Internet host loopback address.
     *
     * @var int
     */
    const LOOPBACK = 4;

    /**
     * Relay anycast address.
     *
     * @var int
     */
    const ANYCAST_RELAY = 5;

    /**
     * "Limited broadcast" destination address.
     *
     * @var int
     */
    const LIMITED_BROADCAST = 6;

    /**
     * Multicast address assignments - Indentify a group of interfaces.
     *
     * @var int
     */
    const MULTICAST = 7;

    /**
     * "Link local" address, allocated for communication between hosts on a single link.
     *
     * @var int
     */
    const LINK_LOCAL = 8;

    /**
     * Link local unicast / Linked-scoped unicast.
     *
     * @var int
     */
    const LINK_LOCAL_UNICAST = 9;

    /**
     * Discard-Only address.
     *
     * @var int
     */
    const DISCARD_ONLY = 10;

    /**
     * Discard address.
     *
     * @var int
     */
    const DISCARD = 11;

    /**
     * For use in private networks.
     *
     * @var int
     */
    const PRIVATE_NETWORK = 12;

    /**
     * Public address.
     *
     * @var int
     */
    const PUBLIC_NETWORK = 13;

    /**
     * Carrier-grade NAT address.
     *
     * @var int
     */
    const CARRIER_GRADE_NAT = 14;

    /**
     * The following constants are kept only for backward compatibility.
     */

    /**
     * @deprecated use UNSPECIFIED instead
     */
    const T_UNSPECIFIED = 1;

    /**
     * @deprecated use RESERVED instead
     */
    const T_RESERVED = 2;

    /**
     * @deprecated use THIS_NETWORK instead
     */
    const T_THISNETWORK = 3;

    /**
     * @deprecated use LOOPBACK instead
     */
    const T_LOOPBACK = 4;

    /**
     * @deprecated use ANYCAST_RELAY instead
     */
    const T_ANYCASTRELAY = 5;

    /**
     * @deprecated use LIMITED_BROADCAST instead
     */
    const T_LIMITEDBROADCAST = 6;

    /**
     * @deprecated use MULTICAST instead
     */
    const T_MULTICAST = 7;

    /**
     * @deprecated use LINK_LOCAL_UNICAST instead
     */
    const T_LINKLOCAL = 8;

    /**
     * @deprecated use UNSPECIFIED instead
     */
    const T_LINKLOCAL_UNICAST = 9;

    /**
     * @deprecated use DISCARD_ONLY instead
     */
    const T_DISCARDONLY = 10;

    /**
     * @deprecated use DISCARD instead
     */
    const T_DISCARD = 11;

    /**
     * @deprecated use PRIVATE_NETWORK instead
     */
    const T_PRIVATENETWORK = 12;

    /**
     * @deprecated use PUBLIC_NETWORK instead
     */
    const T_PUBLIC = 13;

    /**
     * @deprecated use CARRIER_GRADE_NAT instead
     */
    const T_CGNAT = 14;

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
            case static::UNSPECIFIED:
                return 'Unspecified/unknown address';
            case static::RESERVED:
                return 'Reserved/internal use only';
            case static::THIS_NETWORK:
                return 'Refer to source hosts on "this" network';
            case static::LOOPBACK:
                return 'Internet host loopback address';
            case static::ANYCAST_RELAY:
                return 'Relay anycast address';
            case static::LIMITED_BROADCAST:
                return '"Limited broadcast" destination address';
            case static::MULTICAST:
                return 'Multicast address assignments - Indentify a group of interfaces';
            case static::LINK_LOCAL:
                return '"Link local" address, allocated for communication between hosts on a single link';
            case static::LINK_LOCAL_UNICAST:
                return 'Link local unicast / Linked-scoped unicast';
            case static::DISCARD_ONLY:
                return 'Discard only';
            case static::DISCARD:
                return 'Discard';
            case static::PRIVATE_NETWORK:
                return 'For use in private networks';
            case static::PUBLIC_NETWORK:
                return 'Public address';
            case static::CARRIER_GRADE_NAT:
                return 'Carrier-grade NAT';
            default:
                return $type === null ?
                    'Unknown type' :
                    sprintf('Unknown type (%s)', $type);
        }
    }
}
