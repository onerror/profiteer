<?php

namespace Telegram;

class TelegramPublisher{
    private string $apiToken;
    private string $chatId;
    public function __construct(string $apiToken, string $chatId ){
        $this->apiToken = $apiToken;
        $this->chatId = $chatId;
    }
    
    /**
     * @param string $message
     *
     * @return string
     */
    public function publish(string $message): string
    {
        $requestData = [
            'chat_id' => $this->chatId,
            'text' => $message
        ];
        $response = file_get_contents(
            "https://api.telegram.org/bot{$this->apiToken}/sendMessage?" .
            http_build_query($requestData)
        );
        return empty($response)?'':$response;
    }
}