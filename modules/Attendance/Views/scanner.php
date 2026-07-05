<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Scan Terminal</title>
    <!-- Include the HTML5-QRCode scanner library directly from a reliable CDN -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 30px; }
        .scanner-container { max-width: 600px; margin: 0 auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; }
        #reader { width: 100%; background: #000; border-radius: 6px; overflow: hidden; }
        .back-btn { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; font-weight: bold; }
        .status-box { margin-top: 15px; padding: 12px; border-radius: 4px; font-weight: bold; display: none; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

<div class="scanner-container">
    <a href="?module=Attendance&action=view_events" class="back-btn">← Back to Events List</a>
    
    <h2>Attendance Terminal</h2>
    <p>Event ID Context: <strong><?php echo htmlspecialchars($_GET['event_id'] ?? '0'); ?></strong></p>
    
    <!-- The targeted video element rendering box -->
    <div id="reader"></div>
    
    <!-- Real-time scanning feedback banner -->
    <div id="scan-status" class="status-box"></div>
</div>

<script>
// 1. Initialize the scanner engine instance
const html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", 
    { fps: 10, qrbox: { width: 250, height: 250 } },
    /* verbose= */ false
);

// 2. What happens when the camera successfully reads a QR Code code string
function onScanSuccess(decodedText, decodedResult) {
    // Temporarily pause scanner to prevent duplicate multi-scans of the same ticket
    html5QrcodeScanner.clear();
    
    const statusBox = document.getElementById('scan-status');
    statusBox.className = "status-box"; 
    statusBox.style.display = "block";
    statusBox.innerHTML = "Processing ticket string: " + decodedText + "...";

    // 3. Send the scanned text string dynamically to the backend using AJAX
    fetch('?module=Attendance&action=process_scan', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'qr_data': decodedText,
            'event_id': '<?php echo intval($_GET['event_id'] ?? 0); ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            statusBox.classList.add('success');
            statusBox.innerHTML = "✅ Verified: " + data.message;
        } else {
            statusBox.classList.add('error');
            statusBox.innerHTML = "❌ Rejected: " + data.message;
        }
        
        // Restart camera scanning after a short delay (e.g., 3 seconds) for the next student
        setTimeout(() => {
            location.reload(); 
        }, 3000);
    })
    .catch(err => {
        statusBox.className = "status-box error";
        statusBox.innerHTML = "Network connection breakdown error tracking logic.";
        console.error(err);
    });
}

// Render the camera scanner workspace directly onto the layout element framework
html5QrcodeScanner.render(onScanSuccess);
</script>

</body>
</html>