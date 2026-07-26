<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Planner Dashboard</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f6f9; margin: 0; padding: 20px;">

    <!-- Capture dynamic absolute script pathway to prevent routing breakage -->
    <?php $selfPath = htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>

    <!-- Main Workspace Container -->
    <div style="max-width: 1200px; margin: 0 auto;">
        
        <!-- Header -->
        <header style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="color: #2c3e50; margin: 0; font-size: 1.85em;">Event Planner Control Center</h1>
                <p style="color: #7f8c8d; margin: 5px 0 0 0; font-size: 0.95em;">Design upcoming events, track quotas, and monitor real-time attendee engagement.</p>
            </div>
            
            <!-- Dynamic Logout Pathway Resolution -->
            <a href="<?= $selfPath ?>?module=Auth&action=logout" onclick="return confirm('Are you sure you want to log out?');" style="background: #dc3545; color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.9em;">
                Logout
            </a>

        </header>

        <!-- 1. Profile Header Div (Registrar Style Consistency) -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; max-width: 500px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); display: flex; align-items: center; justify-content: space-between; gap: 20px;">
            <div style="display: flex; align-items: center; gap: 15px; overflow: hidden;">
                <!-- Initial Circle Avatar -->
                <div style="background: #f0fdfa; color: #0d9488; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.4em; font-weight: bold; flex-shrink: 0;">
                    <?= strtoupper(substr($currentPlannerName, 0, 1)) ?>
                </div>
                <!-- Identity Badges and Details -->
                <div style="display: flex; flex-direction: column; overflow: hidden;">
                    <span style="font-size: 1.05em; font-weight: 600; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= $currentPlannerName ?>">
                        <?= $currentPlannerName ?>
                    </span>
                    <span style="font-size: 0.85em; color: #64748b; margin: 2px 0 6px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= $plannerEmail ?>">
                        <?= $plannerEmail ?>
                    </span>
                    <div>
                        <!-- Teal-themed Role Badge to distinguish from Registrar -->
                        <span style="background: #f0fdfa; color: #0d9488; border: 1px solid #99f6e4; font-size: 0.75em; font-weight: bold; padding: 2px 8px; border-radius: 12px; display: inline-block; text-transform: uppercase; letter-spacing: 0.05em;">
                            <?= $currentPlannerRole ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Profile/Settings Quick Link: Route Resolved Safely -->
            <div style="flex-shrink: 0;">
                <a href="<?= $selfPath ?>?module=Admin&action=profile_settings" style="background: #0f172a; color: #ffffff; padding: 10px 14px; border-radius: 6px; text-decoration: none; font-size: 0.88em; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: background 0.2s; border: 1px solid #1e293b;" onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='#0f172a'">
                    Settings
                </a>
            </div>
        </div>

    </div>

    <!-- 2. High-Impact KPI Cards Row -->
        <div style="max-width: 1200px; margin: 25px auto 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">
            
            <!-- Card 1: Active Events -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.01); display: flex; flex-direction: column; gap: 5px;">
                <span style="font-size: 0.85em; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Active Events</span>
                <span style="font-size: 2em; font-weight: 700; color: #0f172a;"><?= $activeEventsCount ?></span>
                <span style="font-size: 0.8em; color: #0d9488; font-weight: 500;">Currently in scheduling pipe</span>
            </div>

            <!-- Card 2: Total Registrants Cohort -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.01); display: flex; flex-direction: column; gap: 5px;">
                <span style="font-size: 0.85em; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Total Registrants</span>
                <span style="font-size: 2em; font-weight: 700; color: #0f172a;"><?= $totalRegistrants ?></span>
                <span style="font-size: 0.8em; color: #0284c7; font-weight: 500;">Cumulative check-ins tracked</span>
            </div>

            <!-- Card 3: Top Performing Spotlight Card -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.01); display: flex; flex-direction: column; justify-content: space-between; gap: 5px;">
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <span style="font-size: 0.85em; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Top Performing Event</span>
                    <span style="font-size: 1.1em; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= $topEventData['name'] ?>">
                        <?= $topEventData['name'] ?>
                    </span>
                </div>
                <span style="font-size: 0.85em; color: #ea580c; font-weight: 600; margin-top: 4px;">
                    <?= $topEventData['count'] ?> Attendees Checked In
                </span>
            </div>

        </div>

        <!-- 3. Dynamic Charts Display Row -->
        <div style="max-width: 1200px; margin: 30px auto 0 auto; display: grid; grid-template-columns: 2fr 2fr 1.2fr; gap: 20px; flex-wrap: wrap;">
            
            <!-- Chart Container A: Bar Chart -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.01);">
                <h4 style="margin: 0 0 15px 0; color: #1e293b; font-size: 0.95em; font-weight: 700; text-transform: uppercase;">Quota &amp; Capacity Tracker</h4>
                <div style="position: relative; height: 220px; width: 100%;">
                    <canvas id="capacityBarChart"></canvas>
                </div>
            </div>

            <!-- Chart Container B: Line Chart -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.01);">
                <h4 style="margin: 0 0 15px 0; color: #1e293b; font-size: 0.95em; font-weight: 700; text-transform: uppercase;">Registration Velocity</h4>
                <div style="position: relative; height: 220px; width: 100%;">
                    <canvas id="velocityLineChart"></canvas>
                </div>
            </div>

            <!-- Chart Container C: Doughnut Chart -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.01);">
                <h4 style="margin: 0 0 15px 0; color: #1e293b; font-size: 0.95em; font-weight: 700; text-transform: uppercase;">Turnout Rate</h4>
                <div style="position: relative; height: 220px; width: 100%;">
                    <canvas id="turnoutDoughnutChart"></canvas>
                </div>
            </div>

        </div>

        <!-- 4. Interactive Calendar & Feedback Sideboard -->
        <div style="max-width: 1200px; margin: 30px auto 0 auto; display: grid; grid-template-columns: 1.5fr 1fr; gap: 20px;">
            
            <!-- Left Side: The Calendar Component -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.01);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="margin: 0; color: #1e293b; font-size: 1.1em; font-weight: 700;">Event Calendar</h3>
                    
                    <!-- Form Dynamic Action Path Fix -->
                    <form method="GET" action="" style="display: flex; align-items: center; gap: 8px; margin: 0;">
                        <input type="hidden" name="module" value="Admin">
                        <input type="hidden" name="action" value="planner_dashboard">

                        <!-- Quick Previous Month Arrow Button -->
                        <?php
                        $prevMonth = $month - 1;
                        $prevYear = $year;
                        if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }
                        ?>
                        <a href="<?= $selfPath ?>?module=Admin&action=planner_dashboard&c_month=<?= $prevMonth ?>&c_year=<?= $prevYear ?>" 
                           style="background: #f1f5f9; color: #334155; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.9em; border: 1px solid #e2e8f0;"
                           onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                            &larr;
                        </a>

                        <!-- Month Dropdown Selector -->
                        <select name="c_month" onchange="this.form.submit()" style="padding: 6px 10px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.88em; font-weight: 600; color: #334155; background: #ffffff; cursor: pointer;">
                            <?php
                            for ($m = 1; $m <= 12; $m++) {
                                $selected = ($m === $month) ? 'selected' : '';
                                echo "<option value='{$m}' {$selected}>" . date('F', mktime(0, 0, 0, $m, 1)) . "</option>";
                            }
                            ?>
                        </select>

                        <!-- Year Dropdown Selector -->
                        <select name="c_year" onchange="this.form.submit()" style="padding: 6px 10px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.88em; font-weight: 600; color: #334155; background: #ffffff; cursor: pointer;">
                            <?php
                            $currentSystemYear = (int)date('Y');
                            for ($y = $currentSystemYear - 3; $y <= $currentSystemYear + 3; $y++) {
                                $selected = ($y === $year) ? 'selected' : '';
                                echo "<option value='{$y}' {$selected}>{$y}</option>";
                            }
                            ?>
                        </select>

                        <!-- Quick Next Month Arrow Button -->
                        <?php
                        $nextMonth = $month + 1;
                        $nextYear = $year;
                        if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }
                        ?>
                        <a href="<?= $selfPath ?>?module=Admin&action=planner_dashboard&c_month=<?= $nextMonth ?>&c_year=<?= $nextYear ?>" 
                           style="background: #f1f5f9; color: #334155; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.9em; border: 1px solid #e2e8f0;"
                           onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                            &rarr;
                        </a>
                    </form>
                </div>

                <!-- Simple Calendar Grid Engine -->
                <div style="display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; font-size: 0.85em; font-weight: 600; color: #64748b; margin-bottom: 10px;">
                    <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; text-align: center;">
                    <?php
                    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
                    $daysInMonth = date('t', $firstDayOfMonth);
                    $dayOfWeek = date('w', $firstDayOfMonth);

                    for ($i = 0; $i < $dayOfWeek; $i++) {
                        echo '<div style="padding: 12px; background: transparent;"></div>';
                    }

                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $currentDateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        $hasEvent = isset($mappedEvents[$currentDateStr]);
                        
                        $bgStyle = 'background: #f8fafc; color: #334155;';
                        $dotIndicator = '';

                        if ($hasEvent) {
                            $bgStyle = 'background: #e0f2fe; color: #0369a1; font-weight: bold; cursor: pointer; border: 1px solid #bae6fd;';
                            $dotIndicator = '<div style="width: 5px; height: 5px; background: #0284c7; border-radius: 50%; margin: 2px auto 0 auto;"></div>';
                        }

                        $jsDataParam = $hasEvent ? json_encode($mappedEvents[$currentDateStr]) : '[]';
                        ?>
                        <div onclick='handleCalendarDayClick("<?= $currentDateStr ?>", <?= htmlspecialchars($jsDataParam, ENT_QUOTES, 'UTF-8') ?>)' 
                             style="padding: 10px 4px; border-radius: 6px; font-size: 0.9em; transition: all 0.2s; <?= $bgStyle ?>">
                            <?= $day ?>
                            <?= $dotIndicator ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <!-- Right Side: The Sidebar Control Panel -->
            <div id="sideboardPanel" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.01); display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; color: #94a3b8;">
                <div id="sideboardPlaceholder">
                    <p style="font-size: 2.5em; margin: 0;">📅</p>
                    <p style="font-size: 0.95em; margin-top: 10px; font-weight: 500;">Select an highlighted date to view real-time metrics, capacity tracking, and student reviews.</p>
                </div>
                <div id="sideboardContent" style="display: none; width: 100%; text-align: left;">
                    <!-- Content populated by Javascript engine dynamically -->
                </div>
            </div>

            <button onclick="openCreateModal()" style="background: #2563eb; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; cursor: pointer;">
                + Create New Event
            </button>
        </div>

        <!-- CREATE EVENT MODAL WITH FILE UPLOAD, PRICE, AND OPEN REGISTRATION TOGGLE -->
        <div id="createEventModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
            <div style="background: #fff; padding: 24px; border-radius: 8px; width: 100%; max-width: 450px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); max-height: 90vh; overflow-y: auto;">
                <h3 style="margin-top: 0; margin-bottom: 16px; font-size: 1.25em; color: #1e293b;">Create New Event</h3>
                
                <form method="POST" action="?module=Admin&action=createEvent" enctype="multipart/form-data">
                    
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 0.85em; font-weight: 600; color: #475569; margin-bottom: 4px;">Event Name</label>
                        <input type="text" name="name" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box;">
                    </div>

                    <!-- NEW: Event Category Dropdown -->
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 0.85em; font-weight: 600; color: #475569; margin-bottom: 4px;">Event Category</label>
                        <select name="category" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box; background-color: #fff;">
                            <option value="Seminar">Seminar</option>
                            <option value="Workshop">Workshop</option>
                            <option value="Tournament">Tournament</option>
                            <option value="Tryouts">Tryouts</option>
                            <option value="Intramurals">Intramurals</option>
                            <option value="Exhibitions">Exhibitions</option>
                            <option value="Fundraisers">Fundraisers</option>
                            <option value="Orientations">Orientations</option>
                            <option value="Webinars">Webinars</option>
                        </select>
                    </div>
                    
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 0.85em; font-weight: 600; color: #475569; margin-bottom: 4px;">Thumbnail Image</label>
                        <input type="file" name="thumbnail" accept="image/*" style="width: 100%; padding: 4px 0;">
                        <span style="font-size: 0.75em; color: #64748b; display: block; margin-top: 2px;">Leave blank to use default layout.</span>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 0.85em; font-weight: 600; color: #475569; margin-bottom: 4px;">Date & Time</label>
                        <input type="datetime-local" name="event_date" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 0.85em; font-weight: 600; color: #475569; margin-bottom: 4px;">Venue/Location</label>
                        <input type="text" name="location" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 0.85em; font-weight: 600; color: #475569; margin-bottom: 4px;">Description</label>
                        <textarea name="description" rows="3" style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box; resize: vertical;"></textarea>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 0.85em; font-weight: 600; color: #475569; margin-bottom: 4px;">Event Price (₱)</label>
                        <input type="number" step="0.01" min="0" name="price" value="0.00" placeholder="0.00" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 0.85em; font-weight: 600; color: #475569; margin-bottom: 4px;">Max Capacity (Quota)</label>
                        <input type="number" name="max_capacity" min="1" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; background: #f8fafc; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 6px;">
                        <div>
                            <label for="create_open_registration" style="display: block; font-size: 0.85em; font-weight: 600; color: #1e293b; cursor: pointer;">Open Registration</label>
                            <span style="font-size: 0.75em; color: #64748b; display: block;">Allow sign-ups upon event creation.</span>
                        </div>
                        <input type="checkbox" id="create_open_registration" name="open_registration" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 8px;">
                        <button type="button" onclick="closeCreateModal()" style="background: #e2e8f0; color: #334155; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Cancel</button>
                        <button type="submit" style="background: #2563eb; color: #fff; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Save Event</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- EDIT EVENT MODAL WITH FILE STREAMING, PRICE, REGISTRATION TOGGLE, AND ARCHIVE BUTTON -->
        <div id="editEventModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
            <div style="background: #fff; padding: 24px; border-radius: 8px; width: 100%; max-width: 450px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); max-height: 90vh; overflow-y: auto;">
                <h3 style="margin-top: 0; margin-bottom: 16px; font-size: 1.25em; color: #1e293b;">Edit Event</h3>
                
                <form method="POST" action="?module=Admin&action=editEvent" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="edit_event_id">
                    
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 0.85em; font-weight: 600; color: #475569; margin-bottom: 4px;">Event Name</label>
                        <input type="text" name="name" id="edit_event_name" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box;">
                    </div>

                    <!-- NEW: Edit Event Category Dropdown -->
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 0.85em; font-weight: 600; color: #475569; margin-bottom: 4px;">Event Category</label>
                        <select name="category" id="edit_event_category" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box; background-color: #fff;">
                            <option value="Seminar">Seminar</option>
                            <option value="Workshop">Workshop</option>
                            <option value="Tournament">Tournament</option>
                            <option value="Tryouts">Tryouts</option>
                            <option value="Intramurals">Intramurals</option>
                            <option value="Exhibitions">Exhibitions</option>
                            <option value="Fundraisers">Fundraisers</option>
                            <option value="Orientations">Orientations</option>
                            <option value="Webinars">Webinars</option>
                        </select>
                    </div>
                    
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 0.85em; font-weight: 600; color: #475569; margin-bottom: 4px;">Change Thumbnail Image</label>
                        <input type="file" name="thumbnail" accept="image/*" style="width: 100%; padding: 4px 0;">
                        <span style="font-size: 0.75em; color: #64748b; display: block; margin-top: 2px;">Leave blank to preserve current thumbnail.</span>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 0.85em; font-weight: 600; color: #475569; margin-bottom: 4px;">Date & Time</label>
                        <input type="datetime-local" name="event_date" id="edit_event_date" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 0.85em; font-weight: 600; color: #475569; margin-bottom: 4px;">Venue/Location</label>
                        <input type="text" name="location" id="edit_event_location" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 0.85em; font-weight: 600; color: #475569; margin-bottom: 4px;">Description</label>
                        <textarea name="description" id="edit_event_description" rows="3" style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box; resize: vertical;"></textarea>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 0.85em; font-weight: 600; color: #475569; margin-bottom: 4px;">Event Price (₱)</label>
                        <input type="number" step="0.01" min="0" name="price" id="edit_event_price" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 0.85em; font-weight: 600; color: #475569; margin-bottom: 4px;">Max Capacity (Quota)</label>
                        <input type="number" name="max_capacity" id="edit_event_max_capacity" min="1" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; background: #f8fafc; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 6px;">
                        <div>
                            <label for="edit_open_registration" style="display: block; font-size: 0.85em; font-weight: 600; color: #1e293b; cursor: pointer;">Open Registration</label>
                            <span style="font-size: 0.75em; color: #64748b; display: block;">Enable or suspend student registrations.</span>
                        </div>
                        <input type="checkbox" id="edit_open_registration" name="open_registration" value="1" style="width: 18px; height: 18px; cursor: pointer;">
                    </div>

                    <div style="display: flex; justify-content: space-between;">
                        <button type="button" id="archive_event_btn" style="background: #e11d48; color: #fff; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Archive</button>
                        <div style="display: flex; gap: 8px;">
                            <button type="button" onclick="closeEditModal()" style="background: #e2e8f0; color: #334155; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Cancel</button>
                            <button type="submit" style="background: #10b981; color: #fff; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Update Event</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    <!-- Include dynamic Chart.js Library Engine from Official CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- 1. MODAL CONTROLLERS ---
    function openCreateModal() {
        const modal = document.getElementById('createEventModal');
        if (modal) modal.style.display = 'flex';
    }

    function closeCreateModal() {
        const modal = document.getElementById('createEventModal');
        if (modal) modal.style.display = 'none';
    }

    function closeEditModal() {
        const modal = document.getElementById('editEventModal');
        if (modal) modal.style.display = 'none';
    }

    // --- 2. CATEGORY BADGE HELPER ---
    function getCategoryBadge(categoryName) {
        const cat = categoryName || 'Seminar';
        let bg = '#e2e8f0';
        let text = '#334155';

        switch(cat.toLowerCase()) {
            case 'seminar':       bg = '#f3e8ff'; text = '#6b21a8'; break;
            case 'workshop':      bg = '#dbeafe'; text = '#1e40af'; break;
            case 'tournament':    bg = '#ffedd5'; text = '#9a3412'; break;
            case 'tryouts':       bg = '#fef3c7'; text = '#92400e'; break;
            case 'intramurals':   bg = '#dcfce7'; text = '#166534'; break;
            case 'exhibitions':   bg = '#fae8ff'; text = '#86198f'; break;
            case 'fundraisers':   bg = '#ffe4e6'; text = '#9f1239'; break;
            case 'orientations':  bg = '#e0f2fe'; text = '#075985'; break;
            case 'webinars':      bg = '#ccfbf1'; text = '#115e59'; break;
        }

        return `<span style="font-size:0.75em; font-weight:700; text-transform:uppercase; color:${text}; background:${bg}; padding:2px 8px; border-radius:12px;">${cat}</span>`;
    }

    // --- 3. AUTO-FILL AND OPEN EDIT MODAL ---
    function openEditModal(eventObj) {
        if (!eventObj) return;

        const idEl = document.getElementById('edit_event_id');
        if (idEl) idEl.value = eventObj.id || '';

        const nameEl = document.getElementById('edit_event_name');
        if (nameEl) nameEl.value = eventObj.name || '';

        const catEl = document.getElementById('edit_event_category');
        if (catEl) catEl.value = eventObj.category || 'Seminar';

        const dateEl = document.getElementById('edit_event_date');
        if (dateEl) {
            let rawDate = eventObj.event_date || eventObj.edate || '';
            if (rawDate && !rawDate.includes('T')) {
                rawDate = `${rawDate}T12:00`;
            }
            dateEl.value = rawDate;
        }

        const locEl = document.getElementById('edit_event_location');
        if (locEl) locEl.value = eventObj.location || '';

        const descEl = document.getElementById('edit_event_description');
        if (descEl) descEl.value = eventObj.description || '';

        const capEl = document.getElementById('edit_event_max_capacity');
        if (capEl) capEl.value = eventObj.max_capacity || eventObj.capacity || '';

        const priceEl = document.getElementById('edit_event_price');
        if (priceEl) priceEl.value = eventObj.price !== undefined ? eventObj.price : '0.00';

        const regCheckEl = document.getElementById('edit_open_registration');
        if (regCheckEl) {
            const isRegOpen = String(eventObj.open_registration) === '1' || eventObj.open_registration === true;
            regCheckEl.checked = isRegOpen;
        }

        // --- ARCHIVE / UNARCHIVE BUTTON TOGGLE ---
        const archiveBtn = document.getElementById('archive_event_btn');
        if (archiveBtn) {
            const isArchived = String(eventObj.is_active) === '0' || eventObj.is_active === true;

            if (isArchived) {
                archiveBtn.textContent = 'Unarchive Event';
                archiveBtn.style.backgroundColor = '#2563eb'; // Blue button for unarchive
                archiveBtn.style.color = '#ffffff';
                archiveBtn.onclick = function() {
                    if (confirm(`Are you sure you want to unarchive "${eventObj.name}"?`)) {
                        window.location.href = `/Walany/index.php?module=Admin&action=unarchiveEvent&id=${eventObj.id}`;
                    }
                };
            } else {
                archiveBtn.textContent = 'Archive Event';
                archiveBtn.style.backgroundColor = '#ef4444'; // Red button for archive
                archiveBtn.style.color = '#ffffff';
                archiveBtn.onclick = function() {
                    if (confirm(`Are you sure you want to archive "${eventObj.name}"?`)) {
                        window.location.href = `/Walany/index.php?module=Admin&action=archiveEvent&id=${eventObj.id}`;
                    }
                };
            }
        }

        const editModal = document.getElementById('editEventModal');
        if (editModal) editModal.style.display = 'flex';
    }

    // --- 4. CALENDAR SIDEBOARD HANDLER ---
    function handleCalendarDayClick(dateString, events) {
        const sidebar = document.getElementById('sideboardPanel');
        const placeholder = document.getElementById('sideboardPlaceholder');
        const content = document.getElementById('sideboardContent');

        if (!events || events.length === 0) {
            placeholder.style.display = 'block';
            content.style.display = 'none';
            sidebar.style.color = '#94a3b8';
            return;
        }

        placeholder.style.display = 'none';
        content.style.display = 'block';
        sidebar.style.color = 'inherit';

        const activeEv = events[0]; 
        window.currentSelectedEvent = activeEv;

        const maxCap = parseInt(activeEv.max_capacity || activeEv.capacity || 1);
        const regCount = parseInt(activeEv.registrants || 0);
        const fillPct = Math.round((regCount / maxCap) * 100);
        
        const priceVal = parseFloat(activeEv.price || 0);
        const priceLabel = priceVal > 0 ? `₱${priceVal.toFixed(2)}` : 'FREE';

        // Check registration status
        const isRegOpen = String(activeEv.open_registration) === '1' || activeEv.open_registration === true;
        const regStatusBadge = isRegOpen 
            ? `<span style="font-size:0.75em; font-weight:700; color:#15803d; background:#dcfce7; padding:2px 8px; border-radius:12px;">Registration Open</span>`
            : `<span style="font-size:0.75em; font-weight:700; color:#b91c1c; background:#fee2e2; padding:2px 8px; border-radius:12px;">Registration Closed</span>`;

        // Check archived status
        const isArchived = String(activeEv.is_active) === '0' || activeEv.is_active === true;
        const archivedBadge = isArchived 
            ? `<span style="font-size:0.75em; font-weight:700; text-transform:uppercase; color:#475569; background:#e2e8f0; padding:2px 8px; border-radius:12px; border:1px solid #cbd5e1;">📦 Archived</span>`
            : '';

        const categoryBadge = getCategoryBadge(activeEv.category);

        let reviewFeedHtml = '';
        if (activeEv.feedbacks && activeEv.feedbacks.length > 0) {
            activeEv.feedbacks.forEach(f => {
                reviewFeedHtml += `
                    <div style="border-bottom: 1px solid #f1f5f9; padding: 8px 0;">
                        <div style="display:flex; justify-content:space-between; font-size:0.8em; font-weight:600; color:#475569;">
                            <span>Ref ID: ${f.reference_id}</span>
                            <span style="color:#eab308;">★ ${f.rating}</span>
                        </div>
                        <p style="margin:4px 0 0 0; font-size:0.85em; color:#64748b; line-height:1.4;">"${f.comment}"</p>
                    </div>
                `;
            });
        } else {
            reviewFeedHtml = `<p style="font-size:0.85em; color:#94a3b8; font-style:italic; margin:10px 0 0 0;">No reviews written yet for this event.</p>`;
        }

        content.innerHTML = `
            <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 15px; display: flex; flex-direction: column; gap: 8px;">
                <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                    <button type="button" onclick="openEditModal(window.currentSelectedEvent)" style="background: #f1f5f9; border: 1px solid #cbd5e1; padding: 5px 10px; border-radius: 4px; font-size: 0.8em; font-weight: 500; cursor: pointer; color: #334155;">
                        Edit
                    </button>
                    
                    <a href="/Walany/index.php?module=Admin&action=exportguestlist&event_id=${activeEv.id}" 
                    style="display: inline-flex; align-items: center; gap: 6px; background: #ffffff; border: 1px solid #cbd5e1; padding: 4px 10px; border-radius: 4px; font-size: 0.8em; font-weight: 500; color: #334155; text-decoration: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Export Guest List
                    </a>
                </div>

                <div>
                    <div style="display:flex; gap:6px; align-items:center; flex-wrap:wrap; margin-top: 4px;">
                        ${categoryBadge}
                        <span style="font-size:0.75em; font-weight:700; text-transform:uppercase; color:#0284c7; background:#e0f2fe; padding:2px 8px; border-radius:12px;">${dateString}</span>
                        ${regStatusBadge}
                        ${archivedBadge}
                    </div>
                    <h3 style="margin: 8px 0 4px 0; color:#0f172a; font-size:1.2em;">${activeEv.name}</h3>
                    <p style="margin:0; font-size:0.85em; color:#64748b;">📍 ${activeEv.location} &bull; <strong>Price: ${priceLabel}</strong></p>
                </div>
            </div>
            
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px; margin-bottom:15px;">
                <div style="background:#f8fafc; padding:10px; border-radius:6px; border:1px solid #f1f5f9;">
                    <span style="display:block; font-size:0.75em; color:#64748b; font-weight:600;">Total Attendees</span>
                    <span style="font-size:1.1em; font-weight:700; color:#0f172a;">${fillPct}%</span>
                    <span style="display:block; font-size:0.7em; color:#94a3b8;">(${regCount}/${maxCap} slots)</span>
                </div>
                <div style="background:#f8fafc; padding:10px; border-radius:6px; border:1px solid #f1f5f9;">
                    <span style="display:block; font-size:0.75em; color:#64748b; font-weight:600;">AVG RATING</span>
                    <span style="font-size:1.1em; font-weight:700; color:#eab308;">★ ${activeEv.rating || '0.0'}</span>
                    <span style="display:block; font-size:0.7em; color:#94a3b8;">Student Score</span>
                </div>
            </div>

            <h4 style="margin:15px 0 5px 0; font-size:0.9em; font-weight:700; text-transform:uppercase; color:#475569; letter-spacing:0.05em;">Student Reviews Feed</h4>
            <div style="max-height:160px; overflow-y:auto; padding-right:5px;">
                ${reviewFeedHtml}
            </div>
        `;
    }
