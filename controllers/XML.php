<?php
require_once __DIR__ . "/../models/Database.php";
require_once __DIR__ . "/../models/eventModel.php";
require_once __DIR__ . "/../models/registrantModel.php";

/**
 * Export events from walania_event table to XML format
 * @return SimpleXMLElement XML object containing all events
 */
function exportEvents() {
    $database = new Database();
    $dbConnection = $database->getConnection();
    $eventModel = new EventModel($dbConnection);
    $events = $eventModel->getAllEvents();
    
    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><events></events>');
    
    foreach ($events as $event) {
        $eventNode = $xml->addChild('event');
        $eventNode->addChild('id', $event['id']);
        $eventNode->addChild('name', htmlspecialchars($event['name'], ENT_XML1 | ENT_COMPAT, 'UTF-8'));
        $eventNode->addChild('event_date', $event['event_date']);
        $eventNode->addChild('location', htmlspecialchars($event['location'], ENT_XML1 | ENT_COMPAT, 'UTF-8'));
        $eventNode->addChild('description', htmlspecialchars($event['description'], ENT_XML1 | ENT_COMPAT, 'UTF-8'));
    }
    
    return $xml;
}

/**
 * Export registrants from walania_registrant table to XML format
 * @return SimpleXMLElement XML object containing all registrants
 */
function exportRegistrants() {
    $database = new Database();
    $dbConnection = $database->getConnection();
    $registrantModel = new RegistrantModel($dbConnection);
    $registrants = $registrantModel->getAllRegistrants();
    
    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><registrants></registrants>');
    
    foreach ($registrants as $registrant) {
        $regNode = $xml->addChild('registrant');
        $regNode->addChild('id', $registrant['id']);
        $regNode->addChild('full_name', htmlspecialchars($registrant['full_name'], ENT_XML1 | ENT_COMPAT, 'UTF-8'));
        $regNode->addChild('age', $registrant['age']);
        $regNode->addChild('email', htmlspecialchars($registrant['email'], ENT_XML1 | ENT_COMPAT, 'UTF-8'));
        $regNode->addChild('contact_number', htmlspecialchars($registrant['contact_number'], ENT_XML1 | ENT_COMPAT, 'UTF-8'));
        $regNode->addChild('preference_allergy', htmlspecialchars($registrant['preference_allergy'] ?? '', ENT_XML1 | ENT_COMPAT, 'UTF-8'));
        $regNode->addChild('event_id', $registrant['event_id'] ?? '');
        $regNode->addChild('user_id', $registrant['user_id'] ?? '');
        $regNode->addChild('registered_at', $registrant['registered_at']);
    }
    
    return $xml;
}

function importEvents($xmlContent) {
    $xml = simplexml_load_string($xmlContent);
    if (!$xml || !isset($xml->event)) {
        return 'Successfully imported (0) rows';
    }

    $model = new EventModel((new Database())->getConnection());
    $count = 0;

    foreach ($xml->event as $event) {
        $name = trim((string) $event->name);
        $date = trim((string) $event->event_date);
        if ($name === '' || $date === '') {
            continue;
        }
        if ($model->addEvent($name, $date, trim((string) $event->location), trim((string) $event->description))) {
            $count++;
        }
    }

    return 'Successfully imported ('. $count .') rows';
}

function importRegistrants($xmlContent) {
    $xml = simplexml_load_string($xmlContent);
    if (!$xml || !isset($xml->registrant)) {
        return 'Successfully imported (0) rows';
    }

    $model = new RegistrantModel((new Database())->getConnection());
    $count = 0;

    foreach ($xml->registrant as $registrant) {
        $name = trim((string) $registrant->full_name);
        $email = trim((string) $registrant->email);
        if ($name === '' || $email === '') {
            continue;
        }
        if ($model->addRegistrant(
            $name,
            trim((string) $registrant->age),
            $email,
            trim((string) $registrant->contact_number),
            trim((string) $registrant->preference_allergy),
            trim((string) $registrant->event_id),
            trim((string) $registrant->user_id)
        )) {
            $count++;
        }
    }

    return 'Successfully imported ('. $count .') rows';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['export_events'])) {
        $xml = exportEvents();
        header('Content-Type: application/xml; charset=UTF-8');
        header('Content-Disposition: attachment; filename="events_' . date('Y-m-d_H-i-s') . '.xml"');
        echo $xml->asXML();
        exit;
    }

    if (!empty($_FILES['xml_file']['tmp_name']) && !empty($_POST['action'])) {
        $content = file_get_contents($_FILES['xml_file']['tmp_name']);
        $message = $_POST['action'] === 'import_events'
            ? importEvents($content)
            : importRegistrants($content);

        header('Content-Type: text/plain; charset=UTF-8');
        echo $message;
        exit;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    
    if ($action === 'export_events') {
        $xml = exportEvents();
        header('Content-Type: application/xml; charset=UTF-8');
        header('Content-Disposition: attachment; filename="events_' . date('Y-m-d_H-i-s') . '.xml"');
        echo $xml->asXML();
        exit;
    }
    
    if ($action === 'export_registrants') {
        $xml = exportRegistrants();
        header('Content-Type: application/xml; charset=UTF-8');
        header('Content-Disposition: attachment; filename="registrants_' . date('Y-m-d_H-i-s') . '.xml"');
        echo $xml->asXML();
        exit;
    }
}
?>
