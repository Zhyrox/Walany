<style>
    /* 1. Floating Action Toggle Button */
    .chat-launcher-btn {
        position: fixed;
        bottom: 25px;
        right: 25px;
        width: 60px;
        height: 60px;
        background: #006064;
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(0, 96, 100, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        font-size: 24px;
        transition: transform 0.2s ease, background 0.2s ease;
    }
    .chat-launcher-btn:hover {
        transform: scale(1.05);
        background: #004d40;
    }

    /* 2. Chat Floating Container Component Overlay */
    .chat-widget-wrapper {
        position: fixed;
        bottom: 95px;
        right: 25px;
        width: 380px;
        height: 520px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        z-index: 9999;
        opacity: 0;
        transform: translateY(20px) scale(0.95);
        pointer-events: none;
        transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.1);
    }

    .chat-widget-wrapper.is-active {
        opacity: 1;
        transform: translateY(0) scale(1);
        pointer-events: auto;
    }

    /* 3. Internal Layout */
    .chat-widget-header { 
        background: #006064; 
        color: #fff; 
        padding: 15px; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
    }
    .chat-widget-header h2 { margin: 0; font-size: 16px; font-weight: 600; }
    
    .chat-widget-actions { display: flex; align-items: center; gap: 8px; }
    .chat-widget-clear-btn { 
        background: rgba(255,255,255,0.15); 
        color: white; 
        border: none; 
        padding: 4px 8px; 
        font-size: 11px; 
        font-weight: 500; 
        border-radius: 4px; 
        cursor: pointer; 
        transition: background 0.2s; 
    }
    .chat-widget-clear-btn:hover { background: #c62828; }
    
    .chat-widget-messages { 
        flex: 1; 
        padding: 15px; 
        overflow-y: auto; 
        background: #fafafa; 
        display: flex; 
        flex-direction: column; 
        gap: 10px; 
    }
    
    .chat-widget-msg { 
        max-width: 80%; 
        padding: 10px 12px; 
        border-radius: 8px; 
        font-size: 13px; 
        line-height: 1.4; 
        word-wrap: break-word;
    }
    .chat-widget-msg.user { background: #006064; color: #fff; align-self: flex-end; border-bottom-right-radius: 0; }
    .chat-widget-msg.bot { background: #e0f7fa; color: #006064; align-self: flex-start; border-bottom-left-radius: 0; }
    .chat-widget-msg.agent { background: #fff3e0; color: #e65100; border: 1px solid #ffe0b2; align-self: flex-start; border-bottom-left-radius: 0; }
    
    .chat-widget-input-area { padding: 12px; background: #fff; border-top: 1px solid #eee; display: flex; flex-direction: column; gap: 8px; }
    .chat-widget-chips { display: flex; flex-wrap: wrap; gap: 6px; max-height: 65px; overflow-y: auto; }
    
    .chat-widget-chip { 
        border: 1px solid #b2dfdb; 
        background: #f1fbfb; 
        color: #006064; 
        padding: 5px 10px; 
        border-radius: 999px; 
        cursor: pointer; 
        font-size: 11px; 
        white-space: nowrap;
        transition: all 0.2s ease; 
    }
    .chat-widget-chip:hover { background: #006064; color: white; border-color: #006064; }
    
    .chat-widget-input-row { display: flex; gap: 8px; }
    .chat-widget-input-row input { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 6px; outline: none; font-size: 13px; }
    .chat-widget-input-row button { background: #006064; color: white; border: none; padding: 0 15px; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 13px; }
    .chat-widget-input-row button:hover { background: #004d40; }
    
    .chat-widget-status-badge { font-size: 10px; background: rgba(255,255,255,0.2); padding: 3px 6px; border-radius: 20px; }
</style>

<!-- Floating Action Button Launcher -->
<button class="chat-launcher-btn" id="chatLauncher" onclick="toggleChatWidget()" aria-label="Open chat assistant">
    💬
</button>

<!-- Main Floating Container -->
<div class="chat-widget-wrapper" id="chatWidgetContainer">
    <div class="chat-widget-header">
        <h2>Walania Assistant</h2>
        <div class="chat-widget-actions">
            <button class="chat-widget-clear-btn" onclick="clearWidgetHistory()">Clear</button>
            <span class="chat-widget-status-badge" id="chatModeWidget"><?= isset($activeSession) && $activeSession['status'] === 'human' ? 'Live Agent Mode' : 'AI Bot Mode' ?></span>
        </div>
    </div>
    
    <div class="chat-widget-messages" id="chatWidgetBox">
        <?php foreach (($history ?? []) as $msg): ?>
            <div class="chat-widget-msg <?= htmlspecialchars($msg['sender']) ?>">
                <?= htmlspecialchars($msg['message']) ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="chat-widget-input-area">
        <div class="chat-widget-chips" aria-label="Suggested prompt options">
            <?php foreach (($suggestions ?? []) as $suggestion): ?>
                <button type="button" class="chat-widget-chip" data-message="<?= htmlspecialchars($suggestion['message'], ENT_QUOTES) ?>">
                    <?= htmlspecialchars($suggestion['label']) ?>
                </button>
            <?php endforeach; ?>
        </div>
        <div class="chat-widget-input-row">
            <input type="text" id="widgetUserInput" placeholder="Type a message..." onkeypress="if(event.key === 'Enter') sendWidgetChatMessage()">
            <button onclick="sendWidgetChatMessage()">Send</button>
        </div>
    </div>
</div>

<script>
let isSendingWidgetMsg = false;
let isPollingUserChat = false;

document.addEventListener("DOMContentLoaded", function() {
    const box = document.getElementById('chatWidgetBox');
    if (box) box.scrollTop = box.scrollHeight;
    
    const modeBadge = document.getElementById('chatModeWidget');
    if (modeBadge && modeBadge.innerText === "Live Agent Mode") {
        modeBadge.style.background = "#e65100";
    }

    document.addEventListener('click', function(e) {
        const chip = e.target.closest('.chat-widget-chip');
        if (chip) {
            e.preventDefault();
            const msg = chip.getAttribute('data-message') || chip.innerText.trim();
            if (msg) {
                const inputEl = document.getElementById('widgetUserInput');
                if (inputEl) inputEl.value = msg;
                sendWidgetChatMessage();
            }
        }
    });
});

function toggleChatWidget() {
    const container = document.getElementById('chatWidgetContainer');
    const launcher = document.getElementById('chatLauncher');
    
    if (!container) return;
    container.classList.toggle('is-active');
    
    if (container.classList.contains('is-active')) {
        if (launcher) launcher.innerText = '✕';
        const inputEl = document.getElementById('widgetUserInput');
        if (inputEl) inputEl.focus();
        
        // Immediate fetch when opening
        pollUserChatHistory(true);
    } else {
        if (launcher) launcher.innerText = '💬';
    }
}

function sendWidgetChatMessage() {
    if (isSendingWidgetMsg) return;

    const inputEl = document.getElementById('widgetUserInput');
    const msgText = inputEl ? inputEl.value.trim() : '';
    if (!msgText) return;

    isSendingWidgetMsg = true;

    // Clear input immediately for snappy UI
    if (inputEl) inputEl.value = '';

    fetch('/Walany/chat.php?action=send', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message: msgText })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            // Re-fetch entire history from DB cleanly to avoid local duplicate bugs
            pollUserChatHistory(true);

            const modeBadge = document.getElementById('chatModeWidget');
            if (modeBadge && data.mode === 'human') {
                modeBadge.innerText = "Live Agent Mode";
                modeBadge.style.background = "#e65100";
            }
        }
    })
    .catch(err => console.error("Error sending message:", err))
    .finally(() => {
        isSendingWidgetMsg = false;
    });
}

function clearWidgetHistory() {
    if (!confirm("Clear your chat conversation history context?")) return;

    fetch('/Walany/chat.php?action=clear', { method: 'POST' })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            const box = document.getElementById('chatWidgetBox');
            if (box) box.innerHTML = '';
            
            const modeBadge = document.getElementById('chatModeWidget');
            if (modeBadge) {
                modeBadge.innerText = "AI Bot Mode";
                modeBadge.style.background = "rgba(255,255,255,0.2)";
            }
        }
    });
}

function appendWidgetMessage(sender, text) {
    const box = document.getElementById('chatWidgetBox');
    if (!box) return;
    
    const msgDiv = document.createElement('div');
    msgDiv.className = `chat-widget-msg ${sender}`;
    msgDiv.innerText = text;
    box.appendChild(msgDiv);
    box.scrollTop = box.scrollHeight;
}

// Single Source of Truth for Chat History
function pollUserChatHistory(forceRefresh = false) {
    if (isPollingUserChat) return;

    const container = document.getElementById('chatWidgetContainer');
    if (!container || !container.classList.contains('is-active')) return;

    isPollingUserChat = true;

    fetch(`/Walany/chat.php?action=get_history&_t=${Date.now()}`)
        .then(res => res.json())
        .then(data => {
            const box = document.getElementById('chatWidgetBox');
            if (!box || !Array.isArray(data)) return;

            const messages = data;
            const currentDomCount = box.querySelectorAll('.chat-widget-msg').length;

            // Render/re-render if forced or if backend row count doesn't match DOM count
            if (forceRefresh || messages.length !== currentDomCount) {
                box.innerHTML = ''; // Wipe DOM cleanly once on true state change

                messages.forEach(m => {
                    const msgDiv = document.createElement('div');
                    msgDiv.className = `chat-widget-msg ${m.sender}`;
                    msgDiv.innerText = m.message;
                    box.appendChild(msgDiv);
                });

                box.scrollTop = box.scrollHeight;
            }
        })
        .catch(err => console.error("Error polling chat history:", err))
        .finally(() => {
            isPollingUserChat = false;
        });
}

// Background Polling Loop (Every 3.5 seconds)
setInterval(() => {
    pollUserChatHistory(false);
}, 3500);
</script>