<?php

namespace App\Requests;

use System\Request;

class ProductListRequest extends Request
{
    public function __construct()
    {
        parent::__construct();
    }

    function validate(): bool
    {
        $this->errors->setMessage('ValidationError');
        if (!isset($this->data['limit']) || !(int)$this->data['limit']) {
            $this->errors->pushError('Limit is required');
            return false;
        }
        if (isset($this->data['offset']) && !(int)$this->data['offset']) {
            $this->errors->pushError('Offset is not valid');
            return false;
        }
        if (isset($this->data['search'])) {
            $searchLength = strlen($this->data['search']);
            if ($searchLength && ($searchLength < 1 || $searchLength > 64)) {
                $this->errors->pushError('Search value is invalid');
                return false;
            }
        }
        return true;
    }
}