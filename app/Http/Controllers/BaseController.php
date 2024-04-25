<?php

namespace App\Http\Controllers;

use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class BaseController
 *
 * @package App\Http\Controllers
 */
class BaseController extends Controller
{
    /** @var BaseService $service */
    protected BaseService $service;
    /** @var Request $request */
    protected Request $request;

    /**
     * BaseController constructor.
     *
     * @param BaseService $service
     * @param Request     $request
     */
    public function __construct(BaseService $service, Request $request)
    {
        $this->service = $service;
        $this->request = $request;
    }

    /**
     * Get list model items
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->success(
            $this->service->getAll()
        );
    }

    /**
     * Show model item data by id
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        return $this->success(
            $this->service->find($id)
        );
    }

    /**
     * Create new model
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function store(): JsonResponse
    {
        return $this->success(
            $this->service->create(
                $this->request->all()
            )
        );
    }

    /**
     * Update model item data by id
     *
     * @param int $id
     *
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function update(int $id): JsonResponse
    {
        return $this->success(
            $this->service->update($this->request->all(), $id)
        );
    }

    /**
     * Destroy model by id
     *
     * @param int $id
     *
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->success(
            $this->service->delete($id)
        );
    }
}
