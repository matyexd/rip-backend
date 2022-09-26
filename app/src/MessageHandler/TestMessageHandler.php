<?php

namespace App\MessageHandler;

use App\Message\TestMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class TestMessageHandler
{
    public function __invoke(TestMessage $message)
    {
        print_r('Handler handled the message!');
    }
}