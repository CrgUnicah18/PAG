<!-- resources/views/components/notify-messages.blade.php -->
<div>
    @foreach($messages as $message)
        <div class="notify {{ $message['type'] == 'success' ? 'notify-success' : 'notify-error' }}">
            <span>{{ $message['message'] }}</span>
        </div>
    @endforeach
</div>