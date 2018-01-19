<?php

return [

    'vk' => [
        'group_id' => env('VK_GROUP_ID'),
    ],

    'discord' => [
        'webhook_url' => env('DISCORD_WEBHOOK_URL'),
    ],

    'telegram' => [
        'api_key'  => env('TELEGRAM_API_KEY'),
        'bot_name' => env('TELEGRAM_BOT_NAME'),
        'chat_id'  => env('TELEGRAM_CHAT_ID'),
    ],
];