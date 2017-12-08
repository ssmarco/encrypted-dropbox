<?php

use SilverStripe\Dev\Debug;
use SilverStripe\Control\Controller;
use SilverStripe\Security\Security;
use SilverStripe\Assets\File;
use phpseclib\Crypt\RSA;
class EncryptedUploadController extends Controller
{
    private static $url_segment = 'encrypted-upload';
    private static $allowed_actions = [
        'upload'
    ];

    public function init() {
        parent::init();
        $this->member = Security::getCurrentUser();
    }

    public function upload($request)
    {
        if(!$this->member) {
            return $this->httpError(401);
        }
        if($request && $request->isPost()) {
            $files = $request->postVar('secrets');

            if($files && isset($files['name'])) {
                $ident = $this->member->RSAPublicKey()->GUID;
                $pubkey = $this->member->RSAPublicKey()->PublicKey;
                foreach($files['name'] as $idx => $filename) {
                    $filename = $ident . '-' . $filename;
                    $uploaddir = ASSETS_PATH.DIRECTORY_SEPARATOR;
                    $uploadfile = $uploaddir . $filename;

                    if(!($file = File::find($filename))) {
                        $file = File::create();
                        $file->Encrypted = true;
                        $file->Filename = $filename;

                        $rsa = new RSA();
                        $rsa->loadKey($pubkey); // public key


                        $data = file_get_contents($files['tmp_name'][$idx]);
                        $ciphertext = $rsa->encrypt($data);
                        file_put_contents($uploadfile, $ciphertext);

                        $file->write();
                    }
                }
            }
        }
        $this->redirect("/");
    }
}
