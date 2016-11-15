<?php

class MultiSessionMemberExtension extends DataExtension {

    private static $has_many = array(
        'RememberLoginTokens' => 'RememberLoginToken'
    );

    public function getRememberLoginToken() {
        $memberData = $this->owner->toMap();

        return self::get_hash() ?: (
            array_key_exists('RememberLoginToken', $memberData) ?
                $memberData['RememberLoginToken'] :
                null
        );
    }

    public function memberLoggedIn() {
        $this->addToTokenList();
    }

    public function beforeMemberLoggedOut() {
        $hash = $this->getRememberLoginToken();

        // Delete login token from list
        if ($hash &&
            $token = $this->owner->RememberLoginTokens()->filter('Hash', $hash)->first()
        )
            $token->delete();
    }

    public static function get_hash() {
        if (!($cookie = Cookie::get('alc_enc')))
            return null;

        // Try to fetch cookie data
        list($uid, $token) = explode(':', $cookie, 2);

        // Get member
        $member = Member::get()->byID($uid);
        if(!$member) return null;

        // Check for corresponding db entry
        $rememberToken = $member
            ->RememberLoginTokens()
            ->filter('Token', $token)
            ->first();

        // Return hash
        return $rememberToken ? $rememberToken->Hash : null;
    }

    private function addToTokenList() {

        // Fetch logged members token/hash
        $hash = $this->getRememberLoginToken();

        // Add to list if not present yet
        if ($hash &&
            !$this->owner->RememberLoginTokens()->filter('Hash', $hash)->exists()
        ) {
            list($uid, $token) = explode(':', Cookie::get('alc_enc'), 2);
            $loginToken = RememberLoginToken::create();
            $loginToken->Hash = $hash;
            $loginToken->Token = $token;
            $loginToken->OwnerID = $uid;

            // Write token
            $this->owner->RememberLoginTokens()->add($loginToken->write());
        }
    }
}