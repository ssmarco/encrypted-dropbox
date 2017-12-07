<?php

use SilverStripe\ORM\DataExtension;
class EncryptedDropboxMemberExtension extends DataExtension
{
    private static $has_one = [
        'RSAPublicKey' => 'RSAPublicKey'
    ];

    public function onBeforeWrite() {
        if(!$this->owner->RSAPublicKeyID) {
            $key = RSAPublicKey::get()->filter('MemberID', 0)->limit(1)->first();
            if($key && $key->ID) {
                $this->owner->RSAPublicKeyID = $key->ID;
                $key->MemberID = $this->owner->ID;
                $key->write();
            }

        }
    }
}
