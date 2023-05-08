<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $modelClass = User::class;

    public $model;

    public function __construct()
    {
        $this->model = new $this->modelClass;
    }

    public function store($attribute)
    {
        $attribute['password'] = Hash::make($attribute['password']);
        $User = $this->model->newInstance($attribute);
        $User->save();
        return $User;
    }
}