@extends('layouts.main')
@section('content')
    <form action="">
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="keyword" placeholder="Cari User" aria-describedby="button-addon2">
            <button class="btn btn-secondary" type="submit" id="button-addon2">Cari</button>
        </div>
    </form>

    @if ($users)
        @if (!$users)
            <div class="text-center">User tidak ditemukan</div>
        @else
            <div class="text-center">{{ $users->count() }} user ditemukan</div>
            @foreach ($users as $user)
            <div class="d-flex justify-content-between mb-3 border-bottom border-3 p-2">
                <a href="{{ url('/dashboard-'.$user->username) }}" class="link-dark text-decoration-none d-flex align-items-center gap-2">
                    <img src="{{ asset('storage/'.$user->image) }}" class="rounded-circle" style="max-width: 2.5rem; aspect-ratio:1/1;">
                    <span>{{ $user->name }}</span>
                </a>
                <a href="#" class="btn btn-primary add-friend" data-username="{{ $user->username }}">Tambah</a>
            </div>
            @endforeach
        @endif
    @else
        <div class="d-flex flex-wrap justify-content-center">
            @foreach ($posts as $post)
            <img src="{{ asset('storage/'.$post->image) }}" class="rounded m-1" style="max-width: 15rem" role="button" data-bs-toggle="modal" data-bs-target="#post{{ $post->slug }}">

            <div class="modal fade" id="post{{ $post->slug }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen-sm-down modal-lg mx-auto">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">
                                <a href="{{ url('/dashboard-'.$post->user->username) }}" class="link-dark text-decoration-none">
                                    <img src="{{ asset('storage/'.$post->user->image) }}" class="rounded-circle" width="36">
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
                                <p>
                                    {!! $post->caption !!}
                                </p>
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
                                            <img src="{{ asset('storage/'.$comment->user->image) }}" class="rounded-circle" width="36">
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
    @endif

    <script>
        var friend = '{{ route('addFriend') }}';
        var like = '{{ route('like') }}';
        var comment = '{{ route('comment') }}';
    </script>
    <script src="{{ asset('js/user.js') }}"></script>
    <script src="{{ asset('js/post.js') }}"></script>
@endsection