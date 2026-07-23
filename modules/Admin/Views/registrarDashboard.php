<?php
require_once __DIR__ . '/../Models/RegistrarModel.php';
require_once __DIR__ . '/../Controllers/RegistrarController.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Workspace Dashboard</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f6f9; margin: 0; padding: 20px;">

    <!-- Main Workspace Container -->
    <div style="max-width: 1200px; margin: 0 auto;">
        
        <header style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="color: #2c3e50; margin: 0; font-size: 1.85em;">Registrar Control Center</h1>
                <p style="color: #7f8c8d; margin: 5px 0 0 0; font-size: 0.95em;">Monitor student cohorts, event registration queues, and registration operations.</p>
            </div>
            
            <a href="/Walany/index.php?module=Auth&action=logout" onclick="return confirm('Are you sure you want to log out of the system?');" style="background: #dc3545; color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.9em;">
                Logout
            </a>
        </header>

        <!-- Profile Card -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; max-width: 500px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); display: flex; align-items: center; justify-content: space-between; gap: 20px;">
            <div style="display: flex; align-items: center; gap: 15px; overflow: hidden;">
                <div style="background: #e0f2fe; color: #0284c7; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.4em; font-weight: bold; flex-shrink: 0;">
                    <?= strtoupper(substr($currentRegistrarName, 0, 1)) ?>
                </div>
                <div style="display: flex; flex-direction: column; overflow: hidden;">
                    <span style="font-size: 1.05em; font-weight: 600; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= $currentRegistrarName ?>">
                        <?= $currentRegistrarName ?>
                    </span>
                    <span style="font-size: 0.85em; color: #64748b; margin: 2px 0 6px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= $registrarEmail ?>">
                        <?= $registrarEmail ?>
                    </span>
                    <div>
                        <span style="background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; font-size: 0.75em; font-weight: bold; padding: 2px 8px; border-radius: 12px; display: inline-block; text-transform: uppercase; letter-spacing: 0.05em;">
                            <?= $currentRegistrarRole ?>
                        </span>
                    </div>
                </div>
            </div>

            <div style="flex-shrink: 0;">
                <a href="/Walany/index.php?module=Admin&action=profile_settings" style="background: #0f172a; color: #ffffff; padding: 10px 14px; border-radius: 6px; text-decoration: none; font-size: 0.88em; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: background 0.2s; border: 1px solid #1e293b;" onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='#0f172a'">
                    Settings
                </a>
            </div>
        </div>

        <!-- Calendar Workspace Row -->
        <div style="margin-top: 30px; display: grid; grid-template-columns: 1fr 350px; gap: 20px; align-items: start;">
            
            <!-- 1. Interactive Calendar Grid -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                    <div>
                        <h2 style="margin: 0; color: #1e293b; font-size: 1.3em; font-weight: 700;"><?= $monthName . " " . $year ?></h2>
                    </div>
                    
                    <form method="GET" action="/Walany/index.php" style="display: flex; align-items: center; gap: 8px; margin: 0;">
                        <input type="hidden" name="module" value="Admin">
                        <input type="hidden" name="action" value="registrar_dashboard">

                        <!-- Month Selector -->
                        <select name="c_month" onchange="this.form.submit()" style="padding: 6px 12px; border: 1px solid #cbd5e1; border-radius: 6px; background: #ffffff; color: #334155; font-size: 0.9em; font-weight: 600; cursor: pointer; outline: none;">
                            <?php
                            for ($m = 1; $m <= 12; $m++) {
                                $selected = ($m === $month) ? 'selected' : '';
                                $mName = date('F', mktime(0, 0, 0, $m, 1, $year));
                                echo "<option value='{$m}' {$selected}>{$mName}</option>";
                            }
                            ?>
                        </select>

                        <!-- Year Selector -->
                        <select name="c_year" onchange="this.form.submit()" style="padding: 6px 12px; border: 1px solid #cbd5e1; border-radius: 6px; background: #ffffff; color: #334155; font-size: 0.9em; font-weight: 600; cursor: pointer; outline: none;">
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
                            <a href="/Walany/index.php?module=Admin&action=registrar_dashboard&c_month=<?= $prevMonth ?>&c_year=<?= $prevYear ?>" style="text-decoration: none; padding: 6px 10px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; color: #334155; font-size: 0.9em; font-weight: 600;">&larr;</a>
                            <a href="/Walany/index.php?module=Admin&action=registrar_dashboard&c_month=<?= date('m') ?>&c_year=<?= date('Y') ?>" style="text-decoration: none; padding: 6px 10px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; color: #334155; font-size: 0.9em; font-weight: 600;">Today</a>
                            <a href="/Walany/index.php?module=Admin&action=registrar_dashboard&c_month=<?= $nextMonth ?>&c_year=<?= $nextYear ?>" style="text-decoration: none; padding: 6px 10px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; color: #334155; font-size: 0.9em; font-weight: 600;">&rarr;</a>
                        </div>
                    </form>
                </div>

                <!-- Week Bar -->
                <div style="display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; margin-bottom: 10px; font-weight: bold; color: #64748b; font-size: 0.85em;">
                    <div>SUN</div><div>MON</div><div>TUE</div><div>WED</div><div>THU</div><div>FRI</div><div>SAT</div>
                </div>

                <!-- Days Matrix -->
                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px;">
                    <?php
                    for ($i = 0; $i < $dayOfWeek; $i++) {
                        echo '<div style="aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; color: #cbd5e1;"></div>';
                    }

                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $currentDateKey = sprintf("%04d-%02d-%02d", $year, $month, $day);
                        $hasEvents = isset($eventsByDate[$currentDateKey]);
                        $todayClass = ($currentDateKey === date('Y-m-d')) ? 'border: 2px solid #3b82f6;' : 'border: 1px solid #e2e8f0;';

                        if ($hasEvents) {
                            $bgStyle = 'background: #e0f2fe; color: #0369a1; font-weight: 700; cursor: pointer;';
                            $indicator = '<span style="width: 6px; height: 6px; background: #0284c7; border-radius: 50%; margin-top: 4px; display: block;"></span>';
                            $jsClick = "onclick='showEventDetails(\"" . $currentDateKey . "\", " . json_encode($eventsByDate[$currentDateKey]) . ")'";
                        } else {
                            $bgStyle = 'background: #ffffff; color: #334155;';
                            $indicator = '<span style="width: 6px; height: 6px; visibility: hidden; margin-top: 4px; display: block;"></span>';
                            $jsClick = "";
                        }
                        ?>
                        <div <?= $jsClick ?> class="calendar-day" style="aspect-ratio: 1/1; border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: 0.95em; transition: all 0.15s ease-in-out; <?= $todayClass . ' ' . $bgStyle ?>" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                            <span><?= $day ?></span>
                            <?= $indicator ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <!-- 2. Dynamic Details Sideboard Panel -->
            <div id="event-sideboard" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); min-height: 280px; display: flex; flex-direction: column; justify-content: space-between; gap: 20px;">
                <div>
                    <h3 id="panel-date" style="margin-top: 0; margin-bottom: 15px; color: #0f172a; font-size: 1.15em; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
                        Selected Date
                    </h3>
                    <div id="events-list-container" style="display: flex; flex-direction: column; gap: 12px;">
                        <p style="color: #64748b; font-size: 0.9em; font-style: italic; text-align: center; margin-top: 30px;">
                            Click a highlighted blue calendar date to view scheduled events.
                        </p>
                    </div>
                </div>
                
                <div style="border-top: 1px solid #f1f5f9; padding-top: 15px; display: flex; flex-direction: column; gap: 10px;">
                    <a href="/Walany/index.php?module=Attendance&action=view_events" style="background: #0284c7; color: #ffffff; padding: 10px 14px; border-radius: 6px; text-decoration: none; font-size: 0.88em; font-weight: 600; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.2s;" onmouseover="this.style.background='#0369a1'" onmouseout="this.style.background='#0284c7'">
                        View All Events &amp; Scanners
                    </a>
                    <div style="text-align: right;">
                        <span style="font-size: 0.75em; color: #94a3b8; font-weight: 500;">Walany Cohort Registrar Scheduler</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Attendance Logs Grid -->
        <div style="margin-top: 30px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
                <div>
                    <h3 style="margin: 0; color: #0f172a; font-size: 1.2em; font-weight: 700;">Live Attendance Logs</h3>
                    <p style="color: #64748b; margin: 3px 0 0 0; font-size: 0.85em;">Real-time stream of the latest event check-ins.</p>
                </div>
                <input type="text" id="registrantSearch" onkeyup="filterRegistrantTable()" placeholder="Search logs..." style="padding: 8px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.88em; width: 260px; outline: none; transition: border-color 0.15s;" onfocus="this.style.borderColor='#0284c7'" onblur="this.style.borderColor='#cbd5e1'">
            </div>

            <div style="overflow-x: auto;">
                <table id="registrantsTable" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9em;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 12px 16px; color: #475569; font-weight: 600;">Reference ID</th>
                            <th style="padding: 12px 16px; color: #475569; font-weight: 600;">Full Name</th>
                            <th style="padding: 12px 16px; color: #475569; font-weight: 600;">Event</th>
                            <th style="padding: 12px 16px; color: #475569; font-weight: 600;">Time Checked In</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentAttendance)): ?>
                            <tr>
                                <td colspan="4" style="padding: 30px; text-align: center; color: #94a3b8; font-style: italic;">
                                    No live attendance records logged yet.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentAttendance as $log): ?>
                                <?php $fullName = htmlspecialchars($log['last_name'] . ', ' . $log['first_name']); ?>
                                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.1s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 14px 16px; font-family: monospace; font-weight: 600; color: #0284c7;">
                                        #<?= htmlspecialchars($log['reference_id'] ?? 'N/A') ?>
                                    </td>
                                    <td style="padding: 14px 16px; color: #0f172a; font-weight: 500;">
                                        <?= $fullName ?>
                                    </td>
                                    <td style="padding: 14px 16px; color: #334155;">
                                        <span style="background: #f1f5f9; color: #475569; padding: 4px 8px; border-radius: 4px; font-weight: 500; font-size: 0.95em;">
                                            <?= htmlspecialchars($log['event_name']) ?>
                                        </span>
                                    </td>
                                    <td style="padding: 14px 16px; color: #16a34a; font-weight: 600;">
                                        <?= date('h:i A (M d, Y)', strtotime($log['time_checked_in'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Script Engines -->
    <script>
        function showEventDetails(dateString, events) {
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateObject = new Date(dateString);
            document.getElementById('panel-date').innerText = dateObject.toLocaleDateString('en-US', dateOptions);

            const container = document.getElementById('events-list-container');
            container.innerHTML = '';

            events.forEach(function(event) {
                const eventCard = document.createElement('div');
                eventCard.style.cssText = "background: #f8fafc; border-left: 4px solid #0284c7; padding: 12px; border-radius: 0 6px 6px 0; box-shadow: 0 1px 2px rgba(0,0,0,0.01);";
                eventCard.innerHTML = `
                    <h4 style="margin: 0 0 5px 0; color: #0f172a; font-size: 0.95em; font-weight: 600;">${event.name}</h4>
                    <div style="font-size: 0.8em; color: #64748b; margin-bottom: 4px;">Time: ${event.time}</div>
                    <div style="font-size: 0.8em; color: #64748b;">Venue: ${event.location}</div>
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