</script>

<!-- Live Attendance Logs Container -->
<div style="background: #ffffff; padding: 24px; border-radius: 8px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    
    <!-- Top Header Layout Row -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
        <div>
            <h2 style="margin: 0 0 4px 0; font-size: 22px; font-weight: 700; color: #1e293b;">Live Attendance Logs</h2>
            <p style="margin: 0; font-size: 14px; color: #64748b;">Real-time stream of the latest event check-ins.</p>
        </div>
        <div>
            <input type="text" id="log-search-input" placeholder="Search logs..." autocomplete="off"
                   style="width: 260px; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; color: #334155; outline: none; transition: border-color 0.2s;">
        </div>
    </div>

    <!-- Dynamic Event Filter Tags Bar -->
    <div id="event-tags-container" style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
        <!-- Injected via JavaScript dynamically -->
    </div>

    <!-- Flattened Logs Data Layout Matrix -->
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
            <thead>
                <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 12px 16px; font-weight: 600; color: #475569; width: 20%;">Reference ID</th>
                    <th style="padding: 12px 16px; font-weight: 600; color: #475569; width: 30%;">Full Name</th>
                    <th style="padding: 12px 16px; font-weight: 600; color: #475569; width: 25%;">Event</th>
                    <th style="padding: 12px 16px; font-weight: 600; color: #475569; width: 25%;">Time Checked In</th>
                </tr>
            </thead>
            <tbody id="live-logs-body" style="color: #334155;">
                <tr>
                    <td colspan="4" style="padding: 24px; text-align: center; color: #94a3b8;">Initializing live connection logs...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination Controls Footer Block -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid #f1f5f9;">
        <div style="font-size: 14px; color: #64748b;" id="pagination-info-text">
            Showing 0 to 0 of 0 entries
        </div>
        <div style="display: flex; gap: 8px;">
            <button id="btn-prev-page" style="padding: 8px 14px; border: 1px solid #cbd5e1; background: #fff; border-radius: 6px; font-size: 13px; font-weight: 500; color: #334155; cursor: pointer; transition: all 0.2s; outline: none;">
                Previous
            </button>
            <button id="btn-next-page" style="padding: 8px 14px; border: 1px solid #cbd5e1; background: #fff; border-radius: 6px; font-size: 13px; font-weight: 500; color: #334155; cursor: pointer; transition: all 0.2s; outline: none;">
                Next
            </button>
        </div>
    </div>
