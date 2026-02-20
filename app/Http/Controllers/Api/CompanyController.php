<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCompany;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Service\EvaluationService;
use Illuminate\Http\Client\Response as HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class CompanyController extends Controller
{
    protected  $repository;
    protected  $evaluationService;

    public function __construct(Company $model, EvaluationService $evaluationService)
    {
        $this->repository = $model;
        $this->evaluationService = $evaluationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $companies = $this->repository->getFilterCompanies($request->get('filter', '')) ?? collect();
        return CompanyResource::collection($companies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUpdateCompany $request
     * @return CompanyResource
     */
    public function store(StoreUpdateCompany $request)
    {
        $company = $this->repository->create($request->validated());
        return new CompanyResource($company);
    }

    /**
     * Display the specified resource.
     *
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $uuid)
    {

        $evaluations = null;

        $company = $this->repository
            ->where('uuid', $uuid)
            ->with('category')
            ->firstOrFail();

        try {
            /** @var HttpResponse $response */
            $response = $this->evaluationService->getEvaluationsCompany($uuid);

            $evaluations = $response->successful()
                ? $response->json()
                : [
                    'error' => 'evaluation_service_failed',
                    'status' => $response->status(),
                    'body' => $response->body(),
                ];
        } catch (Throwable $e) {
            Log::warning('Evaluation service failed', [
                'company_uuid' => $uuid,
                'error' => $e->getMessage(),
            ]);

            $evaluations = [
                'error' => 'evaluation_service_exception',
                'message' => $e->getMessage(),
            ];
        }

        return response()->json([
            'data' => (new CompanyResource($company))->resolve(),
            'evaluations' => $evaluations,
        ]);


    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreUpdateCompany $request
     * @param string $uuid
     * @return CompanyResource
     */
    public function update(StoreUpdateCompany $request, string $uuid)
    {
        $company = $this->repository
            ->where('uuid', $uuid)
            ->with('category')
            ->firstOrFail();

        $company->update($request->validated());

        return new CompanyResource($company);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $uuid
     * @return Response
     */
    public function destroy(string $uuid)
    {
        $company = $this->repository->where('uuid', $uuid)->firstOrFail();
        $company->delete();
        return response()->noContent();
    }
}
