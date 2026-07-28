<?php
require_once __DIR__ . '/../../../core/Database.php';
require_once __DIR__ . '/../../Chatbot/Models/ChatSessions.php';

$dbInstance = new Database();
$chatModel = new ChatSession($dbInstance->getConnection());
$escalatedSessions = $chatModel->getEscalatedSessions();
?>

<style>
/* Modal Overlay */
.admin-modal-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

/* Modal Box */
.admin-modal-content {
    background: #ffffff;
    width: 480px;
    max-width: 90%;
    max-height: 85vh;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.admin-modal-header {
    padding: 16px 20px;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.admin-modal-header h3 { margin: 0; font-size: 1.1rem; color: #333; }
.close-modal-btn { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #666; }

.admin-modal-body {
    padding: 15px;
    overflow-y: auto;
}

/* Session Item Cards */
.session-item {
    padding: 12px 15px;
    margin-bottom: 10px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.session-item:hover {
    background: #e3f2fd;
    border-color: #90caf9;
}

/* Admin Workspace Layout */
#adminMessageLogs {
    display: flex;
    flex-direction: column;
    gap: 8px;
    height: 300px;
    overflow-y: auto;
    padding: 10px;
    background: #fafafa;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    margin-bottom: 12px;
}

.chat-input-row {
    display: flex;
    gap: 8px;
}

.chat-input-row input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    outline: none;
}

.chat-input-row button {
    padding: 10px 16px;
    background: #00838f;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}

.resolve-btn {
    background: #d32f2f !important;
}
</style>

<!-- Communication Hub Card (Pinned Button) -->
<div class="pinned-action-card" onclick="openSessionListModal()" style="cursor: pointer;">
    <div class="icon-bubble">💬</div>
    <div class="action-details">
        <div class="action-title">
            Human Takeover <span class="badge">LIVE</span>
        </div>
        <div class="action-subtext">Intervene in live student bot chats</div>
    </div>
</div>

<!-- 1. SESSION LIST MODAL -->
<div id="sessionListModal" class="admin-modal-overlay" style="display: none;">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h3>Active Chat Sessions</h3>
            <button class="close-modal-btn" onclick="closeSessionListModal()">&times;</button>
        </div>
        <div class="admin-modal-body">
            <div id="sessionsListContainer">
                <p class="loading-text">Loading chat sessions...</p>
            </div>
        </div>
    </div>
</div>

<!-- 2. LIVE CHAT WORKSPACE MODAL (This was missing!) -->
<div id="adminChatWorkspace" class="admin-modal-overlay" style="display: none;">
    <div class="admin-modal-content" style="width: 550px;">
        <div class="admin-modal-header">
            <h3 id="chatWorkspaceHeader">Responding to Session</h3>
            <button class="close-modal-btn" onclick="closeChatWorkspace()">&times;</button>
        </div>
        <div class="admin-modal-body">
            <input type="hidden" id="activeAdminSessionId" value="" />
            
            <!-- Message Logs Box -->
            <div id="adminMessageLogs"></div>
            
            <!-- Input & Actions -->
            <div class="chat-input-row">
                <input type="text" id="adminReplyInput" placeholder="Type response as agent..." onkeypress="if(event.key==='Enter') sendAdminResponse()" />
                <button onclick="sendAdminResponse()">Send</button>
                <button class="resolve-btn" onclick="resolveSessionCall()" title="Return session to AI Bot">Release</button>
            </div>
        </div>
    </div>
</div>

<script>
let activeSessionId = null;
let isLoadingHistory = false;

// ==========================================
// 1. MODAL & SESSION LIST FUNCTIONS
// ==========================================

function openSessionListModal() {
    const modal = document.getElementById('sessionListModal');
    if (modal) modal.style.display = 'flex';
    fetchChatSessions();
}

function closeSessionListModal() {
    const modal = document.getElementById('sessionListModal');
    if (modal) modal.style.display = 'none';
}

function closeChatWorkspace() {
    const workspace = document.getElementById('adminChatWorkspace');
    if (workspace) workspace.style.display = 'none';
    activeSessionId = null; // Stop 4-second polling loop when window is closed
}

function fetchChatSessions() {
    const container = document.getElementById('sessionsListContainer');
    if (!container) return;
    
    container.innerHTML = '<p style="text-align:center; padding:15px; color:#666;">Loading chat sessions...</p>';

    fetch('/Walany/chat.php?action=get_active_sessions')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success' && Array.isArray(data.sessions) && data.sessions.length > 0) {
                container.innerHTML = '';
                data.sessions.forEach(session => {
                    const div = document.createElement('div');
                    div.className = 'session-item';
                    
                    if (session.id == activeSessionId) {
                        div.style.borderLeft = '4px solid #00838f';
                        div.style.background = '#e0f7fa';
                    }

                    div.onclick = () => {
                        closeSessionListModal();
                        loadAdminChat(session.id);
                    };

                    const statusClass = session.status === 'human' ? '#e65100' : '#2e7d32';
                    const statusBg = session.status === 'human' ? '#ffe0b2' : '#e8f5e9';

                    div.innerHTML = `
                        <div class="session-info">
                            <div style="font-weight: bold; color: #1565c0;">Session #${session.id}</div>
                            <small style="color: #666;">Last updated: ${session.updated_at || 'Recently'}</small>
                        </div>
                        <span style="font-size: 0.75rem; padding: 4px 8px; border-radius: 12px; font-weight: 600; background: ${statusBg}; color: ${statusClass};">
                            ${session.status ? session.status.toUpperCase() : 'BOT'}
                        </span>
                    `;
                    container.appendChild(div);
                });
            } else {
                container.innerHTML = '<p style="text-align:center; padding:15px; color:#888;">No active chat sessions found.</p>';
            }
        })
        .catch(err => {
            console.error('Error fetching sessions:', err);
            container.innerHTML = '<p style="text-align:center; padding:15px; color:#c62828;">Failed to load sessions.</p>';
        });
}


// ==========================================
// 2. LIVE CHAT WORKSPACE LOGIC
// ==========================================

function loadAdminChat(sessionId) {
    if (!sessionId) return;
    
    // Lock to prevent stacking concurrent requests
    if (isLoadingHistory) return;
    
    activeSessionId = sessionId;
    isLoadingHistory = true;

    // Show Chat Workspace Overlay
    const workspace = document.getElementById('adminChatWorkspace');
    if (workspace) workspace.style.display = 'flex';

    const hiddenInput = document.getElementById('activeAdminSessionId');
    if (hiddenInput) hiddenInput.value = sessionId;
    
    const workspaceHeader = document.getElementById('chatWorkspaceHeader');
    if (workspaceHeader) workspaceHeader.innerText = "Responding to Session #" + sessionId;

    fetch(`/Walany/chat.php?action=get_history&session_id=${sessionId}`)
    .then(res => res.json())
    .then(messages => {
        const log = document.getElementById('adminMessageLogs');
        if (!log) return;
        
        log.innerHTML = '';
        if (Array.isArray(messages)) {
            messages.forEach(m => {
                const div = document.createElement('div');
                div.innerText = `${m.sender.toUpperCase()}: ${m.message}`;
                div.style.padding = '8px 12px';
                div.style.borderRadius = '6px';
                div.style.maxWidth = '75%';
                div.style.wordBreak = 'break-word';
                
                if (m.sender === 'user') {
                    div.style.background = '#e0f7fa';
                    div.style.color = '#006064';
                    div.style.alignSelf = 'flex-start';
                } else if (m.sender === 'agent') {
                    div.style.background = '#ffe0b2';
                    div.style.color = '#e65100';
                    div.style.alignSelf = 'flex-end';
                } else {
                    div.style.background = '#e8f5e9';
                    div.style.color = '#1b5e20';
                    div.style.alignSelf = 'flex-start';
                }
                log.appendChild(div);
            });
        }
        log.scrollTop = log.scrollHeight;
    })
    .catch(err => console.error("Error loading chat history:", err))
    .finally(() => {
        isLoadingHistory = false;
    });
}

function sendAdminResponse() {
    const input = document.getElementById('adminReplyInput');
    const text = input ? input.value.trim() : '';
    if (!text || !activeSessionId) return;

    fetch('/Walany/chat.php?action=admin_reply', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ session_id: activeSessionId, message: text })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            if (input) input.value = '';
            loadAdminChat(activeSessionId); // Refresh log immediately
        }
    })
    .catch(err => console.error("Error sending reply:", err));
}

function resolveSessionCall() {
    if (!activeSessionId || !confirm("Return this user session back to AI Bot control?")) return;
    
    fetch('/Walany/chat.php?action=resolve_session', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ session_id: activeSessionId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            closeChatWorkspace();
        }
    });
}

// Auto-poll active conversation thread safely every 4 seconds
setInterval(() => {
    if (activeSessionId && !isLoadingHistory) {
        loadAdminChat(activeSessionId);
    }
}, 4000);
</script>