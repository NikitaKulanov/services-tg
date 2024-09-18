<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RulesTGBot
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!($request->isJson())) return new Response('Unsupported media type.', 415);

        if ((
                $request->input('message.chat.type') === 'group' or
                $request->input('callback_query.message.chat.type') === 'group' or
                $request->input('channel_post.chat.type') === 'channel' or
                $request->input('edited_channel_post.chat.type') === 'channel'
//                $request->input('my_chat_member.new_chat_member.status') === 'kicked' // Пользователь заблокировал бота
            )
            and !config('bot.settings.answer_in_groups', false)) return new Response('', 204);

        return $next($request);
    }
}
