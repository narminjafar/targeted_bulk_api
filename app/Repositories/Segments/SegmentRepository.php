<?php

namespace App\Repositories\Segments;

use App\Models\Segment;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class SegmentRepository extends AbstractRepository implements SegmentRepositoryInterface
{
    protected Model $model;

    public function __construct(Segment $model)
    {
        $this->model = $model;
    }
}