</div>

<style>
    #log-search-input:focus { border-color: #3b82f6; }
    .log-row { border-bottom: 1px solid #f1f5f9; transition: background-color 0.2s ease; }
    .log-row:hover { background-color: #f8fafc; }
    .highlight-new-row { background-color: #f0fdf4 !important; }
    .event-tag-btn { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; cursor: pointer; border: 1px solid #e2e8f0; background-color: #f8fafc; color: #64748b; transition: all 0.2s; }
    .event-tag-btn.active { background-color: #3b82f6; color: #ffffff; border-color: #3b82f6; }
    button:disabled { opacity: 0.5; cursor: not-allowed !important; }
</style>

<!-- Real-time dynamic connection handler -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('log-search-input');
    const logsBody = document.getElementById('live-logs-body');
    const tagsContainer = document.getElementById('event-tags-container');
    const btnPrev = document.getElementById('btn-prev-page');
    const btnNext = document.getElementById('btn-next-page');
    const paginationText = document.getElementById('pagination-info-text');
    
    let allLogsDataset = [];
    let activeSearchQuery = '';
    let selectedEventFilter = 'ALL';
    let knownCheckInKeys = new Set();
    let isFirstLoad = true;

    // Pagination Parameters
    let currentPage = 1;
    const itemsPerPage = 10;

    async function fetchLiveLogs() {
        try {
            const response = await fetch(`/Walany/index.php?module=Admin&action=getlivelogsapi&search=${encodeURIComponent(activeSearchQuery)}`);
            if (!response.ok) throw new Error('Network offline');
            allLogsDataset = await response.json();
            
            renderEventTags();
            processAndRenderTable();
        } catch (error) {
            console.error('Log sync failure:', error);
        }
    }

    // Dynamic extraction and assembly of Event Tag filters
    function renderEventTags() {
        const uniqueEvents = new Set();
        allLogsDataset.forEach(log => uniqueEvents.add(log.event_name));
        
        let tagsHtml = `<button class="event-tag-btn ${selectedEventFilter === 'ALL' ? 'active' : ''}" data-event="ALL">All Events</button>`;
        
        uniqueEvents.forEach(evt => {
            tagsHtml += `<button class="event-tag-btn ${selectedEventFilter === evt ? 'active' : ''}" data-event="${evt}">${evt}</button>`;
        });
        
        tagsContainer.innerHTML = tagsHtml;
    }

    // Filter processing and layout pagination generation logic
    function processAndRenderTable() {
        // Step 1: Filter raw set based on selected event tag string
        let filteredLogs = allLogsDataset;
        if (selectedEventFilter !== 'ALL') {
            filteredLogs = allLogsDataset.filter(log => log.event_name === selectedEventFilter);
        }

        const totalItems = filteredLogs.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;
        
        // Boundaries safety fallback checking
        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        // Step 2: Extract sliding page index boundaries
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
        const paginatedSlice = filteredLogs.slice(startIndex, endIndex);

        // Step 3: Render data nodes into DOM elements
        if (paginatedSlice.length === 0) {
            logsBody.innerHTML = `
                <tr>
                    <td colspan="4" style="padding: 32px; text-align: center; color: #94a3b8;">No matching check-in records found.</td>
                </tr>
            `;
            paginationText.textContent = "Showing 0 to 0 of 0 entries";
            btnPrev.disabled = true;
            btnNext.disabled = true;
            return;
        }

        let htmlRows = '';
        paginatedSlice.forEach(log => {
            const uniqueKey = `${log.reference_id}_${log.time_checked_in}`;
            const isNew = !knownCheckInKeys.has(uniqueKey) && !isFirstLoad;
            
            knownCheckInKeys.add(uniqueKey);
            const flashClass = isNew ? 'highlight-new-row' : '';

            htmlRows += `
                <tr class="log-row ${flashClass}">
                    <td style="padding: 16px; color: #0284c7; font-weight: 500;">
                        <a href="#" style="color: #0284c7; text-decoration: none;">#${log.reference_id.replace('#', '')}</a>
                    </td>
                    <td style="padding: 16px; font-weight: 500; color: #1e293b;">${log.fullname}</td>
                    <td style="padding: 16px;">
                        <span style="background-color: #f1f5f9; color: #475569; padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: 500;">
                            ${log.event_name}
                        </span>
                    </td>
                    <td style="padding: 16px; color: #16a34a; font-weight: 600;">${log.time_checked_in}</td>
                </tr>
            `;
        });

        logsBody.innerHTML = htmlRows;
        paginationText.textContent = `Showing ${startIndex + 1} to ${endIndex} of ${totalItems} entries`;

        // Handle buttons state management
        btnPrev.disabled = (currentPage === 1);
        btnNext.disabled = (currentPage === totalPages);

        setTimeout(() => {
            document.querySelectorAll('.highlight-new-row').forEach(row => {
                row.classList.remove('highlight-new-row');
            });
        }, 2500);

        isFirstLoad = false;
    }

    // Intercept tag filtering selections dynamically
    tagsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('event-tag-btn')) {
            selectedEventFilter = e.target.getAttribute('data-event');
            currentPage = 1; // reset view tracking back to first slice index
            processAndRenderTable();
            
            // Set styles dynamically active immediately
            document.querySelectorAll('.event-tag-btn').forEach(btn => btn.classList.remove('active'));
            e.target.classList.add('active');
        }
    });

    // Pagination Interaction Control Configuration Hooks
    btnPrev.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            processAndRenderTable();
        }
    });

    btnNext.addEventListener('click', () => {
        currentPage++;
        processAndRenderTable();
    });

    // Search Interaction Controller Setup Block
    let debounceTimer;
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            activeSearchQuery = this.value;
            isFirstLoad = true; 
            currentPage = 1; 
            fetchLiveLogs();
        }, 400); 
    });

    fetchLiveLogs();
    setInterval(fetchLiveLogs, 3000); // Poll tracking database loops cleanly every 3 seconds
});
</script>
</body>
</html>