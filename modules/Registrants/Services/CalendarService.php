<?php

class CalendarService 
{
    /**
     * Generates a structural Google Calendar template injection URL.
     *
     * @param array $eventData Contains 'name', 'start_date', 'end_date', 'description', 'location'
     * @param string $referenceNumber Unique ticket identifier for the user
     * @return string Fully structured URL string
     */
    public static function generateGoogleCalendarUrl(array $eventData, string $referenceNumber): string
    {
        $baseUrl = "https://calendar.google.com/calendar/render";
        
        // Format dates to Google's required ISO format: YYYYMMDDTHHMMSSZ
        // If your database only has a date, we default it to an all-day or standard timezone format
        $startTimestamp = strtotime($eventData['event_date'] ?? 'now');
        $endTimestamp = $startTimestamp + 10800; // Default event length to 3 hours layout buffer
        
        $datesParam = date('Ymd\THis', $startTimestamp) . '/' . date('Ymd\THis', $endTimestamp);
        
        // Build clear, informative body text for the user's calendar event note details
        $details = "Thank you for registering! \n\n";
        $details .= "Your Ticket Reference ID: " . $referenceNumber . "\n";
        $details .= "Please have your QR code or Reference ID ready at the venue check-in gate. \n\n";
        $details .= $eventData['description'] ?? '';

        $params = [
            'action'   => 'TEMPLATE',
            'text'     => $eventData['name'] ?? 'Walania Event',
            'dates'    => $datesParam,
            'details'  => $details,
            'location' => $eventData['location'] ?? 'Walania Designated Venue',
            'sf'       => 'true',
            'output'   => 'xml'
        ];

        return $baseUrl . '?' . http_build_query($params);
    }
}