<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Scan Terminal</title>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; padding: 20px; margin: 0; }
        .back-btn { display: inline-block; margin-bottom: 15px; color: #007bff; text-decoration: none; font-weight: bold; }
        
        /* Master Layout Split Screen Splitter */
        .workspace-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 25px; max-width: 1300px; margin: 0 auto; }
        
        /* General Panel Cards styling */
        .panel-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 20px; }
        h3 { margin-top: 0; border-bottom: 2px solid #eaeaea; padding-bottom: 8px; color: #333; }
        
        /* Left Column UI items */
        #reader { width: 100%; background: #000; border-radius: 6px; overflow: hidden; }
        .manual-input-group { display: flex; gap: 10px; margin-top: 15px; }
        .manual-input-group input { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 15px; }
        .manual-input-group button { background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; cursor: pointer; }
        .manual-input-group button:hover { background: #218838; }
        
        /* Banner Alerts notifications */
        .status-box { margin-top: 15px; padding: 12px; border-radius: 4px; font-weight: bold; display: none; text-align: center; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        /* Right Column Metadata Details Lists elements */
        .details-display p { font-size: 16px; margin: 10px 0; color: #444; }
        .details-display span { font-weight: bold; color: #111; }
        
        /* Live Attendees History Table layout structure */
        .table-container { overflow-y: auto; max-height: 400px; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; color: #555; position: sticky; top: 0; }
        tr:hover { background-color: #fdfdfd; }
    </style>
</head>
<body>

<div style="max-width: 1300px; margin: 0 auto;">
    <a href="?module=Attendance&action=view_events" class="back-btn">← Back to Events List</a>
    <h2>Registrar Entry Control Terminal (Event ID Context: <?php echo htmlspecialchars($_GET['event_id'] ?? '0'); ?>)</h2>
</div>

<div class="workspace-grid">
    
    <!-- LEFT SIDE: CONTROLS & INTERCEPTORS -->
    <div class="column-left">
        <div class="panel-card">
            <h3>Camera QR Hardware Stream</h3>
            <div id="reader"></div>
            <div id="scan-status" class="status-box"></div>
        </div>
        
        <div class="panel-card">
            <h3>Manual Reference Overrides</h3>
            <p style="font-size: 14px; color: #666; margin-top: 0;">If ticket camera reading fails, manually search student parameters below:</p>
            <div class="manual-input-group">
                <input type="text" id="manual_ref" placeholder="Type Reference ID (e.g. REF-10023)..." autocomplete="off">
                <button type="button" onclick="submitManualInput()">Verify System ID</button>
            </div>
        </div>
    </div>
    
    <!-- RIGHT SIDE: DISPLAYS & GRID MATRIX LOGS -->
    <div class="column-right">
        <!-- Top Right panel metadata displaying -->
        <div class="panel-card">
            <h3>Scanned Profile Inspection Matrix</h3>
            <div class="details-display">
                <p>Reference ID: <span id="lbl-ref">---</span></p>
                <p>Full Name: <span id="lbl-name">---</span></p>
                <p>Email Address: <span id="lbl-email">---</span></p>
                <p>Phone Contact: <span id="lbl-phone">---</span></p>
            </div>
        </div>
        
        <!-- Bottom Right panel grid log entries metrics lists -->
        <div class="panel-card">
            <h3>Live Verified Event Attendees</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Reference ID</th>
                            <th>Attendee Name</th>
                            <th>Checked In Timestamp</th>
                        </tr>
                    </thead>
                    <tbody id="attendees-rows">
                        <?php if (empty($attendees)): ?>
                            <tr id="no-data-row"><td colspan="3" style="text-align: center; color: #999;">No entry record sequences logged yet today.</td></tr>
                        <?php else: ?>
                            <?php foreach ($attendees as $person): ?>
                                <tr>
                                    <td><code><?php echo htmlspecialchars($person['reference_id']); ?></code></td>
                                    <td><?php echo htmlspecialchars($person['first_name'] . ' ' . $person['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($person['time_checked_in']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</div>

<script>
// Initialize Camera scanner hardware instances
let html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 15, qrbox: { width: 250, height: 250 } }, false);

function onScanSuccess(decodedText) {
    sendAttendancePayload(decodedText);
}
html5QrcodeScanner.render(onScanSuccess);

// Catch and route manual text input fields submissions
function submitManualInput() {
    const inputField = document.getElementById('manual_ref');
    const value = inputField.value.trim();
    if(!value) { alert("Please type a reference ID string first."); return; }
    
    sendAttendancePayload(value);
    inputField.value = ''; // Clean the workspace box context container layout items
}

// Global AJAX pipeline dispatcher
function sendAttendancePayload(referenceString) {
    const statusBox = document.getElementById('scan-status');
    statusBox.className = "status-box"; 
    statusBox.style.display = "block";
    statusBox.innerHTML = "Querying credentials registry...";

    fetch('?module=Attendance&action=process_scan', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            'qr_data': referenceString,
            'event_id': '<?php echo intval($_GET['event_id'] ?? 0); ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        // Clear old visual classes
        statusBox.className = "status-box"; 
        
        if(data.status === 'success') {
            statusBox.classList.add('success');
            statusBox.innerHTML = "✅ " + data.message;
        } else {
            statusBox.classList.add('error');
            statusBox.innerHTML = "❌ " + data.message;
        }
        
        // Map demographic profiles records inspect elements if object elements returned
        if(data.registrant) {
            document.getElementById('lbl-ref').innerText = data.reference_id || '---';
            document.getElementById('lbl-name').innerText = data.registrant.first_name + ' ' + data.registrant.last_name;
            document.getElementById('lbl-email').innerText = data.registrant.email || '---';
            document.getElementById('lbl-phone').innerText = data.registrant.contact_number || '---';
        }

        // Rebuild and injection layout matrices rows without forcing location reloads scripts
        if(data.attendees) {
            rebuildAttendeesTable(data.attendees);
        }
    })
    .catch(err => {
        statusBox.className = "status-box error";
        statusBox.innerHTML = "Network validation link broke down.";
        console.error(err);
    });
}

function rebuildAttendeesTable(list) {
    const tbody = document.getElementById('attendees-rows');
    if(!list || list.length === 0) {
        tbody.innerHTML = `<tr id="no-data-row"><td colspan="3" style="text-align: center; color: #999;">No entry record sequences logged yet today.</td></tr>`;
        return;
    }
    
    let html = "";
    list.forEach(person => {
        // FIXED: Using true JavaScript template literals (backticks) instead of quotes
        html += `<tr>
            <td><code>${escapeHtml(person.reference_id)}</code></td>
            <td>${escapeHtml(person.first_name)} ${escapeHtml(person.last_name)}</td>
            <td>${escapeHtml(person.time_checked_in)}</td>
        </tr>`;
    });
    tbody.innerHTML = html;
}

function escapeHtml(str) {
    if(!str) return '';
    return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}
</script>

</body>
</html>