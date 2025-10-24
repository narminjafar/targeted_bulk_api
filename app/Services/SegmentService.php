<?php

namespace App\Services;

use App\Exceptions\CustomApiException;
use App\Repositories\Users\UserRepositoryInterface;
use App\Repositories\SegmentRepository;
use App\Repositories\Segments\SegmentRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Http\Response;
use Illuminate\Pipeline\Pipeline;

class SegmentService
{

    public function __construct(
        private SegmentRepositoryInterface    $segmentRepo,
        private UserRepositoryInterface       $userRepo,
        private SegmentFilterService $filterService
    )
    {
    }

    public function allPaginated(int $perPage)
    {
        return $this->segmentRepo->paginate($perPage);
    }

    public function create(array $data)
    {
        $data['name'] = $data['name'] ?? 'Segment ' . now()->format('Ymd_His');
        $filterJson = $data['filter_json'] ?? [];

        return $this->segmentRepo->create([
            'name' => $data['name'],
            'filter_json' => $filterJson,
        ]);
    }

    public function get(int $id)
    {
       $segment = $this->segmentRepo->find($id);

        if (!$segment) {
            throw new CustomApiException(
                'Tələb olunan seqment tapılmadı.',
                'NOT_FOUND',
                Response::HTTP_NOT_FOUND
            );
        }

        return $segment;
    }

    public function preview(int $id, int $sampleCount = 20): array
    {
        $segment = $this->get($id);
        $query = $this->filterUsers($segment->filter_json);

        return [
            'total_recipients' => $query->count(),
            'sample' => $query->inRandomOrder()->limit($sampleCount)->get(['id', 'name', 'email',
                'email_verified_at','marketing_opt_in','last_active_at'
            ]),
        ];
    }

    public function filterUsers(array $filters)
    {
        request()->merge(['filters' => $filters]);

        return app(Pipeline::class)
            ->send($this->userRepo->query())
            ->through($this->filterService->getFilters())
            ->thenReturn();
    }
}
