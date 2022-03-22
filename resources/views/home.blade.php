@extends('layouts.main')

@section('content')

    <div class="px-md-5">
        @if (session()->has('uploaded'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('uploaded') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @foreach ($posts->sortByDesc('created_at') as $post)
        <div class="card mx-auto mw-75 mb-5">
            <div class="card-header">
                <a href="{{ url('/dashboard-'.$post->user->username) }}" class="link-dark text-decoration-none">
                    <img src="{{ asset('storage/'.$post->user->image) }}" class="rounded-circle" width="36" style="aspect-ratio:1/1;">
                    <span>{{ $post->user->name }}</span>
                </a>
            </div>
            <img src="{{ asset('storage/'.$post->image) }}" class="card-img-top h-auto w-auto" role="button" data-bs-toggle="modal" data-bs-target="#post{{ $post->id }}">
            <div class="d-flex justify-content-between px-3">
                <span class="likeCount">{{ $post->likes->count() }} Suka</span>
                @if ($post->likes->firstWhere('user_id',Auth::user()->id))
                <a href="#" class="like link-dark text-decoration-none"><i class="bi bi-heart-fill fs-3" data-slug="{{ $post->slug }}" style="color: red"></i></a>
                @else
                <a href="#" class="like link-dark text-decoration-none"><i class="bi bi-heart-fill fs-3" data-slug="{{ $post->slug }}" style="color: black"></i></a>
                @endif
            </div>
            <div class="card-body" role="button" data-bs-toggle="modal" data-bs-target="#post{{ $post->id }}">
                {!! $post->caption !!}
            </div>
        </div>
        <div class="modal fade" id="post{{ $post->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen-sm-down modal-lg mx-auto">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">
                            <a href="{{ url('/dashboard-'.$post->user->username) }}" class="link-dark text-decoration-none">
                                <img src="{{ asset('storage/'.$post->user->image) }}" class="rounded-circle" width="36" style="aspect-ratio:1/1;">
                                <span>{{ $post->user->name }}</span>
                            </a>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row">
                        <div class="col-sm-6">
                            <img src="{{ asset('storage/'.$post->image) }}" class="w-100">
                            <div class="d-flex justify-content-between">
                                <span class="likeCount">{{ $post->likes->count() }} Suka</span>
                                <a href="#" class="like link-dark text-decoration-none"><i class="bi bi-heart-fill fs-3" data-slug="{{ $post->slug }}" style="color: {{ ($post->likes->contains('user_id',Auth::user()->id)) ? 'red' : 'black' }}"></i></a>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            {!! $post->caption !!}
                            <hr class="w-100">
                            <form class="comment" data-slug="{{ $post->slug }}">
                                <div class="input-group mb-3">
                                    <input name="comment" type="text" class="form-control" placeholder="Kirim komentar" autocomplete="off">
                                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Kirim</button>
                                </div>
                            </form>
                            <ul class="list-unstyled overflow-auto" style="max-height: 250px">
                                @foreach ($post->comments->sortByDesc('created_at') as $comment)
                                <li class="mb-3 border-bottom">
                                    <a href="{{ url('/dashboard-'.$comment->user->username) }}" class="link-dark text-decoration-none">
                                        <img src="{{ asset('storage/'.$comment->user->image) }}" class="rounded-circle" width="36" style="aspect-ratio:1/1;">
                                        <span>{{ $comment->user->name }}</span>
                                    </a>
                                    <p class="position-relative">
                                        <span class="position-absolute end-0 pe-1 comment-style">{{ $comment->created_at->diffForHumans() }}</span>
                                        {{ $comment->comment }}
                                    </p>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <script>
        var like = '{{ route('like') }}';
        var comment = '{{ route('comment') }}';
    </script>
    <script src="{{ asset('js/post.js') }}"></script>
@endsection
