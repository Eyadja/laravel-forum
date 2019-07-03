<?php

namespace LaravelForum;

use LaravelForum\Notifications\ReplyMarkedAsBestReply;

class Discussion extends Model
{
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(Replay::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function bestReply()
    {
        return $this->belongsTo(Replay::class, 'reply_id');
    }

    public function scopefilterByChannels($builder)
    {
        if (request()->query('channel')) {
            $channel = Channel::where('slug', request()->query('channel'))->first();

            if ($channel) {
                return $builder->where('channel_id', $channel->id);
            }
            return $builder;
        }
        return $builder;
    }

    public function markAsBestReply(Replay $reply)
    {
        $this->update([
            'reply_id' => $reply->id
        ]);

        if ($reply->owner->id == $this->author->id)
        {
            return;
        }

        $reply->owner->notify(new ReplyMarkedAsBestReply($reply->discussion));
    }
}
