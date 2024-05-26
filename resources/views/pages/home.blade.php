@extends('layouts.app')

@section('body')
    <section class="container hero">
        <div class="row align-items-center h-100">
            <div class="col-12 col-lg-6">
                <h1>
                    Welcome to Laracuss <br> The Community Forum
                </h1>
                <p class="mb-4">
                    Empowered by Laravel, Laracuss is a community forum for developers of all skill levels.
                </p>
                <a href="{{ route('auth.sign-up.show') }}" class="btn btn-primary me-2 mb-2 mb-lg-0">Sign Up</a>
                <a href="{{ route('discussions.index') }}" class="btn btn-secondary mb-2 mb-lg-0">Join Discussion</a>
            </div>
            <div class="col-12 col-lg-6 h-315px order-first order-lg-last mb-2 mb-lg-0">
                <img class="hero-image float-lg-end" src="{{ url('assets/images/hero-image.png') }}" alt="">
            </div>
        </div>
    </section>
    <section class="container min-h-372px">
        <div class="row">
            <div class="col-12 col-lg-4 text-center">
                <img class="promote-icon mb-2" src="{{ url('assets/images/discussions.png') }}" alt="Discussions">
                <h2>{{ Str::plural('Discussion', $discussionCount) }}</h2>
                <p class="fs-3">{{ $discussionCount }}</p>
            </div>
            <div class="col-12 col-lg-4 text-center">
                <img class="promote-icon mb-2" src="{{ url('assets/images/answers.png') }}" alt="Answers">
                <h2>{{ Str::plural('Answer', $discussionCount) }}</h2>
                <p class="fs-3">{{ $answerCount }}</p>
            </div>
            <div class="col-12 col-lg-4 text-center">
                <img class="promote-icon mb-2" src="{{ url('assets/images/users.png') }}" alt="Users">
                <h2>{{ Str::plural('User', $discussionCount) }}</h2>
                <p class="fs-3">{{ $userCount }}</p>
            </div>
        </div>
    </section>
    <section class="bg-gray">
        <div class="container py-80px">
            <h2 class="text-center mb-5">Help Others</h2>
            <div class="row">
                @forelse ($latestDiscussions as $item)
                    <div class="col-12 col-lg-4 mb-3">
                        <div class="card">
                            <a href="{{ route('discussions.show', $item->slug) }}">
                                <h3>{{ $item->title }}</h3>
                            </a>
                            <div class="">
                                <p class="mb-5">{{ $item->content_preview }}</p>
                            </div>
                            <div class="row">
                                <div class="col me-1 me-lg-2">
                                    <a href="{{ route('discussions.categories.show', $item->category->slug) }}"><span
                                            class="badge rounded-pill text-bg-light">{{ $item->category->name }}</span></a>
                                </div>
                                <div class="col-5 col-lg-7">
                                    <div class="avatar-sm-wrapper d-inline-block">
                                        <a href="#" class="me-1">
                                            <img class="avatar rounded-circle"
                                                src="{{ filter_var($item->user->picture, FILTER_VALIDATE_URL) ? $item->user->picture : Storage::url($item->user->picture) }}"
                                                alt="{{ $item->user->username }}">
                                        </a>
                                    </div>
                                    <span class="fs-12px">
                                        <a href="{{ route('users.show', $item->user->username) }}"
                                            class="me-1 fw-bold">{{ strlen($item->user->username) > 7 ? substr($item->user->username, 0, 7) . '...' : $item->user->username }}</a>
                                        <span class="color-gray">{{ $item->created_at->diffForHumans() }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </section>
    <section class="container min-h-372px d-flex flex-column align-items-center justify-content-center">
        <h2>Ready to contribute?</h2>
        <p class="mb-4">Want to make a big impact?</p>
        <div class="text-center">
            <a href="{{ route('auth.sign-up.show') }}" class="btn btn-primary me-2 mb-2 mb-lg-0">Sign Up</a>
            <a href="{{ route('discussions.index') }}" class="btn btn-secondary mb-2 mb-lg-0">Join Discussion</a>
        </div>
    </section>
@endsection
