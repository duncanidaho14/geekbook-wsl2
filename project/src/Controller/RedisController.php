<?php

namespace App\Controller;

use Predis\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class RedisController extends AbstractController
{
    public Client $redis;

    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    #[Route('/add-message', name:'add_message_app')]
    public function addMessage(): Response
    {
        $streamOne = $this->redis->xadd('mystream', '*', '1234');
        $streamTwo = $this->redis->xadd('mystream', '*', '1234', 'temperature 20.1');

        return $this->render('/chat/add-message.html.twig', [
            'streamOne' => $streamOne,
            'streamTwo' => $streamTwo
        ]);
    }

    #[Route('/read-message', name:'read_message_app')]
    public function readMessages(): Response
    {
        $messages = $this->redis->xrange('mystream', '-', '+');
        
        $output = '';
        foreach ($messages as $messageId => $message) {
            $output .= "Message ID: $messageId, Data: " . json_encode($message) . "\n";
        }

        return $this->render('/chat/read-message.html.twig', [

        ]);
    }

    #[Route('/create-group', name:'create_group_app')]
    public function createGroup(): Response
    {
        $this->redis->xgroup('CREATE', 'mystream', 'mygroup', '0', true);

        return new Response('Consumer group created');
    }

    #[Route('/consume-messages', name:'consume_messages_app')]
    public function consumeMessages(): Response
    {
        $messages = $this->redis->xreadgroup('mygroup', 'consumer1', ['mystream' => '>'], 10);

        $output = '';
        foreach ($messages as $stream => $messageList) {
            foreach ($messageList as $messageId => $message) {
                $output .= "Message ID: $messageId, Data: " . json_encode($message) . "\n";
                
                // Acknowledge the message
                $this->redis->xack('mystream', 'mygroup', [$messageId]);
            }
        }

        return new Response("<pre>$output</pre>");
    }
}