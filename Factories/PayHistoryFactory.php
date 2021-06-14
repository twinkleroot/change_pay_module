<?php
namespace App\Factories;

use App\Common\Request;
use App\Common\Util;
use App\Models\Alimtalk\PayHistoryModel;

class PayHistoryFactory 
{
    public function create(Request $request) 
    {
        $req = $request->get();
        return (new PayHistoryModel())
                ->setUserId(isset($req->user_id) ? $req->user_id : Util::GetUserId())
                ->setTotalPrice($req->total_price)
                ->setOrderQty($req->order_quantity)
                ->setPayType($req->pay_type)
                ->setMonthlyPrice($req->monthly_price)
                ->setModule(1)
                ->setType(1);
    }
}