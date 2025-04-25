<?php

use App\Repositories\Message\MessageRepository;
use Illuminate\Support\Facades\Broadcast;

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

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
// });

// Broadcast::channel('message{uuid}', function () {
//
//	return true;
// });

Broadcast::channel('message.{chatUuid}', function ($chatUuid, $uuid,MessageRepository $repository) {
    if( $chatUuid == $uuid){
        $repository->changeMessageStatus($chatUuid, 2);
        return true;
    }else {
        return false;
    }

});

Broadcast::channel('message.{groupUuid}', function ($groupUuid, $uuid) {
    return $groupUuid == $uuid;
});

