<!-- resources/views/vendor/notify/messages.blade.php -->
@if (session('notify'))
    @foreach (session('notify') as $notification)
        <div class="notify {{ $notification['type'] }}">
            <p>{{ $notification['message'] }}</p>
        </div>
    @endforeach
@endif