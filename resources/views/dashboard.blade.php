@extends('layouts.main')

@section('content')
<div class="container px-md-5">
    {{-- session --}}
    @if (session()->has('edited'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('edited') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if (session()->has('postEdit'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('postEdit') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    {{-- info profile --}}
    <div class="card mb-3 mx-auto position-relative p-1" style="max-width: 540px;">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="{{ asset('storage/'.$user->image) }}" class="img-fluid rounded-circle d-block mx-auto align-center mt-3" style="max-width: 7rem; aspect-ratio: 1/1;">
                @if ($user->id == Auth::user()->id)
                <button type="button" class="btn btn-info mx-auto d-block mt-3" data-bs-toggle="modal" data-bs-target="#edit">
                    Edit
                </button>
                @endif
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ $user->name }}</h5>
                    <div class="d-flex justify-content-evenly">
                        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#followed">
                            Diikuti
                            <br>
                            {{ $user->followed->where('status',true)->count() }}
                        </button>
                        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#following">
                            Mengikuti
                            <br>
                            {{ $user->following->where('status',true)->count() }}
                        </button>
                    </div>
                    <p class="card-text">{!! $user->bio !!}</p>
                </div>
            </div>
        </div>
        @if ($user->id == Auth::user()->id)
        <button type="button" class="btn btn-danger position-absolute top-0 end-0" data-bs-toggle="modal" data-bs-target="#logout">
            Logout
        </button>
        @endif
    </div>
    {{-- posts user --}}
    <div class="d-flex flex-wrap justify-content-center">
        @foreach ($user->posts->sortByDesc('created_at') as $post)
        <img src="{{ asset('storage/'.$post->image) }}" class="rounded m-1" style="max-width: 15rem" role="button" data-bs-toggle="modal" data-bs-target="#post{{ $post->slug }}">
        {{-- modal post --}}
        <div class="modal fade" id="post{{ $post->slug }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen-sm-down modal-lg mx-auto">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">
                            <a href="{{ url('/dashboard-'.$post->user->username) }}" class="link-dark text-decoration-none">
                                <img src="{{ asset('storage/'.$post->user->image) }}" class="rounded-circle" width="36" style="aspect-ratio:1/1">
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
                                <a href="#" id="{{ $loop->index }}" class="like link-dark text-decoration-none"><i class="bi bi-heart-fill fs-3" data-slug="{{ $post->slug }}" style="color: {{ ($post->likes->contains('user_id',Auth::user()->id)) ? 'red' : 'black' }}"></i></a>
                            </div>
                            @if ($user->id == Auth::user()->id)
                            <a href="#" class="btn btn-danger delete w-100" data-bs-toggle="modal" data-bs-target="#delete{{ $post->slug }}">Delete</a>
                            <a href="#" class="mt-3 btn btn-warning delete w-100" data-bs-toggle="modal" data-bs-target="#edit{{ $post->slug }}">Ubah</a>
                            @endif
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
                                        <img src="{{ asset('storage/'.$comment->user->image) }}" class="rounded-circle" width="36" style="aspect-ratio:1/1">
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
        @if ($user->id == Auth::user()->id)
        {{-- modal delete post --}}
        <div class="modal fade" id="delete{{ $post->slug }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Yakin ingin menghapus postingan?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <form action="{{ route('delete') }}" method="post">
                            @csrf
                            <input type="hidden" name="slug" value="{{ $post->slug }}">
                            <button type="submit" class="btn btn-danger">Iya</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach
        @if ($user->id == Auth::user()->id)
        @foreach ($user->posts->sortByDesc('created_at') as $modal)
            {{-- modal edit post --}}
            <div class="modal fade" id="edit{{ $modal->slug }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="{{ $modal->slug }}Label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="{{ $modal->slug }}Label">Ubah postingan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('editPost') }}" method="POST" enctype="multipart/form-data">
                                @method('put')
                                @csrf
                                <input type="hidden" name="slug" value="{{ $modal->slug }}">
                                <input type="hidden" name="oldImage" value="{{ $modal->image }}">
                                <label class="preview-img" for="{{ $loop->index }}">
                                    <img class="preview" src="{{ asset('storage/'.$modal->image) }}">
                                    <input id="{{ $loop->index }}" type="file" name="image" accept="image/*" class="image @error('image') is-invalid @enderror">
                                    @error('image')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </label>
                                <label for="caption-{{ $loop->iteration }}" class="mt-3 d-block">Caption (maks: 1000):
                                    <input id="caption-{{ $loop->iteration }}" type="hidden" name="caption" value="{{ old('caption',$modal->caption) }}" required>
                                    <trix-editor input="caption-{{ $loop->iteration }}"></trix-editor>
                                    @error('caption')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    <div class="invalid-feedback max-char">
                                        Mencapai batas karakter.
                                    </div>
                                </label>
                                <button class="btn btn-info mt-4 d-block m-auto" type="submit">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        @endif
    </div>
    @if ($user->id == Auth::user()->id)
    {{-- modal logout --}}
    <div class="modal fade" id="logout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Yakin ingin keluar?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-danger">Iya</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- modal edit profile --}}
    <div class="modal fade" id="edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('edit') }}" method="post" enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        <label class="preview-img" for="image-profile">
                            <input type="hidden" name="oldImage" value="{{ $user->image }}">
                            <img id="preview-profile" class="rounded-circle" src="{{ asset('storage/'.$user->image) }}">
                            <input id="image-profile" type="file" name="image" accept="image/*" class="@error('image') is-invalid @enderror" onchange="previewProfile()">
                            @error('image')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </label>
                        <label for="name" class="mt-3 d-block" >Nama
                            <input id="name" class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name',$user->name) }}">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </label>
                        <label for="bio" class="mt-3 d-block">Bio (maks: 1000)
                            <input id="bio" type="hidden" name="bio" value="{{ old('bio',$user->bio) }}" required>
                            <trix-editor input="bio"></trix-editor>
                            <div class="invalid-feedback max-char">
                                Mencapai batas karakter.
                            </div>
                        </label>
                        <button class="btn btn-info mt-4 d-block m-auto" type="submit">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    {{-- modal followed --}}
    <div class="modal fade" id="followed" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="followedLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="followedLabel">Pengikut</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach ($user->followed->where('status',true) as $fld)
                    <div class="d-flex justify-content-between mb-3 p-2">
                        <a href="{{ url('/dashboard-'.$fld->user_1->username) }}" class="link-dark text-decoration-none d-flex align-items-center gap-2">
                            <img src="{{ asset('storage/'.$fld->user_1->image) }}" class="rounded-circle" style="max-width: 2.5rem; aspect-ratio:1/1">
                            <span>{{ $fld->user_1->name }}</span>
                        </a>
                    </div>
                    @endforeach
                    @if ($user->id == Auth::user()->id)
                    <hr>
                    @foreach ($user->followed->where('status',false) as $fld)
                    <div class="d-flex justify-content-between mb-3 p-2">
                        <a href="{{ url('/dashboard-'.$fld->user_1->username) }}" class="link-dark text-decoration-none d-flex align-items-center gap-2">
                            <img src="{{ asset('storage/'.$fld->user_1->image) }}" class="rounded-circle" style="max-width: 2.5rem; aspect-ratio:1/1">
                            <span>{{ $fld->user_1->name }}</span>
                        </a>
                        <div>
                            <button type="button" class="btn btn-success rounded px-3 py-1 accept" data-username="{{ $fld->user_1->username }}">Terima</button>
                            <button type="button" class="btn btn-danger rounded px-3 py-1 reject" data-username="{{ $fld->user_1->username }}">Tolak</button>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- modal following --}}
    <div class="modal fade" id="following" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="followingLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="followingLabel">Mengikuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach ($user->following->where('status',true) as $flw)
                    <div class="d-flex justify-content-between mb-3 p-2">
                        <a href="{{ url('/dashboard-'.$flw->user_2->username) }}" class="link-dark text-decoration-none d-flex align-items-center gap-2">
                            <img src="{{ asset('storage/'.$flw->user_2->image) }}" class="rounded-circle" style="max-width: 2.5rem; aspect-ratio:1/1">
                            <span>{{ $flw->user_2->name }}</span>
                        </a>
                    </div>
                    @endforeach
                    @if ($user->id == Auth::user()->id)
                    <hr>
                    <p>Menunggu persetujuan</p>
                    @foreach ($user->following->where('status',false) as $flw)
                    <div class="d-flex justify-content-between mb-3 p-2">
                        <a href="{{ url('/dashboard-'.$flw->user_2->username) }}" class="link-dark text-decoration-none d-flex align-items-center gap-2">
                            <img src="{{ asset('storage/'.$flw->user_2->image) }}" class="rounded-circle" style="max-width: 2.5rem; aspect-ratio:1/1">
                            <span>{{ $flw->user_2->name }}</span>
                        </a>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var acc = '{{ route('acc') }}';
    var rjc = '{{ route('rjc') }}';
    var like = '{{ route('like') }}';
    var comment = '{{ route('comment') }}';
</script>
<script src="{{ asset('js/post.js') }}"></script>
<script src="{{ asset('js/user.js') }}"></script>
<script src="{{ asset('js/updatePreview.js') }}"></script>
@endsection