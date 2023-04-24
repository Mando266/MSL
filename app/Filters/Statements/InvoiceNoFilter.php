<?php

namespace App\Filters\Statements;
use App\Filters\AbstractBasicFilter;

class InvoiceNoFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('invoice_no',$value);
    }
}
