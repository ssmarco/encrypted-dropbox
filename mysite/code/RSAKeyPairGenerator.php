<?php

use SilverStripe\ORM\DB;
use SilverStripe\Dev\BuildTask;
class RSAKeyPairGeneratorTask extends BuildTask
{
    public $title = 'RSA Keypair Generator';
    public $description = 'Bulk generator for public/private keys';

    /**
     * @param HTTPRequest $request->n number of keys to generate
     */
    public function run($request) {
        $numberOfKeys = $request->requestVar('n');

        if(!$numberOfKeys || $numberOfKeys <= 0) {
            throw new Exception("Invalid number of keys to generate");
        }

        for($i = 0; $i < $numberOfKeys; $i++) {
            $rsaPubkey = RSAPublicKey::create();
            $rsaPubkey->write();
            DB::alteration_message('Wrote public key for ' . $rsaPubkey->GUID);
        }

    }
}
