<?php
require_once __DIR__ . '/../../../core/Database.php';
require_once __DIR__ . '/../../Chatbot/Models/ChatSessions.php';

$dbInstance = new Database();
$chatModel = new ChatSession($dbInstance->getConnection());
$escalatedSessions = $chatModel->getEscalatedSessions();
?>

<div class="admin-chat-dashboard" style="display: flex; height: 80vh; font-family: sans-serif; border: 1px solid #ddd;">
    
    <!-- Sidebar Queue List -->
    <div class="session-sidebar" style="width: 30%; border-right: 1px solid #ddd; overflow-y: auto; background: #f8f9fa;">
        <h3 style="padding: 15px; margin: 0; background: #004d40; color: white;">Escalated Chats</h3>
        <div id="sessionQueueList">
            <?php if (empty($escalatedSessions)): ?>
                <div style="padding: 15px; color: #888; font-size: 13px;">No chats currently waiting for agent assistance.</div>
            <?php else: ?>
                <?php foreach ($escalatedSessions as $s): ?>
                    <div class="queue-item" onclick="loadAdminChat(<?= $s['id'] ?>)" style="padding: 15px; border-bottom: 1px solid #eee; cursor: pointer;">
                        <strong>Session #<?= $s['id'] ?></strong>
                        <div style="font-size: 11px; color: #666;">Waiting since: <?= htmlspecialchars($s['created_at'] ?? 'N/A') ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Active Chat Workspace -->
    <div class="chat-workspace" style="width: 70%; display: flex; flex-direction: column;">
        <div id="chatWorkspaceHeader" style="padding: 15px; background: #006064; color: white; font-weight: bold;">
            Select a session from the left queue to begin chatting
        </div>
        
        <div id="adminMessageLogs" style="flex: 1; padding: 20px; overflow-y: auto; background: #fafafa; display: flex; flex-direction: column; gap: 12px;">
            <!-- Thread messages dynamically loaded via AJAX -->
        </div>

        <div class="admin-input-tray" style="padding: 15px; border-top: 1px solid #ddd; display: flex; gap: 10px;">
            <input type="hidden" id="activeAdminSessionId" value="">
            <input type="text" id="adminReplyInput" placeholder="Type resolution message here..." style="flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" onkeypress="if(event.key==='Enter') sendAdminResponse()">
            <button onclick="sendAdminResponse()" style="background: #006064; color: white; border: none; padding: 0 20px; border-radius: 4px; cursor: pointer;">Send to User</button>
            <button onclick="resolveSessionCall()" style="background: #2e7d32; color: white; border: none; padding: 0 15px; border-radius: 4px; cursor: pointer;">Resolve</button>
        </div>
    </div>
</div>

<script>
let activeSessionId = null;

function loadAdminChat(sessionId) {
    activeSessionId = sessionId;
    document.getElementById('activeAdminSessionId').value = sessionId;
    document.getElementById('chatWorkspaceHeader').innerText = "Responding to Session #" + sessionId;

    fetch(`chat.php?action=get_history&session_id=${sessionId}`)
    .then(res => res.json())
    .then(messages => {
        const log = document.getElementById('adminMessageLogs');
        log.innerHTML = '';
        if (Array.isArray(messages)) {
            messages.forEach(m => {
                const div = document.createElement('div');
                div.innerText = `${m.sender.toUpperCase()}: ${m.message}`;
                div.style.padding = '8px 12px';
                div.style.borderRadius = '6px';
                div.style.maxWidth = '70%';
                if(m.sender === 'user') {
                    div.style.background = '#e0f7fa';
                    div.style.alignSelf = 'flex-start';
                } else {
                    div.style.background = '#ffe0b2';
                    div.style.alignSelf = 'flex-end';
                }
                log.appendChild(div);
            });
        }
        log.scrollTop = log.scrollHeight;
    })
    .catch(err => console.error("Error loading chat history:", err));
}

function sendAdminResponse() {
    const input = document.getElementById('adminReplyInput');
    const text = input.value.trim();
    if (!text || !activeSessionId) return;

    fetch('chat.php?action=admin_reply', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ session_id: activeSessionId, message: text })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            input.value = '';
            loadAdminChat(activeSessionId);
        }
    });
}

function resolveSessionCall() {
    if(!activeSessionId || !confirm("Return this user session back to AI Bot control?")) return;
    
    fetch('chat.php?action=resolve_session', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ session_id: activeSessionId })
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            location.reload();
        }
    });
}

setInterval(() => {
    if(activeSessionId) loadAdminChat(activeSessionId);
}, 4000);
</script>