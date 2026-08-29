<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Services\AI;

use Exception;
use Illuminate\Support\Facades\Log;
use OpenAI;

class GeminiService implements AIServiceInterface
{
    use HasSystemPrompt;

    private $client;

    private array $config;

    /**
     * GeminiService constructor
     *
     * @param  array  $config  Configuration array containing API key, base URL, and timeout settings
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        if (empty($config['api_key'])) {
            throw new \InvalidArgumentException('Gemini API key is required');
        }

        $this->client = OpenAI::factory()
            ->withApiKey($config['api_key'])
            ->withBaseUri($config['base_url'] ?? 'https://generativelanguage.googleapis.com/v1beta/openai/')
            ->make();
    }

    /**
     * Generate content using Gemini API
     *
     * @param  string  $prompt  The prompt text to generate content from
     * @param  array  $options  Additional configuration options
     * @return string The generated content
     */
    public function generate(string $prompt, array $options = []): string
    {
        try {
            $prompt = $this->handlePrompt($prompt, $options);

            $requestData = [
                'model'       => $options['model'] ?? $this->config['model'] ?? 'gemini-1.5-flash',
                'messages'    => [['role' => 'user', 'content' => $prompt]],
                'max_tokens'  => $options['max_tokens'] ?? $this->config['max_tokens'] ?? 2048,
                'temperature' => $options['temperature'] ?? $this->config['temperature'] ?? 0.7,
            ];

            $response = $this->client->chat()->create($requestData);

            return $response->choices[0]->message->content ?? '';
        } catch (Exception $e) {
            Log::error('GeminiService error: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Stream content generation using Gemini API
     *
     * @param  string  $prompt  The prompt text to generate content from
     * @param  array  $options  Additional configuration options
     * @return iterable Iterator yielding generated content chunks
     */
    public function stream(string $prompt, array $options = []): iterable
    {
        $prompt = $this->handlePrompt($prompt, $options);

        $stream = $this->client->chat()->createStreamed([
            'model'       => $options['model'] ?? $this->config['model'] ?? 'gemini-1.5-flash',
            'messages'    => [['role' => 'user', 'content' => $prompt]],
            'max_tokens'  => $options['max_tokens'] ?? $this->config['max_tokens'] ?? 2048,
            'temperature' => $options['temperature'] ?? $this->config['temperature'] ?? 0.7,
        ]);

        foreach ($stream as $response) {
            $delta = $response->choices[0]->delta;
            if (isset($delta->content)) {
                yield $delta->content;
            }
        }
    }

    /**
     * Validate Gemini configuration
     *
     * @param  array  $config  Configuration array to validate
     * @return bool Whether the configuration is valid
     */
    public function validateConfig(array $config): bool
    {
        return ! empty($config['api_key']);
    }

    /**
     * Get Gemini model information
     *
     * @return array Model information including available models and capabilities
     */
    public static function getModelInfo(): array
    {
        return [
            'name'     => 'Google Gemini',
            'provider' => 'Google',
            'models'   => [
                'gemini-1.5-flash',
                'gemini-1.5-pro',
                'gemini-pro',
            ],
            'supports_streaming' => true,
            'supports_images'    => true,
            'max_tokens'         => 1000000,
            'base_url'           => 'https://generativelanguage.googleapis.com/v1beta/openai/',
        ];
    }
}
