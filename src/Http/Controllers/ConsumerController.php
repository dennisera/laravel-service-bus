<?php

namespace SMSkin\ServiceBus\Http\Controllers;

use SMSkin\ServiceBus\ServiceBus;
use SMSkin\ServiceBus\Exceptions\PackageConsumerNotExists;
use SMSkin\ServiceBus\Http\Requests\ConsumeRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ConsumerController extends Controller
{
    /**
     * @param ConsumeRequest $request
     * @return Response
     * @throws PackageConsumerNotExists
     * @throws ValidationException
     */
    public function __invoke(ConsumeRequest $request): Response
    {
        $response = app(ServiceBus::class)->consume(
            (new \SMSkin\ServiceBus\Requests\ConsumeRequest)->setJson(
                json_encode($request->input('package'))
            )
        );
        if (is_null($response)) {
            return response()->noContent();
        }
        return response()->json([
            'package' => $response->toArray()
        ]);
    }
}
