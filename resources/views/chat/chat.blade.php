<!doctype html>
<html lang="en">
<head>
    @if (PEPE)
    <base href="/">
    @else
    <base href="/tutorial/">
    @endif
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Federico Zacayan</title>
  <link href="chat/chat.css" rel="stylesheet" type="text/css"/>
</head>
<body>
  <ul class="pages">
    <li class="chat page">
      <div class="chatArea">
        <ul class="messages"></ul>
      </div>
      <input class="inputMessage" placeholder="Type here..."/>
    </li>
    <li class="login page">
      <div class="form">
        <h3 class="title">What's your nickname?</h3>
        <input class="usernameInput" type="text" maxlength="14" />
      </div>
    </li>
  </ul>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="chat/socket.io-client/socket.io.js"></script>
  <script src="chat/chat.js"></script>
</body>
</html>
