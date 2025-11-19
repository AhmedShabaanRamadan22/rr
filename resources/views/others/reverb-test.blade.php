<!DOCTYPE html>

<head>
    <title>Reverb Test</title>
          <!-- Include Pusher JS -->
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.0.1/dist/web/pusher.min.js"></script>

    <!-- Include Laravel Echo -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.17.1/echo.iife.min.js" integrity="sha512-+niSJwvEHJjkzsB/dPujR2RRenWKIx7jZ/R6Q1XVY3ZmQ1s6BN5coO9smFctXZ29kjGO98vJ0Rx+K+n3pFkWMw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    <h1>Reverb Test</h1>
    <p>
        Try publishing an event to the channels <strong>chat & chat.544</strong>
        with event name <strong>newMessage</strong> for both.
    </p>
    <form id="eventForm">
        <input type="text" id="messageInput">
        <input type="number" id="userIdInput" value='544'>
        <button type="submit">send</button>
    </form>

    <script>
        window.Pusher = Pusher;
        const token = 'api_key';
        let forceTLS = window.location.protocol == 'https:';

        window.Echo = new Echo({
            broadcaster: 'reverb'
            , key: 'bbkkqujfoootjt7ts8ad'
            , wsHost: window.location.hostname
            , wsPort: '8080'
            , wssPort: '443'
            , forceTLS: forceTLS
            , enabledTransports: ['ws', 'wss']
            // or for api requests do this:
            , auth: {
                headers: {
                  Authorization: `Bearer ${token}`,
                },
              },
        });

        //public channel
        Echo.channel("chat").listen(".newMessage", (data) => {
            const text = document.createElement("p");
            text.innerText = 'Public: ' + data.message;
            document.body.appendChild(text);
        });

        //private channel replace the '544' with the user id
        Echo.private('chat.544').listen('.newMessage', function(data) {
            const text = document.createElement("p");
            text.innerText = 'Private: ' + data.message;
            document.body.appendChild(text);
        });
        document.getElementById('eventForm').addEventListener('submit', function(event){
            
            event.preventDefault();
            messageInput = document.getElementById('messageInput');
            userIdInput = document.getElementById('userIdInput');
            message = messageInput.value;
            userId = userIdInput.value;
            if (!message || !userId) return;
            messageInput.value = '';
            messageInput.disabled = true;

            fetch(`/admin/reverb/send?message=${message}&userId=${userId}`);

            messageInput.disabled = false;
        })

    </script>
</body>

</html>
