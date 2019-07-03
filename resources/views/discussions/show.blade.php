@extends('layouts.app')

@section('content')


    <div class="card">
        @include('partials.discussion-header')
        <div class="card-body">

            <div class="text-center">
                <strong>
                    {{ $discussion->title }}
                </strong>
            </div>

            <hr>

            {!! $discussion->content !!}


            @if ($discussion->bestReply)

                <div class="card bg-success my-5" style="color: white">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>
                                <img width="40px" height="40px" style="border-radius: 50%" class="mr-2"
                                     src="{{ Gravatar::src($discussion->bestReply->owner->email) }}" alt="">
                                <strong>
                                    {{ $discussion->bestReply->owner->name }}
                                </strong>
                            </div>
                            <div>
                                <strong>
                                    Best Reply
                                </strong>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <strong>{!! $discussion->bestReply->content !!}</strong>
                    </div>
                </div>

            @endif
        </div>
    </div>

    @foreach ($discussion->replies()->paginate(3) as $reply)
        <div class="card my-5">

            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <img width="40px" height="40px" style="border-radius: 50%"
                             src="{{ Gravatar::src($reply->owner->email) }}" alt="">
                        <span>{{ $reply->owner->name }}</span>
                    </div>
                    <div>
                        @auth
                            @if (auth()->user()->id == $discussion->user_id)
                                <form action="{{ route('discussions.best-reply', ['discussion' => $discussion->slug, 'reply' => $reply->id]) }}"
                                      method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">Mark as best reply</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
            <div class="card-body">
                {!! $reply->content !!}

            </div>
        </div>

    @endforeach
    {{ $discussion->replies()->paginate(3)->links() }}

    <div class="card my-5">
        <div class="card-header">
            Add a replay
        </div>

        <div class="card-body">

            @auth
                <form action="{{ route('replies.store', $discussion->slug) }}" method="POST">
                    @csrf

                    <input type="hidden" name="content" id="content">
                    <trix-editor input="content"></trix-editor>
                    <button type="submit" class="btn btn-success btn-sm my-2">
                        Add Replay
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-info">
                    Sign in to add a replay
                </a>
            @endauth
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.1.1/trix.css">
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.1.1/trix.js"></script>
@stop
