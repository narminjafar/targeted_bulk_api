<?php
namespace App\Repositories\Users;

use Illuminate\Database\Eloquent\Model;

/**
 * @method \App\Models\User|null find(int $id)
 * @method \Illuminate\Database\Eloquent\Collection|\App\Models\User[] all()
 */


interface UserRepositoryInterface {
    public function all(array $filters = []);
    public function find(int $id): ?Model;

    public function query();

    public function paginate( int $perPage,array $filters = []);

    public function create(array $data): Model;

   public function update($model, array $data): Model;

    
}
