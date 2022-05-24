<?php
// src/Service/MessageGenerator.php
namespace App2\services;

class MessageGenerator
{
    private $logger;
    public function __construct(InterfaceLogger $logger)
    {
        $this->logger = $logger;
    }
    public function getHappyMessage(): string
    {
        $messages = [
            'You did it! You updated the system! Amazing!',
            'That was one of the coolest updates I\'ve seen all day!',
            'Great work! Keep going!',
        ];

        $index = array_rand($messages);
        $this->logger->info('About to find a happy message!'.$messages[$index]);
        return $messages[$index];
    }
}