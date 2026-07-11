<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Walania AI Assistant Canvas</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f4f6f9; margin: 0; padding: 40px 20px; }
        .chat-container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; display: flex; flex-direction: column; height: 600px; }
        .chat-header { background: #006064; color: #fff; padding: 20px; display: flex; justify-content: space-between; align-items: center; }
        .chat-header h2 { margin: 0; font-size: 18px; }
        .chat-messages { flex: 1; padding: 20px; overflow-y: auto; background: #fafafa; display: flex; flex-direction: column; gap: 12px; }
        .msg { max-width: 75%; padding: 10px 14px; border-radius: 8px; font-size: 14px; line-height: 1.4; }
        .msg.user { background: #006064; color: #fff; align-self: flex-end; border-bottom-right-radius: 0; }
        .msg.bot, .msg.agent { background: #e0f7fa; color: #006064; align-self: flex-start; border-bottom-left-radius: 0; }
        .msg.agent { background: #fff3e0; color: #e65100; border: 1px solid #ffe0b2; }
        .chat-input-area { padding: 15px; background: #fff; border-top: 1px solid #eee; display: flex; flex-direction: column; gap: 10px; }
        .suggestion-chips { display: flex; flex-wrap: wrap; gap: 8px; }
        .suggestion-chip { border: 1px solid #b2dfdb; background: #f1fbfb; color: #006064; padding: 8px 12px; border-radius: 999px; cursor: pointer; font-size: 13px; transition: all 0.2s ease; }
        .suggestion-chip:hover { background: #006064; color: #fff; border-color: #006064; }
        .chat-input-row { display: flex; gap: 10px; }
        .chat-input-row input { flex: 1; padding: 12px; border: 1px solid #ddd; border-radius: 6px; outline: none; font-size: 14px; }
        .chat-input-row button { background: #006064; color: white; border: none; padding: 0 20px; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .chat-input-row button:hover { background: #004d40; }
        .status-badge { font-size: 11px; background: rgba(255,255,255,0.2); padding: 4px 8px; border-radius: 20px; }
    </style>
</head>
<body>

<div class="chat-container">
    <div class="chat-header">
        <h2>Walany Assistant</h2>
        <span class="status-badge" id="chatMode"><?= isset($session) && $session['status'] === 'human' ? 'Live Agent Mode' : 'AI Bot Mode' ?></span>
    </div>
    
    <div class="chat-messages" id="chatBox">
        <?php foreach ($history as $msg): ?>
            <div class="msg <?= htmlspecialchars($msg['sender']) ?>">
                <?= htmlspecialchars($msg['message']) ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="chat-input-area">
        <div class="suggestion-chips" aria-label="Suggested questions">
            <?php foreach (($suggestions ?? []) as $suggestion): ?>
                <button type="button" class="suggestion-chip" data-message="<?= htmlspecialchars($suggestion['message'], ENT_QUOTES) ?>">
                    <?= htmlspecialchars($suggestion['label']) ?>
                </button>
            <?php endforeach; ?>
        </div>
        <div class="chat-input-row">
            <input type="text" id="userInput" placeholder="Ask a question or type 'talk to agent'..." onkeypress="if(event.key === 'Enter') sendChatMessage()">
            <button onclick="sendChatMessage()">Send</button>
        </div>
    </div>
</div>

<script>
// Keep the scroll height stuck to the floor on initial template render load
window.onload = function() {
    const box = document.getElementById('chatBox');
    box.scrollTop = box.scrollHeight;
    
    // Set color if loaded on human state mode
    if (document.getElementById('chatMode').innerText === "Live Agent Mode") {
        document.getElementById('chatMode').style.background = "#e65100";
    }

    document.querySelectorAll('.suggestion-chip').forEach(button => {
        button.addEventListener('click', () => {
            const inputEl = document.getElementById('userInput');
            inputEl.value = button.getAttribute('data-message') || '';
            sendChatMessage();
        });
    });
};

function sendChatMessage() {
    const inputEl = document.getElementById('userInput');
    const msgText = inputEl.value.trim();
    if(!msgText) return;

    appendMessage('user', msgText);
    inputEl.value = '';

    fetch('chat.php?action=send', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message: msgText })
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            appendMessage(data.mode, data.reply);
            if(data.mode === 'human') {
                document.getElementById('chatMode').innerText = "Live Agent Mode";
                document.getElementById('chatMode').style.background = "#e65100";
            }
        }
    });
}

function appendMessage(sender, text) {
    const box = document.getElementById('chatBox');
    const msgDiv = document.createElement('div');
    msgDiv.className = `msg ${sender}`;
    msgDiv.innerText = text;
    box.appendChild(msgDiv);
    box.scrollTop = box.scrollHeight;
}
</script>

</body>
</html>