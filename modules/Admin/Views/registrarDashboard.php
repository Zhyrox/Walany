<?php
require_once __DIR__ . '/../Models/RegistrarModel.php';
require_once __DIR__ . '/../Controllers/RegistrarController.php';
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Workspace Dashboard</title>
    <link rel="stylesheet" href="/Walany/assets/style.css">
</head>
<body class="home-events-page registrar-dashboard-page">

    <header class="site-header login-header headbar">
        <a href="" class="logo-placeholder" aria-label="Walania home">
            <img src="/Walany/assets/images/Walania.svg" alt="Walania logo">
        </a>
        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle dark mode">
            <img class="theme-toggle-icon" data-theme-icon src="/Walany/assets/images/LightModeIcon.svg" alt="" aria-hidden="true">
        </button>
    </header>

    <!-- Main Workspace Container -->
    <div class="registrar-dashboard-container">
        
        <header class="registrar-dashboard-header">
            <div>
                <h1 class="registrar-dashboard-title">Registrar Control Center</h1>
                <p class="registrar-dashboard-subtitle">Monitor student cohorts, event registration queues, and registration operations.</p>
            </div>

            <a href="/Walany/index.php?module=Auth&action=logout" onclick="return confirm('Are you sure you want to log out of the system?');" class="registrar-dashboard-logout-btn">
                Logout
            </a>
        </header>

        <!-- Profile Card -->
        <div class="registrar-dashboard-user-card">
            <div class="user-card-inner">
                <div class="avatar"> <?= strtoupper(substr($currentRegistrarName, 0, 1)) ?> </div>
                <div class="meta">
                    <span class="name" title="<?= $currentRegistrarName ?>"><?= $currentRegistrarName ?></span>
                    <span class="email" title="<?= $registrarEmail ?>"><?= $registrarEmail ?></span>
                    <div>
                        <span class="role-chip"><?= $currentRegistrarRole ?></span>
                    </div>
                </div>
            </div>

            <div class="registrar-dashboard-actions">
                <a href="/Walany/index.php?module=Admin&action=profile_settings" class="btn">Settings</a>
            </div>
        </div>

        <!-- Calendar Workspace Row -->
        <div class="calendar-workspace-container">
        <div class="calendar-grid-row" style="margin-top: 30px; display: grid; grid-template-columns: 1fr 350px; gap: 20px; align-items: start;">
            
            <!-- 1. Interactive Calendar Grid -->
            <div class="interactive-calendar-container">
            <div class="registrar-dashboard-panel">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                    <div>
                        <h2><?= $monthName . " " . $year ?></h2>
                    </div>
                    
                    <form method="GET" action="/Walany/index.php" class="calendar-controls" style="display: flex; align-items: center; gap: 8px; margin: 0;">
                        <input type="hidden" name="module" value="Admin">
                        <input type="hidden" name="action" value="registrar_dashboard">

                        <!-- Month Selector -->
                        <select name="c_month" onchange="this.form.submit()">
                            <?php
                            for ($m = 1; $m <= 12; $m++) {
                                $selected = ($m === $month) ? 'selected' : '';
                                $mName = date('F', mktime(0, 0, 0, $m, 1, $year));
                                echo "<option value='{$m}' {$selected}>{$mName}</option>";
                            }
                            ?>
                        </select>

                        <!-- Year Selector -->
                        <select name="c_year" onchange="this.form.submit()">
                            <?php
                            $startYear = date('Y') - 3;
                            $endYear   = date('Y') + 3;
                            for ($y = $startYear; $y <= $endYear; $y++) {
                                $selected = ($y === $year) ? 'selected' : '';
                                echo "<option value='{$y}' {$selected}>{$y}</option>";
                            }
                            ?>
                        </select>

                        <!-- Navigation -->
                        <div style="display: flex; gap: 4px; margin-left: 8px;">
                            <a href="/Walany/index.php?module=Admin&action=registrar_dashboard&c_month=<?= $prevMonth ?>&c_year=<?= $prevYear ?>">&larr;</a>
                            <a href="/Walany/index.php?module=Admin&action=registrar_dashboard&c_month=<?= date('m') ?>&c_year=<?= date('Y') ?>">Today</a>
                            <a href="/Walany/index.php?module=Admin&action=registrar_dashboard&c_month=<?= $nextMonth ?>&c_year=<?= $nextYear ?>">&rarr;</a>
                        </div>
                    </form>
                </div>

                <!-- Week Bar -->
                <div class="calendar-weekbar">
                    <div>SUN</div><div>MON</div><div>TUE</div><div>WED</div><div>THU</div><div>FRI</div><div>SAT</div>
                </div>

                <!-- Days Matrix -->
                <div class="calendar-grid">
                    <?php
                    for ($i = 0; $i < $dayOfWeek; $i++) {
                        echo '<div class="calendar-day empty"></div>';
                    }

                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $currentDateKey = sprintf("%04d-%02d-%02d", $year, $month, $day);
                        $hasEvents = isset($eventsByDate[$currentDateKey]);
                        $classes = 'calendar-day';
                        if ($currentDateKey === date('Y-m-d')) { $classes .= ' today'; }

                        if ($hasEvents) {
                            $classes .= ' has-events';
                            $indicator = '<span class="event-indicator"></span>';
                            $jsClick = "onclick='showEventDetails(\"" . $currentDateKey . "\", " . json_encode($eventsByDate[$currentDateKey]) . ")'";
                        } else {
                            $indicator = '<span class="event-indicator hidden"></span>';
                            $jsClick = "";
                        }
                        ?>
                        <div <?= $jsClick ?> class="<?= $classes ?>">
                            <span><?= $day ?></span>
                            <?= $indicator ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            </div>
 
            <!-- 2. Dynamic Details Sideboard Panel -->
            <div class="details-panel-container">
            <div id="event-sideboard" class="registrar-dashboard-sideboard">
                <div>
                    <h3 id="panel-date">Selected Date</h3>
                    <div id="events-list-container">
                        <p>Click a highlighted blue calendar date to view scheduled events.</p>
                    </div>
                </div>
                
                <div style="border-top: 1px solid var(--border); padding-top: 15px; display: flex; flex-direction: column; gap: 10px;">
                    <a href="/Walany/index.php?module=Attendance&action=view_events" class="side-action">View All Events &amp; Scanners</a>
                    <div style="text-align: right;">
                        <span>Walany Cohort Registrar Scheduler</span>
                    </div>
                </div>
            </div>
            </div>
        </div>
        </div>

        <!-- Live Attendance Logs Grid -->
        <div class="attendance-grid-container">
        <div class="registrar-dashboard-logs-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
                <div>
                    <h3>Live Attendance Logs</h3>
                    <p>Real-time stream of the latest event check-ins.</p>
                </div>
                <input type="text" id="registrantSearch" class="registrant-search" onkeyup="filterRegistrantTable()" placeholder="Search logs...">
            </div>

            <div style="overflow-x: auto;">
                <table id="registrantsTable" class="registrar-table">
                    <thead>
                        <tr>
                            <th>Reference ID</th>
                            <th>Full Name</th>
                            <th>Event</th>
                            <th>Time Checked In</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentAttendance)): ?>
                            <tr>
                                <td colspan="4" style="padding: 30px; text-align: center; font-style: italic; color: var(--muted);">
                                    No live attendance records logged yet.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentAttendance as $log): ?>
                                <?php $fullName = htmlspecialchars($log['last_name'] . ', ' . $log['first_name']); ?>
                                <tr>
                                    <td class="ref">#<?= htmlspecialchars($log['reference_id'] ?? 'N/A') ?></td>
                                    <td class="name"><?= $fullName ?></td>
                                    <td><span class="event-badge"><?= htmlspecialchars($log['event_name']) ?></span></td>
                                    <td class="time"><?= date('h:i A (M d, Y)', strtotime($log['time_checked_in'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div>

    </div>

    <!-- Script Engines -->
    <script src="/Walany/assets/script.js"></script>
    <script>
        function showEventDetails(dateString, events) {
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateObject = new Date(dateString);
            document.getElementById('panel-date').innerText = dateObject.toLocaleDateString('en-US', dateOptions);

            const container = document.getElementById('events-list-container');
            container.innerHTML = '';

            events.forEach(function(event) {
                const eventCard = document.createElement('div');
                eventCard.className = 'event-card-detail';
                eventCard.innerHTML = `
                    <h4>${event.name}</h4>
                    <div>Time: ${event.time}</div>
                    <div>Venue: ${event.location}</div>
                `;
                container.appendChild(eventCard);
            });
        }

        function filterRegistrantTable() {
            const input = document.getElementById("registrantSearch");
            const filter = input.value.toLowerCase();
            const table = document.getElementById("registrantsTable");
            const tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let matchFound = false;
                const tds = tr[i].getElementsByTagName("td");
                for (let j = 0; j < tds.length - 1; j++) {
                    if (tds[j]) {
                        const textValue = tds[j].textContent || tds[j].innerText;
                        if (textValue.toLowerCase().indexOf(filter) > -1) {
                            matchFound = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = matchFound ? "" : "none";
            }
        }
    </script>
</body>
</html>