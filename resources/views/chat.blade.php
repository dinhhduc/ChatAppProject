<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Chat App</h1>
    <div style="display: flex;">
        <div style="width: 200px;">
            <h3>Users</h3>
            <ul id="user-list">
                @foreach($users as $user)
                    <li><a href="#" class="user-item" data-id="{{ $user->id }}">{{ $user->name }}</a></li>
                @endforeach
            </ul>
        </div>
        <div style="flex: 1; margin-left: 20px;">
            <h3>Messages</h3>
            <div id="messages" style="border:1px solid #ccc; height:300px; overflow-y:scroll; margin-bottom:10px;"></div>
            <form id="message-form" style="display:none;">
                <input type="text" id="message-input" placeholder="Type a message..." autocomplete="off">
                <button type="submit">Send</button>
            </form>
        </div>
    </div>
    <script>
        let selectedUser = null;
        $(function() {
            $('.user-item').click(function(e) {
                e.preventDefault();
                selectedUser = $(this).data('id');
                fetchMessages();
                $('#message-form').show();
            });
            $('#message-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/messages',
                    method: 'POST',
                    data: {
                        to: selectedUser,
                        message: $('#message-input').val(),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        $('#message-input').val('');
                        fetchMessages();
                    }
                });
            });
        });
        function fetchMessages() {
            $.ajax({
                url: '/messages',
                method: 'GET',
                data: { to: selectedUser },
                success: function(data) {
                    let html = '';
                    data.forEach(function(msg) {
                        html += '<div><b>' + (msg.from == {{ auth()->id() ?? 0 }} ? 'Me' : 'Them') + ':</b> ' + msg.message + '</div>';
                    });
                    $('#messages').html(html);
                }
            });
        }
    </script>
</body>
</html>
