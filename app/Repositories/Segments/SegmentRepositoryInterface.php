<?php
namespace App\Repositories\Segments;

use Illuminate\Database\Eloquent\Model;

interface SegmentRepositoryInterface {
    public function create(array $data);
    public function all(array $filters = []);
    public function find(int $id);
    public function update($model, array $data);
    public function paginate(int $perPage);
}
