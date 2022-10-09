<?php

namespace vebProjekat\core;

class Response
{
    public function setResponse(int $code) {
        http_response_code($code);
    }

}