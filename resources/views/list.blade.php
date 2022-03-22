@extends('layouts.main')
@section('content')
    <div class="px-md-5">
        <div class="card mw-75 mx-auto">
            <div class="card-header pt-4">
                <form action="">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="keyword" placeholder="Cari User" aria-describedby="button-addon2">
                        <button class="btn btn-secondary" type="submit" id="button-addon2">Cari</button>
                    </div>
                </form>
            </div>
            <ul class="list-group list-group-flush">
                @if (!$friends)
                <div class="text-center my-3">User tidak ditemukan</div>
                @else
                    @foreach ($friends as $friend)
                    <div class="d-flex justify-content-between align-items-center my-3 p-2">
                        <a href="{{ url('/chat-'.$friend->user_2->username) }}" class="link-dark text-decoration-none d-flex align-items-center gap-2">
                            <img src="{{ asset('storage/'.$friend->user_2->image) }}" class="rounded-circle" style="max-width: 2.5rem; aspect-ratio:1/1;">
                            <span>{{ $friend->user_2->name }}</span>
                        </a>
                        @if ($friend->user_2->online_status)
                        <span class="badge bg-primary">Online</span>
                        @else
                        <span class="badge bg-secondary">Offline</span>
                        @endif
                    </div>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
@endsection