<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Alerts\TelegramBotService;
use Illuminate\Http\RedirectResponse;

class TelegramController extends Controller
{
    public function test(TelegramBotService $service): RedirectResponse
    {
        $chatId = request('chat_id');

        if (! $chatId) {
            return back()->withErrors(['chat_id' => __('Chat ID required')]);
        }

        $service->sendMessage($chatId, 'Test message from EMA Alert Bot');

        return back()->with('status', __('Test message sent.'));
    }
}
