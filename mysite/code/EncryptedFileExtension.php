<?php

use SilverStripe\ORM\DataExtension;
class EncryptedFileExtension extends DataExtension
{
    private static $db = [
        'Encrypted' => 'Boolean'
    ];
}
