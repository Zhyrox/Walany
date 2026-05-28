<?php
require_once __DIR__ . "../models/Database.php";
require_once __DIR__ . "../models/eventModel.php";

$database = new Database();
$dbConnection = $database->getConnection();
$eventModel = new EventModel($dbConnection);
$events = $eventModel->getAllEvents();

header('Content-Type: application/xml; charset=UTF-8');
header('Content-Disposition: attachment; filename="events.xml"');

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><events></events>');
foreach ($events as $event) {
    $eventNode = $xml->addChild('event');
    $eventNode->addChild('id', $event['id']);
    $eventNode->addChild('name', htmlspecialchars($event['name'], ENT_XML1 | ENT_COMPAT, 'UTF-8'));
    $eventNode->addChild('event_date', $event['event_date']);
    $eventNode->addChild('location', htmlspecialchars($event['location'], ENT_XML1 | ENT_COMPAT, 'UTF-8'));
    $eventNode->addChild('description', htmlspecialchars($event['description'], ENT_XML1 | ENT_COMPAT, 'UTF-8'));
}

echo $xml->asXML();
