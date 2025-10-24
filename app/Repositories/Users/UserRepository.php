<?php

namespace App\Repositories\Users;

use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * @method \App\Models\User|null find(int $id)
 * @method \Illuminate\Database\Eloquent\Collection|\App\Models\User[] all()
 */

class UserRepository extends AbstractRepository implements UserRepositoryInterface

{
    protected Model $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function query()
    {
        return $this->model->newQuery();
    }
}
