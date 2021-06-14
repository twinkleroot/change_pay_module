<?php
namespace App\Repositories\Alimtalk\Interfaces;

use App\Repositories\Alimtalk\Interfaces\BaseRepositoryInterface;
use App\Models\Alimtalk\UserModel;

interface UserRepositoryInterface 
{
    public function getUserByUserId(UserModel $user);
}