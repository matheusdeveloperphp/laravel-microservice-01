<?php

namespace App\Service;

use Matheusdeveloperphp\MicroserviceCommon\Services\Traits\ConsumeExternalService;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class EvaluationService
{
    use ConsumeExternalService;

    protected  $url;
    protected  $token;

    public function __construct()
    {
        $this->url = rtrim(config('services.micro_02.url'), '/');
        $this->token = config('services.micro_02.token');
    }

    /**
     * @param string $companyUuid
     * @return Response
     */
    public function getEvaluationsCompany(string $companyUuid): Response
    {
        $response = $this->request('get', "/evaluations/{$companyUuid}");

        if ($response->failed()) {
            Log::warning('EvaluationService HTTP failed', [
                'status'   => $response->status(),
                'body'     => $response->body(),
                'url'      => $this->url,
                'endpoint' => "/evaluations/{$companyUuid}",
            ]);
        }

        return $response;
    }
}
