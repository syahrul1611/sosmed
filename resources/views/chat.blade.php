@extends('layouts.main')
@section('content')
    <div class="card mw-75 mx-auto">
        <div class="card-header">
            <a href="{{ url('/dashboard-'.$to->username) }}" class="link-dark text-decoration-none">
                <img src="{{ asset('storage/'.$to->image) }}" class="rounded-circle" width="36" style="aspect-ratio:1/1;">
                <span>{{ $to->name }}</span>
            </a>
        </div>
        <div class="container p-3 chat-box overflow-auto" style="height: calc(100vh - 17rem);">
        </div>
        <div class="card-footer">
            <form class="message">
                <div class="input-group">
                    <input name="message" type="text" class="form-control" placeholder="Kirim Pesan" autocomplete="off">
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2"><i class="bi bi-send"></i></button>
                </div>
            </form>
        </div>
    </div>
    <script>
        var send = '{{ route('send') }}';
        var get = '{{ route('get') }}';
        var username = '{{ $to->username }}';
        var idFrom = {{ Auth::user()->id }};
    </script>
    <script src="{{ asset('js/chat.js') }}"></script>
@endsection