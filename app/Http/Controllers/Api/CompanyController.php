<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUpdateCompany;

class CompanyController extends Controller
{

    protected $repository;

    public function __construct(Company $model)
    {
        $this->repository = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $companies = $this->repository->getFilterCompanies($request->get('filter', ''));
        return CompanyResource::collection($companies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
     * @param  string  $uuid
     * @return CompanyResource
     */
    public function show($uuid)
    {
       $company = $this->repository->where('uuid', $uuid)->with('category')->firstOrFail();
       return new CompanyResource($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid
     * @return CompanyResource
     */
    public function update(StoreUpdateCompany $request, $uuid)
    {
        $company = $this->repository->where('uuid', $uuid)->with('category')->firstOrFail();
        $company->update($request->validated());
        return  new CompanyResource($company);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $company = $this->repository->where('uuid', $uuid)->firstOrFail();
        $company->delete();
        return response()->noContent();
    }
}
