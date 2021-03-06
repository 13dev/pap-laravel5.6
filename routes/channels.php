<?php

use App\Broadcasting\PostChannel;
use App\Broadcasting\ThreadChannel;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('post.{post}', PostChannel::class);

Broadcast::channel('thread.{thread}', ThreadChannel::class);

Broadcast::channel('messages.{id}', function ($user, $id) {
    return \Auth::check() && (int) $user->id === (int) $id;
});
