<?php
namespace App\Repositories\Alimtalk\Interfaces;

use App\Models\Alimtalk\ShopModel;
use App\Repositories\Alimtalk\Interfaces\BaseRepositoryInterface;

interface ShopRepositoryInterface extends BaseRepositoryInterface 
{
    public function selectServiceLifeInfo(ShopModel $shop);
    public function applyMonthlyService(ShopModel $shop);
}