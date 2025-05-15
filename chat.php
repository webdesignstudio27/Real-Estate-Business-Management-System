<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Chat Support</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Same styling as before */
        .chat-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            text-align: center;
            z-index: 9999;
        }

        .chat-button {
            display: inline-block;
            padding: 12px 15px;
            font-size: 20px;
            color: white;
            background-color: #007bff;
            border-radius: 50%;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }

        .chat-button:hover {
            background-color: #0056b3;
        }

        .message-box {
            visibility: hidden;
            opacity: 0;
            position: fixed;
            bottom: 80px;
            right: 20px;
            width: 300px;
            height: 350px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.4s ease-in-out;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .message-box.active {
            visibility: visible;
            opacity: 1;
        }

        .chat-header {
            background: #007bff;
            color: white;
            padding: 10px;
            font-size: 16px;
            text-align: center;
            font-weight: bold;
            position: relative;
        }

        .close-btn {
            position: absolute;
            right: 10px;
            cursor: pointer;
            font-size: 18px;
            color: white;
        }

        .close-btn:hover {
            color: #ddd;
        }

        .chat-body {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
        }

        .chat-footer {
            display: flex;
            padding: 10px;
            background: #fff;
            border-top: 1px solid #ccc;
        }

        .chat-footer input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .chat-footer button {
            margin-left: 5px;
            padding: 8px 10px;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .chat-footer button:hover {
            background: #0056b3;
        }

        .message {
            max-width: 75%;
            word-wrap: break-word;
            margin-bottom: 8px;
            padding: 8px 12px;
            border-radius: 15px;
        }

        .sent {
            background: #007bff;
            color: white;
            align-self: flex-end;
        }

        .received {
            background: #e5e5ea;
            color: black;
            align-self: flex-start;
        }

        .timestamp {
            font-size: 10px;
            color: #ccc;
            display: block;
            text-align: right;
            margin-top: 3px;
        }
    </style>
</head>

<body>

    <div class="chat-container">
        <a href="#" class="chat-button" id="chatBtn">
            <i class="fa fa-comment"></i>
        </a>

        <div class="message-box" id="messageBox">
            <div class="chat-header">
                <span>Chat with Agent</span>
                <span class="close-btn" onclick="hideMessageBox()">&times;</span>
            </div>

            <div class="chat-body" id="chatBody"></div>

            <div class="chat-footer">
                <input type="text" id="chatInput" placeholder="Type a message...">
                <button onclick="sendMessage()">Send</button>
            </div>
        </div>
    </div>

    <script>
        const chatBtn = document.getElementById("chatBtn");
        const messageBox = document.getElementById("messageBox");
        const chatBody = document.getElementById("chatBody");
        const chatInput = document.getElementById("chatInput");

        chatBtn.addEventListener("click", function() {
            messageBox.classList.toggle("active");
            if (messageBox.classList.contains("active")) {
                loadChatHistory();
            }
        });

        function hideMessageBox() {
            messageBox.classList.remove("active");
        }

        function sendMessage() {
            const message = chatInput.value.trim();
            if (message === "") return;

            const formData = new FormData();
            formData.append("message", message);

            fetch("chat_send.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.status === "success") {
                        const msgElement = document.createElement("div");
                        msgElement.className = "message sent";
                        msgElement.innerHTML = `${message}<span class="timestamp">${getCurrentTime()}</span>`;
                        chatBody.appendChild(msgElement);
                        chatInput.value = "";
                        chatBody.scrollTop = chatBody.scrollHeight;
                    } else {
                        alert("Message not sent.Please Login");
                        window.location='login.php';

                        
                    }
                });
        }

        function loadChatHistory() {
            fetch("chat_fetch.php")
                .then(response => response.json())
                .then(data => {
                    chatBody.innerHTML = "";
                    data.forEach(msg => {
                        if (msg.send_msg) {
                            chatBody.innerHTML += `
                        <div class="message sent">
                            ${msg.send_msg}
                            <span class="timestamp">${formatTime(msg.send_at)}</span>
                        </div>`;
                        }
                        if (msg.receive_msg) {
                            chatBody.innerHTML += `
                        <div class="message received">
                            ${msg.receive_msg}
                            <span class="timestamp">${formatTime(msg.receive_at)}</span>
                        </div>`;
                        }
                    });
                    chatBody.scrollTop = chatBody.scrollHeight;
                });
        }


        function formatTime(timeStr) {
            if (!timeStr || typeof timeStr !== 'string') return 'Invalid Time';

            timeStr = timeStr.trim();
            const parts = timeStr.split(':');
            if (parts.length < 2) return 'Invalid Time';

            const hours = parts[0].padStart(2, '0');
            const minutes = parts[1].padStart(2, '0');

            return `${hours}:${minutes}`;
        }


        function formatTimeTo12Hour(timeStr) {
            if (!timeStr || typeof timeStr !== 'string') return 'Invalid Time';

            const parts = timeStr.trim().split(':');
            if (parts.length < 2) return 'Invalid Time';

            let hours = parseInt(parts[0], 10);
            const minutes = parts[1].padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12 || 12; // convert to 12-hour format

            return `${hours}:${minutes} ${ampm}`;
        }



        function getCurrentTime() {
            const now = new Date();
            return now.getHours().toString().padStart(2, '0') + ":" +
                now.getMinutes().toString().padStart(2, '0');
        }
    </script>

</body>

</html>