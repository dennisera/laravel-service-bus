<?php

namespace SMSkin\ServiceBus;

use SMSkin\ServiceBus\Controllers\CAsyncPublish;
use SMSkin\ServiceBus\Controllers\CConsume;
use SMSkin\ServiceBus\Controllers\CSyncPublish;
use SMSkin\ServiceBus\Packages\BasePackage;
use SMSkin\ServiceBus\Requests\AsyncPublishRequest;
use SMSkin\ServiceBus\Requests\ConsumeRequest;
use SMSkin\ServiceBus\Requests\SyncPublishRequest;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Validation\ValidationException;
use SMSkin\LaravelSupport\BaseModule;

class ServiceBus extends BaseModule
{
    /**
     * @param AsyncPublishRequest $request
     * @return void
     * @throws ValidationException
     */
    public function asyncPublish(AsyncPublishRequest $request): void
    {
        $request->validate();

        (new CAsyncPublish($request))->execute();
    }

    /**
     * @param SyncPublishRequest $request
     * @return void
     * @throws Exceptions\ApiTokenNotDefined
     * @throws Exceptions\PackageConsumerNotExists
     * @throws GuzzleException
     * @throws ValidationException
     */
    public function syncPublish(SyncPublishRequest $request): void
    {
        $request->validate();

        (new CSyncPublish($request))->execute();
    }

    /**
     * @param ConsumeRequest $request
     * @return BasePackage|null
     * @throws Exceptions\PackageConsumerNotExists
     * @throws ValidationException
     */
    public function consume(ConsumeRequest $request): ?BasePackage
    {
        $request->validate();

        return (new CConsume($request))->execute()->getResult();
    }
}
