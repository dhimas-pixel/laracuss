@extends('layouts.app')

@section('body')
    <section class="bg-gray pt-4 pb-5">
        <div class="container">
            <div class="mb-4">
                <div class="mb-3 d-flex align-items-center justify-content-between">
                    <h2 class="me-4 mb-0">
                        @if (isset($search))
                            {{ "Search result for \"{$search}\"" }}
                        @else
                            {{ 'All Discussions' }}
                        @endif
                        <span>{{ isset($withCategory) ? ' About ' . $withCategory->name : '' }}</span>
                    </h2>
                    <div class="">
                        {{ $discussions->total() . ' ' . Str::plural('Discussion', $discussions->total()) }}
                    </div>
                </div>
                @auth
                    <a href="{{ route('discussions.create') }}" class="btn btn-primary">Create Discussion</a>
                @endauth
                @guest
                    <a href="{{ route('auth.login.show') }}" class="btn btn-primary">Log In to Create Discussion</a>
                @endguest
            </div>
            <div class="row">
                <div class="col-12 col-lg-8 mb-5 mb-lg-0">
                    @forelse ($discussions as $item)
                        <div class="card card-discussions">
                            <div class="row">
                                <div class="col-12 col-lg-2 mb-1 mb-lg-0 d-flex flex-row flex-lg-column align-items-end">
                                    <div class="text-nowrap me-2 me-lg-0">
                                        3 Likes
                                    </div>
                                    <div class="text-nowrap color-gray">
                                        10 Answers
                                    </div>
                                </div>
                                <div class="col-12 col-lg-10">
                                    <a href="{{ route('discussions.show', $item->slug) }}">
                                        <h3>{{ $item->title }}</h3>
                                        <p>{!! $item->content_preview !!}</p>
                                        <div class="row">
                                            <div class="col me-1 me-lg-2">
                                                <a href="{{ route('discussions.categories.show', $item->category->slug) }}">
                                                    <span
                                                        class="badge rounded-pill text-bg-light">{{ $item->category->name }}</span>
                                                </a>
                                            </div>
                                            <div class="col-5 col-lg-4">
                                                <div class="avatar-sm-wrapper d-inline-block">
                                                    <a href="{{ route('users.show', $item->user->username) }}"
                                                        class="me-1">
                                                        <img src="{{ filter_var($item->user->picture, FILTER_VALIDATE_URL) ? $item->user->picture : Storage::url($item->user->picture) }}"
                                                            alt="{{ $item->user->username }}"
                                                            class="avatar rounded-circle">
                                                    </a>
                                                </div>
                                                <span class="fs-12px">
                                                    <a href="{{ route('users.show', $item->user->username) }}"
                                                        class="me-1 fw-bold">{{ $item->user->username }}</a>
                                                    <span
                                                        class="color-gray">{{ $item->created_at->diffForHumans() }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="card card-discussions">
                            Currently no discussion yet
                        </div>
                    @endforelse

                    {{ $discussions->links() }}
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card">
                        <h3>All Categories</h3>
                        <div>
                            @foreach ($categories as $category)
                                <a href="{{ route('discussions.categories.show', $category->slug) }}">
                                    <span class="badge rounded-pill text-bg-light">{{ $category->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
