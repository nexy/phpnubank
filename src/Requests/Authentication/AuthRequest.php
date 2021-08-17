<?php

namespace Nubank\Requests\Authentication;

use Nubank\Requests\BaseRequest;
use GuzzleHttp\Client as GuzzleClient;

abstract class AuthRequest extends BaseRequest
{
    protected $loginUrl;
    protected $liftUrl;

    public function setLoginUrls($login, $lift)
    {
        $this->loginUrl = $login;
        $this->liftUrl = $lift;

        return $this;
    }
}