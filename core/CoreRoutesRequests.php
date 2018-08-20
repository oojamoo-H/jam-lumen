<?php
/**
 * 自定义Trait，重写原先RoutesRequests的prepareResponse方法，使用自定义的Response;
 */

namespace Core;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Laravel\Lumen\Concerns;
use Core\CoreResponse as Response;

trait CoreRoutesRequests{

    use Concerns\RoutesRequests,
        Concerns\RegistersExceptionHandlers;
    /**
     * Prepare the response for sending.
     *
     * @param  mixed  $response
     * @return Response
     */
    public function prepareResponse($response)
    {
        if ($response instanceof Responsable) {
            $response = $response->toResponse(Request::capture());
        }

        if ($response instanceof PsrResponseInterface) {
            $response = (new HttpFoundationFactory)->createResponse($response);
        } elseif (! $response instanceof SymfonyResponse) {
            $response = new Response($response);
        } elseif ($response instanceof BinaryFileResponse) {
            $response = $response->prepare(Request::capture());
        }

        return $response;
    }
}

