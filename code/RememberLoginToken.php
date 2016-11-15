<?php

/**
 * A single login token/hash for auto login of multiple clients.
 *
 * @see Member
 */
class RememberLoginToken extends DataObject {

    private static $db = array(
        'Hash'  => 'Varchar(160)', // Note: this currently holds a hash, not a token.
        'Token' => 'Varchar(160)' // Note: this is the cookie token
    );

    private static $has_one = array(
        'Owner' => 'Member'
    );
}