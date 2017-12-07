<?php

use SilverStripe\Admin\ModelAdmin;
class EncryptedDropboxAdmin extends ModelAdmin
{
    private static $menu_title = 'Encrypted Dropbox';
    private static $url_segment = 'encrypted-dropbox';
    private static $managed_models = [
        'RSAPublicKey'
    ];
}
