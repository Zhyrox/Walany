<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walania | Event Registration</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
<link rel="icon" type="image/svg+xml" href="images\Walania.svg">
</head>
<body>
    <header class="site-header">
        <a href="#home" class="logo-placeholder" aria-label="Walania home">
            <img src="images/Walania.svg" alt="Walania logo">
        </a>

        <nav class="main-nav" aria-label="Main navigation">
            <a href="#events">Events</a>
            <a href="#registration">Register</a>
            <a href="#contacts">Contacts</a>
        </nav>

        <button class="theme-toggle" id="themeToggle" type="button" aria-label="Switch to dark mode">
            <span class="theme-icon" aria-hidden="true">D</span>
        </button>
    </header>

    <main>
        <section class="hero-section" id="home">
            <div class="hero-orb hero-orb-left"></div>
            <div class="hero-orb hero-orb-right"></div>

            <div class="hero-content">
                <h1>Walania</h1>
                <p>Click. Register. Experience.</p>
                <button class="primary-button" id="registerNowBtn" type="button">Register Now!</button>
            </div>
        </section>

        <section class="events-section" id="events">
            <div class="section-inner">
                <div class="section-heading">
                    <p class="eyebrow">Browse upcoming activities</p>
                    <h2>Events Section</h2>
                    <p>Scroll inside the frame to view more events without making the whole page too long.</p>
                </div>

                <div class="event-tools">
                    <label class="event-search-label" for="eventSearch">Search events</label>
                    <div class="event-search-row">
                        <input id="eventSearch" type="search" name="event_search" placeholder="Search by event name">
                        <button class="secondary-button" id="eventSearchBtn" type="button">Search</button>
                    </div>
                </div>

                <div class="events-frame" tabindex="0" aria-label="Scrollable list of events">
                    <article class="event-card">
                        <span class="event-date">Jun 08</span>
                        <div>
                            <h3>Creative Tech Summit</h3>
                            <p>A beginner-friendly session about design, coding, and digital projects.</p>
                        </div>
                    </article>

                    <article class="event-card">
                        <span class="event-date">Jun 14</span>
                        <div>
                            <h3>Campus Innovation Fair</h3>
                            <p>Meet local teams, explore booths, and register for hands-on showcases.</p>
                        </div>
                    </article>

                    <article class="event-card">
                        <span class="event-date">Jun 22</span>
                        <div>
                            <h3>Leadership Workshop</h3>
                            <p>Practical activities for communication, planning, and event coordination.</p>
                        </div>
                    </article>

                    <article class="event-card">
                        <span class="event-date">Jul 03</span>
                        <div>
                            <h3>Community Outreach Day</h3>
                            <p>A volunteer event with team assignments, orientation, and field activities.</p>
                        </div>
                    </article>

                    <article class="event-card">
                        <span class="event-date">Jul 12</span>
                        <div>
                            <h3>Student Mixer Night</h3>
                            <p>An open social event for participants, organizers, and invited guests.</p>
                        </div>
                    </article>

                    <p class="no-events-message" id="noEventsMessage">No events found. Try another search term.</p>
                </div>
            </div>
        </section>

        <section class="registration-section" id="registration">
            <div class="section-inner registration-layout">
                <div class="section-heading">
                    <p class="eyebrow">Event Registration System</p>
                    <h2>Event Registration</h2>
                    <p>Collect participant details here and connect the form action to your PHP MVC controller.</p>
                </div>

                <form class="registration-form" action="" method="POST">
                    <h3>Add Participant</h3>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="fullName">Full Name</label>
                            <input id="fullName" name="full_name" type="text" autocomplete="name" required>
                        </div>

                        <div class="form-group">
                            <label for="age">Age</label>
                            <input id="age" name="age" type="number" min="1" max="120" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" autocomplete="email" required>
                        </div>

                        <div class="form-group">
                            <label for="contactNumber">Contact Number</label>
                            <input id="contactNumber" name="contact_number" type="tel" autocomplete="tel" required>
                        </div>

                        <div class="form-group form-group-wide">
                            <label for="eventName">Event Name</label>
                            <select id="eventName" name="event_name" required>
                                <option value="">-- Select Event --</option>
                                <option value="Creative Tech Summit">Creative Tech Summit</option>
                                <option value="Campus Innovation Fair">Campus Innovation Fair</option>
                                <option value="Leadership Workshop">Leadership Workshop</option>
                                <option value="Community Outreach Day">Community Outreach Day</option>
                                <option value="Student Mixer Night">Student Mixer Night</option>
                            </select>
                        </div>

                        <div class="form-group form-group-wide">
                            <label for="preferenceAllergy">Preference/Allergy</label>
                            <input id="preferenceAllergy" name="preference_allergy" type="text" placeholder="Food preference, accessibility needs, or allergies">
                        </div>
                    </div>

                    <button class="primary-button submit-button" type="submit">Submit</button>
                </form>
            </div>
        </section>

        <section class="contacts-section" id="contacts">
            <div class="section-inner contact-layout">
                <div class="contact-image-placeholder">
                    <span>Image Placeholder</span>
                </div>

                <div class="contact-copy">
                    <p class="eyebrow">Need help?</p>
                    <h2>Contacts</h2>
                    <p>Email: support@walania.test</p>
                    <p>Phone: +63 900 000 0000</p>
                    <p>Office: Student Activities Center, Main Campus</p>
                </div>
            </div>
        </section>
    </main>

    <button class="back-to-top" id="backToTop" type="button" aria-label="Scroll back to top">Top</button>

    <script src="script.js"></script>
</body>
</html>
