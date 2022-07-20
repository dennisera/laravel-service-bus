<?php

namespace SMSkin\ServiceBus\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Exception\AMQPChannelClosedException;
use PhpAmqpLib\Exception\AMQPHeartbeatMissedException;
use SMSkin\LaravelSupport\BaseJob;
use SMSkin\ServiceBus\Enums\Models\PublisherItem;
use SMSkin\ServiceBus\Packages\BasePackage;
use SMSkin\ServiceBus\Support\NeedleProject\LaravelRabbitMq\PublisherInterface;
use SMSkin\ServiceBus\Traits\ClassFromConfig;

class SubmitExceptionPackageToRabbitMQ extends BaseJob implements ShouldQueue
{
    use ClassFromConfig;

    public function __construct(public PublisherItem $publisher, public BasePackage $package)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        try {
            $this->getPublisher($this->publisher->id . '_error')->publish(
                json_encode($this->package->toArray()),
                '*'
            );
        } catch (AMQPHeartbeatMissedException|AMQPChannelClosedException $exception) {
            Log::error($exception);
            $this->release(10);
            return;
        }
    }

    private function getPublisher(string $publisher): PublisherInterface
    {
        return app(PublisherInterface::class, [
            $publisher
        ]);
    }
}