<?php

use SilverStripe\Core\Environment;
use Ramsey\Uuid\Uuid;
use phpseclib\Crypt\RSA;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;
class RSAPublicKey extends DataObject
{
    private static $singular_name = 'RSA Public Key';
    private static $plural_name = 'RSA Public Keys';

    private static $db = [
        'GUID' => 'Varchar(36)',
        'PublicKey' => 'Text'
    ];

    private static $has_one = [
        'Member' => Member::class
    ];

    private static $summary_fields = [
        'GUID' => 'Identifier',
        'Member.Name' => 'Member'
    ];

    public function onBeforeWrite() {
        if(!$privKeyDirectory = Environment::getEnv('RSA_PRIVATE_KEY_DIRECTORY')) {
            throw new Exception("Could not read environment variable: RSA_PRIVATE_KEY_DIRECTORY");
        }
        if(!is_dir($privKeyDirectory)) {
            throw new Exception("Cannot access directory $privKeyDirectory");
        }

        $privKeyDirectory = rtrim($privKeyDirectory, DIRECTORY_SEPARATOR);
        $rsa = new RSA();
        $keys = $rsa->createKey();

        if(isset($keys['publickey']) && isset($keys['privatekey'])) {
            $this->GUID = (string) Uuid::uuid4();
            $this->PublicKey = $keys['publickey'];

            $filename = $this->GUID.'.txt';
            $pathToPrivKey = $privKeyDirectory .DIRECTORY_SEPARATOR. $filename;
            file_put_contents($pathToPrivKey, $keys['privatekey']);
        }

        parent::onBeforeWrite();

    }

}
