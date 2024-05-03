<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\EventRepository;

class ChatGptController extends AbstractController
{
   
    private $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    #[Route('/process-input', name: 'process_input', methods: ['POST'])]
    public function processInput(Request $request): JsonResponse
    {
        $input = $request->request->get('input');
        
        // Check if input is null or empty
        if (empty($input)) {
            return $this->json(['error' => 'Input is missing or empty.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Process input
        $response = $this->processInputLogic($input);
        return $this->json(['response' => $response]);
    }

    private function processInputLogic(string $input): string
    {
        if (strtolower($input) === 'events') {
            // Retrieve events from EventController
            $events = $this->getEvents();
            return json_encode($events);
        } else {
            // Default OpenAI API response
            return $this->getOpenAIAPIAnswer($input);
        }
    }

    private function getEvents(): array
    {
        // Example: Retrieve all events
        $events = $this->eventRepository->findAll();

        // You can format events as needed before returning
        $formattedEvents = [];
        foreach ($events as $event) {
            $formattedEvents[] = [
                'id' => $event->getIdevent(),
                'title' => $event->getTitre(),
                // Add more properties if needed
            ];
        }

        return $formattedEvents;
    }

    private function getOpenAIAPIAnswer(string $question): string
    {
        // Replace 'your-api-key' with your actual OpenAI API key
        $apiKey = 'sk-proj-bBIksizCLeAPm28ey1ocT3BlbkFJnC5yqmJeBpOHkJZROmr0';
        $model = 'gpt-3.5-turbo'; // Adjust the model name

        $url = 'https://api.openai.com/v1/chat/completions';

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $apiKey,
        ];

        $body = [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                ['role' => 'user', 'content' => $question],
            ],
            'max_tokens' => 150,
        ];

        try {
            $client = new Client();
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $body,
            ]);

            $responseData = json_decode($response->getBody(), true);
            $choices = $responseData['choices'] ?? [];

            if (!empty($choices)) {
                $firstChoice = $choices[0];
                $message = $firstChoice['message'] ?? null;
                $content = $message['content'] ?? null;
                if ($content !== null) {
                    return $content;
                }
            }

            return 'No response content available.';
        } catch (\Exception $e) {
            return 'Oops! An error occurred while fetching the answer. Error: ' . $e->getMessage();
        }
    }

    #[Route('/test-chat-gpt', name: 'test_chat_gpt')]
    public function testChatGpt(): Response
    {
        return $this->render('test_chat_gpt.html.twig');
    }
}
