<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function hpp()
    {
        return view('calculator.hpp');
    }
}
