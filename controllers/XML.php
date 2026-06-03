<?php
require_once __DIR__ . "/../models/Database.php";
require_once __DIR__ . "/../models/eventModel.php";
require_once __DIR__ . "/../models/registrantModel.php";

/*Exports events from walania into an XML*/
function exportEvents() {
    $database = new Database();
    $dbConnection = $database->getConnection();
    $eventModel = new EventModel($dbConnection);
    $events = $eventModel->getAllEvents();

    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = true;
    $root = $dom->createElement('events');
    $dom->appendChild($root);

    foreach ($events as $event) {
        $eventNode = $dom->createElement('event');
        $eventNode->appendChild($dom->createElement('id', $event['id']));
        $eventNode->appendChild($dom->createElement('name', $event['name']));
        $eventNode->appendChild($dom->createElement('event_date', $event['event_date']));
        $eventNode->appendChild($dom->createElement('location', $event['location']));
        $eventNode->appendChild($dom->createElement('description', $event['description']));
        $root->appendChild($eventNode);
    }

    return $dom;
}

/*Exports registrants from walania into an XML*/
function exportRegistrants() {
    $database = new Database();
    $dbConnection = $database->getConnection();
    $registrantModel = new RegistrantModel($dbConnection);
    $registrants = $registrantModel->getAllRegistrants();

    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = true;
    $root = $dom->createElement('registrants');
    $dom->appendChild($root);

    foreach ($registrants as $registrant) {
        $regNode = $dom->createElement('registrant');
        $regNode->appendChild($dom->createElement('id', $registrant['id']));
        $regNode->appendChild($dom->createElement('full_name', $registrant['full_name']));
        $regNode->appendChild($dom->createElement('age', $registrant['age']));
        $regNode->appendChild($dom->createElement('email', $registrant['email']));
        $regNode->appendChild($dom->createElement('contact_number', $registrant['contact_number']));
        $regNode->appendChild($dom->createElement('preference_allergy', $registrant['preference_allergy'] ?? ''));
        $regNode->appendChild($dom->createElement('event_id', $registrant['event_id'] ?? ''));
        $regNode->appendChild($dom->createElement('user_id', $registrant['user_id'] ?? ''));
        $regNode->appendChild($dom->createElement('registered_at', $registrant['registered_at']));
        $root->appendChild($regNode);
    }

    return $dom;
}

function importEvents($xmlContent) {
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    if (!$dom->loadXML($xmlContent)) {
        libxml_clear_errors();
        return 'Successfully imported (0) rows';
    }
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);
    $eventNodes = $xpath->query('/events/event');
    if ($eventNodes === false || $eventNodes->length === 0) {
        return 'Successfully imported (0) rows';
    }

    $model = new EventModel((new Database())->getConnection());
    $count = 0;

    foreach ($eventNodes as $eventNode) {
        $name = trim($xpath->evaluate('string(name)', $eventNode));
        $date = trim($xpath->evaluate('string(event_date)', $eventNode));
        if ($name === '' || $date === '') {
            continue;
        }
        if ($model->addEvent(
            $name,
            $date,
            trim($xpath->evaluate('string(location)', $eventNode)),
            trim($xpath->evaluate('string(description)', $eventNode))
        )) {
            $count++;
        }
    }

    return 'Successfully imported ('. $count .') rows';
}

function importRegistrants($xmlContent) {
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    if (!$dom->loadXML($xmlContent)) {
        libxml_clear_errors();
        return 'Successfully imported (0) rows';
    }
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);
    $registrantNodes = $xpath->query('/registrants/registrant');
    if ($registrantNodes === false || $registrantNodes->length === 0) {
        return 'Successfully imported (0) rows';
    }

    $model = new RegistrantModel((new Database())->getConnection());
    $count = 0;

    foreach ($registrantNodes as $registrantNode) {
        $name = trim($xpath->evaluate('string(full_name)', $registrantNode));
        $email = trim($xpath->evaluate('string(email)', $registrantNode));
        if ($name === '' || $email === '') {
            continue;
        }
        if ($model->addRegistrant(
            $name,
            trim($xpath->evaluate('string(age)', $registrantNode)),
            $email,
            trim($xpath->evaluate('string(contact_number)', $registrantNode)),
            trim($xpath->evaluate('string(preference_allergy)', $registrantNode)),
            trim($xpath->evaluate('string(event_id)', $registrantNode)),
            trim($xpath->evaluate('string(user_id)', $registrantNode))
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
        echo $xml->saveXML();
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
        echo $xml->saveXML();
        exit;
    }
    
    if ($action === 'export_registrants') {
        $xml = exportRegistrants();
        header('Content-Type: application/xml; charset=UTF-8');
        header('Content-Disposition: attachment; filename="registrants_' . date('Y-m-d_H-i-s') . '.xml"');
        echo $xml->saveXML();
        exit;
    }
}
?>
