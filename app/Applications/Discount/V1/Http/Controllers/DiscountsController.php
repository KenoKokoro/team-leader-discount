<?php


namespace Discount\V1\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiscountsController extends Controller
{
    public function calculate(Request $request)
    {
        return $this->json()->ok();
    }
}