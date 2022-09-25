<?php

use App\Message\TestMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class TestMessangeHandler implements MessageHandlerInterface
{
    public function __invoke(TestMessage $message)
    {
        print_r('Handler handled the message!');
    }
}