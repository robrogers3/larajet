<?php
namespace robrogers\Larajet\Facades;

use Illuminate\Support\Facades\Facade;

class MailJet  extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Mailjet';
    }
}
