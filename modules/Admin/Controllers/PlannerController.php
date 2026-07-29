<?php
require_once __DIR__ . '/../Models/PlannerModel.php';
require_once __DIR__ . '/../../../core/Config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PlannerController {
    private $model;

    public function __construct() {
        $this->model = new PlannerModel();
    }

    // Renders the Event Planner Dashboard with local PHP variable scoping
    public function plannerDashboard() {
        date_default_timezone_set('Asia/Manila');

        if (session_status() === PHP_SESSION_NONE) { 
            session_start(); 
        }

        // Security check: Match your system's role identifier for planners (e.g., 'planner' or 'coordinator')
        if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'planner' && $_SESSION['role'] !== 'coordinator')) {
            header("Location: /Walany/index.php?module=Auth&action=login");
            exit;
        }

        // Gather Profile Identity Details from Session
        $currentPlannerName = htmlspecialchars($_SESSION['manager_name'] ?? 'Event Coordinator');
        $currentPlannerRole = htmlspecialchars(ucfirst($_SESSION['role'] ?? 'Event Planner'));
        
        // Fetch official email from the database
        $plannerEmail = $this->model->getPlannerEmail($_SESSION['manager_id']);

        // Gather metrics for the KPI Stats Cards
        $activeEventsCount = $this->model->getActiveEventsCount();
        $totalRegistrants  = $this->model->getTotalRegistrantsCount();
        $topEventData      = $this->model->getTopPerformingEvent();

        // Gather datasets for Chart.js engines
        $capacityRaw   = $this->model->getCapacityVsRegistrations();
        $velocityRaw   = $this->model->getRegistrationVelocity();
        $turnoutData   = $this->model->getTurnoutMetrics();

        // Format data arrays for smooth JavaScript JSON extraction
        $chartA_labels = []; $chartA_caps = []; $chartA_regs = [];
        foreach ($capacityRaw as $row) {
            $chartA_labels[] = $row['name'];
            $chartA_caps[]   = (int)$row['max_capacity'];
            $chartA_regs[]   = (int)$row['current_registrations'];
        }

        $chartB_labels = []; $chartB_values = [];
        foreach ($velocityRaw as $row) {
            $chartB_labels[] = date('M d', strtotime($row['checkin_date']));
            $chartB_values[] = (int)$row['signups'];
        }

        // Process calendar positions
        $month = isset($_GET['c_month']) ? (int)$_GET['c_month'] : (int)date('m');
        $year  = isset($_GET['c_year']) ? (int)$_GET['c_year'] : (int)date('Y');

        $calendarEvents = $this->model->getCalendarEvents();

        // Organize events into a clean indexed array grouped by date string
        $mappedEvents = [];
        foreach ($calendarEvents as $ev) {
            $details = $this->model->getEventFeedbackDetails($ev['id']);
            $mappedEvents[$ev['edate']][] = [
                'id'                => $ev['id'],
                'name'              => htmlspecialchars($ev['name']),
                'category'          => $ev['category'] ?? 'Seminar',
                'event_date'        => $ev['edate'],
                'location'          => htmlspecialchars($ev['location']),
                'description'       => htmlspecialchars($ev['description'] ?? ''),
                'max_capacity'      => (int)$ev['max_capacity'],
                'price'             => $ev['price'] ?? '0.00',
                'open_registration' => $ev['open_registration'] ?? 1,
                'is_active'        => $ev['is_active'] ?? 1,
                'is_featured' => (int)($ev['is_featured'] ?? 0),
                'registrants'       => $details['fill_rate'],
                'rating'            => $details['avg_rating'],
                'feedbacks'         => $details['feedbacks']
            ];
        }

        // Load the view file (allowing variables above to inherit scope)
        require_once __DIR__ . '/../Views/plannerDashboard.php';
    }

    public function toggleFeatured() {
        $eventId = $_GET['id'] ?? null;
        if ($eventId) {
            $this->model->toggleFeaturedEvent($eventId);
        }
        header("Location: /Walany/index.php?module=Admin&action=planner_dashboard");
        exit();
    }

    // Handles creating a new event
    public function createEvent() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Sanitize standard string inputs
            $name = trim($_POST['name'] ?? '');
            $location = trim($_POST['location'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $max_capacity = intval($_POST['max_capacity'] ?? 0);
            
            // 2. Normalize the ISO datetime-local 'T' spacer into a clean standard MySQL format
            $rawDate = $_POST['event_date'] ?? '';
            $formattedDate = !empty($rawDate) ? str_replace('T', ' ', $rawDate) . ':00' : date('Y-m-d H:i:s');

            // 3. Robust Thumbnail Upload Validation Engine
            // FIXED: Standard default fallback path structure
            $thumbnailName = '/Walany/assets/images/event_thumbnails/cvsu-imus.png'; 
            
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE) {
                $file = $_FILES['thumbnail'];
                
                // Check for basic PHP framework upload errors
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    header("Location: /Walany/index.php?module=Admin&action=planner_dashboard&status=error&message=" . urlencode("File upload error code: " . $file['error']));
                    exit();
                }

                // Enforce safe size boundary limitations (e.g., maximum 5MB restriction)
                $maxFileSize = 5 * 1024 * 1024; 
                if ($file['size'] > $maxFileSize) {
                    header("Location: /Walany/index.php?module=Admin&action=planner_dashboard&status=error&message=" . urlencode("Image file size exceeds the maximum 5MB limit."));
                    exit();
                }

                // Validate actual MIME types securely using server-side inspection
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($file['tmp_name']);
                $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

                if (!in_array($mimeType, $allowedTypes)) {
                    header("Location: /Walany/index.php?module=Admin&action=planner_dashboard&status=error&message=" . urlencode("Invalid file format. Only JPG, PNG, and WEBP items are allowed."));
                    exit();
                }

                // Set up target delivery structure inside app boundaries
                // FIXED: Resolved 'asssets' typo and forced a trailing forward slash
                $uploadDir = __DIR__ . '/../../../assets/images/event_thumbnails/';
                
                // Generate directory structures dynamically if missing from local environment
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Sanitize file extensions cleanly
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                if (empty($extension)) {
                    $extension = ($mimeType === 'image/png') ? 'png' : (($mimeType === 'image/webp') ? 'webp' : 'jpg');
                }

                // Assign unique hashes to filenames to prevent collision errors
                $uniqueFileName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
                $destinationPath = $uploadDir . $uniqueFileName;

                // Deliver temporary upload artifacts safely to targeted production directory
                if (!move_uploaded_file($file['tmp_name'], $destinationPath)) {
                    header("Location: /Walany/index.php?module=Admin&action=planner_dashboard&status=error&message=" . urlencode("Failed to save uploaded image file to server storage."));
                    exit();
                }
                
                // FIXED: Store as a standard web path structure matching your fallback URL format
                $thumbnailName = '/Walany/assets/images/event_thumbnails/' . $uniqueFileName;
            }

            // 4. Map the collection arrays directly to the tokens your Model specifies (:edate)
            $price = floatval($_POST['price'] ?? 0.00);
            $open_registration = isset($_POST['open_registration']) ? 1 : 0;

            $modelPayload = [
                'name'              => $name,
                'edate'             => $formattedDate,
                'location'          => $location,
                'description'       => $description,
                'thumbnail'         => $thumbnailName,
                'max_capacity'      => $max_capacity,
                'price'             => $price,
                'open_registration' => $open_registration
            ];

            // 5. Fire transaction out to Database layer
            $success = $this->model->insertEvent($modelPayload);

            if ($success) {
                header("Location: /Walany/index.php?module=Admin&action=planner_dashboard&status=success&message=" . urlencode("Event successfully created!"));
            } else {
                header("Location: /Walany/index.php?module=Admin&action=planner_dashboard&status=error&message=" . urlencode("Database mapping operation aborted. Profile creation failed."));
            }
            exit();
        }

        // Default processing for GET operations - simply deliver target form context markup
        require_once 'modules/Admin/Views/create-event.php';
    }

    // Handles updating an existing event
    public function editEvent() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            
            $currentEvent = $this->model->getEventById($id);
            $thumbnailDbPath = $currentEvent['thumbnail'] ?? '/Walany/assets/images/event_thumbnails/cvsu-imus.png';

            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/Walany/assets/images/event_thumbnails/';
                $fileTmpPath = $_FILES['thumbnail']['tmp_name'];
                $fileName = $_FILES['thumbnail']['name'];
                $cleanFileName = time() . '_' . preg_replace("/[^a-zA-Z0-9.\-_]/", "", $fileName);

                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                if (!empty($currentEvent['thumbnail']) && strpos($currentEvent['thumbnail'], 'cvsu-imus.png') === false) {
                    $oldFilePath = $_SERVER['DOCUMENT_ROOT'] . $currentEvent['thumbnail'];
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                if (move_uploaded_file($fileTmpPath, $targetDir . $cleanFileName)) {
                    $thumbnailDbPath = '/Walany/assets/images/event_thumbnails/' . $cleanFileName;
                }
            }

            $data = [
                'id'                => $id,
                'name'              => trim($_POST['name']),
                'edate'             => $_POST['event_date'],
                'location'          => trim($_POST['location']),
                'description'       => trim($_POST['description'] ?? ''),
                'thumbnail'         => $thumbnailDbPath,
                'max_capacity'      => (int)$_POST['max_capacity'],
                'price'             => floatval($_POST['price'] ?? 0.00),
                'open_registration' => isset($_POST['open_registration']) ? 1 : 0
            ];
            
            if ($data['id'] > 0 && !empty($data['name'])) {
                $this->model->updateEvent($data);
            }
        }
        header("Location: /Walany/index.php?module=Admin&action=planner_dashboard");
        exit;
    }

    // Handles archiving an event along with its local thumbnail image file
    public function archiveEvent() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $this->model->archiveEvent($id);
        }
        header("Location: /Walany/index.php?module=Admin&action=planner_dashboard&status=success&message=" . urlencode("Event archived successfully."));
        exit;
    }

    public function unarchiveEvent() {
        $eventId = $_GET['id'] ?? null;

        if ($eventId) {
            $this->model->unarchiveEventById($eventId);
        }

        header("Location: /Walany/index.php?module=Admin&action=planner_dashboard");
        exit();
    }

    public function toggleRegistration() {
        $id = intval($_GET['id'] ?? 0);
        $status = intval($_GET['status'] ?? 0);
        if ($id > 0) {
            $this->model->toggleRegistrationStatus($id, $status);
        }
        header("Location: /Walany/index.php?module=Admin&action=planner_dashboard");
        exit;
    }

    // Live JSON feed endpoint matching layout extensions
    public function getLiveLogsApi() {
        header('Content-Type: application/json');
        $search = trim($_GET['search'] ?? '');
        
        // Pull all relevant items so the frontend pagination handles splits safely
        $logs = $this->model->getLiveCheckIns($search);

        $formattedLogs = array_map(function($log) {
            $fullName = trim($log['last_name'] . ', ' . $log['first_name']);
            
            return [
                'reference_id'    => htmlspecialchars($log['reference_id']),
                'fullname'        => htmlspecialchars($fullName),
                'event_name'      => htmlspecialchars($log['event_name']),
                'time_checked_in' => date('h:i A (M d, Y)', strtotime($log['time_checked_in']))
            ];
        }, $logs);

        echo json_encode($formattedLogs);
        exit();
    }

    // Generates and forces download of a clean event guest list CSV file
    public function exportGuestList() {
        $eventId = intval($_GET['event_id'] ?? 0);
        
        $event = $this->model->getEventById($eventId);
        if (!$event) {
            header("Location: /Walany/index.php?module=Admin&action=planner_dashboard&status=error&message=" . urlencode("Event not found."));
            exit();
        }

        $guests = $this->model->getEventGuestList($eventId);

        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $event['name']);
        $filename = "GuestList_" . $safeName . "_" . date('Y-m-d') . ".xls";

        // Wipe out any accidental output buffers to ensure formatting tags are pure
        if (ob_get_length()) ob_end_clean();

        // Native Excel stream headers
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Cache-Control: max-age=0");
        header("Pragma: public");

        // Open the XML workbook wrapper
        echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        echo "<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\"\n";
        echo " xmlns:o=\"urn:schemas-microsoft-com:office:office\"\n";
        echo " xmlns:x=\"urn:schemas-microsoft-com:office:excel\"\n";
        echo " xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\"\n";
        echo " xmlns:html=\"http://www.w3.org/TR/REC-html40\">\n";
        
        // Structure exact styles for borders, fonts, and alignments
        echo " <Styles>\n";
        // Header Row Style: Midnight Blue background, bold white text, clean borders
        echo "  <Style ss:ID=\"HeaderStyle\">\n";
        echo "   <Font ss:FontName=\"Segoe UI\" ss:Size=\"11\" ss:Bold=\"1\" ss:Color=\"#FFFFFF\"/>\n";
        echo "   <Interior ss:Color=\"#1E293B\" ss:Pattern=\"Solid\"/>\n";
        echo "   <Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\" ss:WrapText=\"1\"/>\n";
        echo "   <Borders>\n";
        echo "    <Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#475569\"/>\n";
        echo "    <Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#475569\"/>\n";
        echo "    <Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#475569\"/>\n";
        echo "    <Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#475569\"/>\n";
        echo "   </Borders>\n";
        echo "  </Style>\n";

        // Standard Row Style: Soft border lines, clean typography alignment
        echo "  <Style ss:ID=\"DataStyle\">\n";
        echo "   <Font ss:FontName=\"Segoe UI\" ss:Size=\"10\"/>\n";
        echo "   <Alignment ss:Vertical=\"Center\"/>\n";
        echo "   <Borders>\n";
        echo "    <Border ss:Position=\"Bottom\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#E2E8F0\"/>\n";
        echo "    <Border ss:Position=\"Left\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#E2E8F0\"/>\n";
        echo "    <Border ss:Position=\"Right\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#E2E8F0\"/>\n";
        echo "    <Border ss:Position=\"Top\" ss:LineStyle=\"Continuous\" ss:Weight=\"1\" ss:Color=\"#E2E8F0\"/>\n";
        echo "   </Borders>\n";
        echo "  </Style>\n";

        // Centered Text Data Style (for Ref ID, Contact Numbers, Ratings)
        echo "  <Style ss:ID=\"CenteredData\">\n";
        echo "   <Parent ss:StyleID=\"DataStyle\"/>\n";
        echo "   <Alignment ss:Horizontal=\"Center\" ss:Vertical=\"Center\"/>\n";
        echo "  </Style>\n";
        echo " </Styles>\n";

        echo " <Worksheet ss:Name=\"Guest List\">\n";
        echo "  <Table>\n";

        // Step 1: Pre-calculate adaptive column widths based on longest text strings
        $widthRefId = 110;
        $widthLastName = 130;
        $widthFirstName = 130;
        $widthEmail = 180;
        $widthPhone = 120;
        $widthStatus = 150;
        $widthRating = 110;
        $widthComment = 220;

        foreach ($guests as $guest) {
            $checkIn = !empty($guest['time_checked_in']) ? 'Checked In at ' . date('h:i A', strtotime($guest['time_checked_in'])) : 'Not Checked In';
            $rating = !empty($guest['feedback_rating']) ? $guest['feedback_rating'] . ' Stars' : 'No Rating';
            $comment = !empty($guest['feedback_comment']) ? $guest['feedback_comment'] : 'No Comment Provided';

            // Pad column widths dynamically if actual text strings exceed default width spacing (approx 7.5 pixels per char)
            $widthLastName = max($widthLastName, (strlen($guest['last_name'] ?? '') * 7.5) + 20);
            $widthFirstName = max($widthFirstName, (strlen($guest['first_name'] ?? '') * 7.5) + 20);
            $widthEmail = max($widthEmail, (strlen($guest['email'] ?? '') * 7.5) + 20);
            $widthPhone = max($widthPhone, (strlen($guest['contact_number'] ?? '') * 7.5) + 20);
            $widthStatus = max($widthStatus, (strlen($checkIn) * 7.5) + 20);
            $widthComment = max($widthComment, (strlen($comment) * 7.5) + 20);
        }

        // Apply calculated layout columns widths
        echo "   <Column ss:Width=\"$widthRefId\"/>\n";
        echo "   <Column ss:Width=\"$widthLastName\"/>\n";
        echo "   <Column ss:Width=\"$widthFirstName\"/>\n";
        echo "   <Column ss:Width=\"$widthEmail\"/>\n";
        echo "   <Column ss:Width=\"$widthPhone\"/>\n";
        echo "   <Column ss:Width=\"$widthStatus\"/>\n";
        echo "   <Column ss:Width=\"$widthRating\"/>\n";
        echo "   <Column ss:Width=\"$widthComment\"/>\n";

        // Step 2: Render Title / Header Row Matrix
        echo "   <Row ss:Height=\"26\">\n";
        $headers = ['Reference ID', 'Last Name', 'First Name', 'Email Address', 'Contact Number', 'Check-In Status', 'Feedback Rating', 'Feedback Comment'];
        foreach ($headers as $header) {
            echo "    <Cell ss:StyleID=\"HeaderStyle\"><Data ss:Type=\"String\">" . htmlspecialchars($header) . "</Data></Cell>\n";
        }
        echo "   </Row>\n";

        // Step 3: Populate Table Grid Records
        if (empty($guests)) {
            echo "   <Row ss:Height=\"30\">\n";
            echo "    <Cell ss:MergeAcross=\"7\" ss:StyleID=\"CenteredData\"><Data ss:Type=\"String\">No registrant records found matching this event ID.</Data></Cell>\n";
            echo "   </Row>\n";
        } else {
            foreach ($guests as $guest) {
                $checkIn = !empty($guest['time_checked_in']) ? 'Checked In at ' . date('h:i A', strtotime($guest['time_checked_in'])) : 'Not Checked In';
                $rating = !empty($guest['feedback_rating']) ? $guest['feedback_rating'] . ' Stars' : 'No Rating';
                $comment = !empty($guest['feedback_comment']) ? $guest['feedback_comment'] : 'No Comment Provided';

                echo "   <Row ss:Height=\"22\">\n";
                echo "    <Cell ss:StyleID=\"CenteredData\"><Data ss:Type=\"String\">" . htmlspecialchars($guest['reference_id'] ?? '') . "</Data></Cell>\n";
                echo "    <Cell ss:StyleID=\"DataStyle\"><Data ss:Type=\"String\">" . htmlspecialchars($guest['last_name'] ?? '') . "</Data></Cell>\n";
                echo "    <Cell ss:StyleID=\"DataStyle\"><Data ss:Type=\"String\">" . htmlspecialchars($guest['first_name'] ?? '') . "</Data></Cell>\n";
                echo "    <Cell ss:StyleID=\"DataStyle\"><Data ss:Type=\"String\">" . htmlspecialchars($guest['email'] ?? '') . "</Data></Cell>\n";
                echo "    <Cell ss:StyleID=\"CenteredData\"><Data ss:Type=\"String\">" . htmlspecialchars($guest['contact_number'] ?? '') . "</Data></Cell>\n";
                echo "    <Cell ss:StyleID=\"DataStyle\"><Data ss:Type=\"String\">" . htmlspecialchars($checkIn) . "</Data></Cell>\n";
                echo "    <Cell ss:StyleID=\"CenteredData\"><Data ss:Type=\"String\">" . htmlspecialchars($rating) . "</Data></Cell>\n";
                echo "    <Cell ss:StyleID=\"DataStyle\"><Data ss:Type=\"String\">" . htmlspecialchars($comment) . "</Data></Cell>\n";
                echo "   </Row>\n";
            }
        }

        echo "  </Table>\n";
        echo " </Worksheet>\n";
        echo "</Workbook>\n";
        exit();
    }

    public function sendBroadcastEmail() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /Walany/index.php?module=Admin&action=planner_dashboard");
            exit();
        }

        $eventId = $_POST['event_id'] ?? null;
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (!$eventId || empty($subject) || empty($message)) {
            header("Location: /Walany/index.php?module=Admin&action=planner_dashboard&status=error&message=" . urlencode("All fields are required."));
            exit();
        }

        // 1. Fetch participants for this event
        $participants = $this->model->getEventParticipants($eventId);

        if (empty($participants)) {
            header("Location: /Walany/index.php?module=Admin&action=planner_dashboard&status=error&message=" . urlencode("No registered participants found for this event."));
            exit();
        }

        // 2. Load PHPMailer files directly from root/core/libs/PHPMailer
        $phpMailerDir = __DIR__ . '/../../../libs/PHPMailer';
        
        // Check if PHPMailer files exist in 'src/' subfolder or directly in directory
        if (file_exists($phpMailerDir . '/src/PHPMailer.php')) {
            require_once $phpMailerDir . '/src/Exception.php';
            require_once $phpMailerDir . '/src/PHPMailer.php';
            require_once $phpMailerDir . '/src/SMTP.php';
        } else {
            require_once $phpMailerDir . '/Exception.php';
            require_once $phpMailerDir . '/PHPMailer.php';
            require_once $phpMailerDir . '/SMTP.php';
        }

        $sentCount = 0;

        // 3. Loop through participants and send via SMTP
        foreach ($participants as $participant) {
            $toEmail = $participant['email'] ?? null;
            $toName  = $participant['full_name'] ?? 'Participant';

            if (!$toEmail) continue;

            $mail = new PHPMailer(true);

            $mail->SMTPDebug = 2; // Output detailed server logs
            $mail->Debugoutput = 'html';

            try {
                // --- SMTP CONFIGURATION ---
                $mail->isSMTP();
                $mail->Host       = SMTP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = SMTP_USER;
                $mail->Password   = SMTP_PASS;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // --- SENDER & RECIPIENT ---
                $mail->setFrom('yeahlow24@gmail.com', 'Walania Events (No-Reply)');
                $mail->addAddress($toEmail, $toName);

                // --- EMAIL CONTENT ---
                $formattedMsg = nl2br(htmlspecialchars($message));
                
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;'>
                        <h2 style='color: #2563eb; margin-top: 0;'>Walania Events Announcement</h2>
                        <p style='color: #475569;'>Hello <strong>{$toName}</strong>,</p>
                        <div style='background: #f8fafc; padding: 15px; border-left: 4px solid #2563eb; margin: 15px 0; color: #1e293b; line-height: 1.6;'>
                            {$formattedMsg}
                        </div>
                        <hr style='border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;'>
                        <p style='font-size: 0.8em; color: #94a3b8;'>This is an official announcement regarding your event registration.</p>
                    </div>
                ";
                $mail->AltBody = strip_tags($message);

                $mail->send();
                $sentCount++;
            } catch (Exception $e) {
                error_log("Failed to send email to {$toEmail}: " . $mail->ErrorInfo);
            }
        }

        header("Location: /Walany/index.php?module=Admin&action=planner_dashboard&status=success&message=" . urlencode("Successfully sent broadcast email to {$sentCount} participant(s)."));
        exit();
    }

    public function exportDataXml() {
        $xmlContent = $this->model->exportFullDatabaseToXml();
        
        header('Content-Type: text/xml; charset=utf-8');
        header('Content-Disposition: attachment; filename="walania_backup_' . date('Y-m-d_H-i-s') . '.xml"');
        echo $xmlContent;
        exit();
    }

    public function importDataXml() {
        $redirectUrl = '/Walany/index.php?module=Admin&action=planner_dashboard';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['xml_file'])) {
            $uploadedFile = $_FILES['xml_file']['tmp_name'];
            
            if ($this->model->importFullDatabaseFromXml($uploadedFile)) {
                header('Location: ' . $redirectUrl . '&status=import_success');
            } else {
                header('Location: ' . $redirectUrl . '&status=import_failed');
            }
        } else {
            header('Location: ' . $redirectUrl . '&status=invalid_request');
        }
        exit();
    }
}
?>