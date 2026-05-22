CREATE DATABASE IF NOT EXISTS walania;

USE walania;

CREATE TABLE IF NOT EXISTS events (
    event_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_date_label VARCHAR(20) NOT NULL,
    event_name VARCHAR(120) NOT NULL UNIQUE,
    event_description VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO events (event_date_label, event_name, event_description)
VALUES
    ('Jun 08', 'Creative Tech Summit', 'A beginner-friendly session about design, coding, and digital projects.'),
    ('Jun 14', 'Campus Innovation Fair', 'Meet local teams, explore booths, and register for hands-on showcases.'),
    ('Jun 22', 'Leadership Workshop', 'Practical activities for communication, planning, and event coordination.'),
    ('Jul 03', 'Community Outreach Day', 'A volunteer event with team assignments, orientation, and field activities.'),
    ('Jul 12', 'Student Mixer Night', 'An open social event for participants, organizers, and invited guests.')
ON DUPLICATE KEY UPDATE
    event_date_label = VALUES(event_date_label),
    event_description = VALUES(event_description);
