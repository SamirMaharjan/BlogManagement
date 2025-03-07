<?php

namespace App\Traits;


trait FormatAmount
{

    public function formatDollar($amount){
        return '$'.number_format((float)$amount, 2, '.', '');
    }
}
