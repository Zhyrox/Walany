<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Events - Walania</title>
    <link rel="icon" type="image/x-icon" href="/Walany/assets/images/Walania.svg">
    <link rel="stylesheet" href="/Walany/assets/style.css">
    <style>
        /* Styles for disabled buttons */
        .btn.disabled,
        .btn[disabled] {
            opacity: 0.55;
            cursor: not-allowed;
            pointer-events: none;
            filter: grayscale(40%);
        }

        /* Category Filter Bar */
        .category-filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
        }

        .category-btn {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 20px;
            padding: 6px 16px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #333;
        }

        .category-btn:hover,
        .category-btn.active {
            background-color: #0056b3;
            color: #ffffff;
            border-color: #0056b3;
        }

        /* Header Tags on Event Cards */
        .event-card-header-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 10px;
            margin-bottom: 8px;
        }

        .category-tag {
            display: inline-block;
            background-color: #e9ecef;
            color: #495057;
            font-size: 12px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 12px;
            text-transform: capitalize;
        }

        /* Capacity Badges */
        .capacity-tag {
            display: inline-block;
            font-size: 12px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 12px;
        }

        .capacity-available {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .capacity-full {
            background-color: #ffebee;
            color: #c62828;
        }

        /* Price Formatting Badges */
        .price-free {
            color: #2e7d32;
            font-weight: bold;
        }

        .price-paid {
            color: #0056b3;
            font-weight: bold;
        }
    </style>
</head>
<body class="home-events-page">

    <div class="connection-warning" style="background-color: #ffcccc; color: #cc0000; border: 2px solid #cc0000; padding: 20px; font-size: 24px; font-family: sans-serif; font-weight: bold; text-align: center; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 100; width: 90%; max-width: 600px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        If you are seeing this, paki ayos nung href nung scripts, assets, and stylesheet files. For easy fix move your project folder inside the htdocs
    </div>

    <header class="site-header login-header headbar">
        <a href="/Walany/index.php?module=Home" class="logo-placeholder" aria-label="Walania home">
            <img src="/Walany/assets/images/Walania.svg" alt="Walania logo">
        </a>
        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle dark mode">
            <img class="theme-toggle-icon" data-theme-icon src="/Walany/assets/images/LightModeIcon.svg" alt="" aria-hidden="true">
        </button>
    </header>

    <main class="events-container">
        <section class="events-search-panel" aria-label="Search campus events">
            <label for="eventSearch">Search events</label>
            <div class="events-search-control">
                <input type="search" id="eventSearch" placeholder="Search by event title..." autocomplete="off">
            </div>
        </section>

        <!-- Category Filter Pills -->
        <div class="category-filter-bar" aria-label="Filter events by category">
            <button type="button" class="category-btn active" data-category="all">All</button>
            <button type="button" class="category-btn" data-category="Seminar">Seminar</button>
            <button type="button" class="category-btn" data-category="Workshop">Workshop</button>
            <button type="button" class="category-btn" data-category="Tournament">Tournament</button>
            <button type="button" class="category-btn" data-category="Tryouts">Tryouts</button>
            <button type="button" class="category-btn" data-category="Intramurals">Intramurals</button>
            <button type="button" class="category-btn" data-category="Exhibitions">Exhibitions</button>
            <button type="button" class="category-btn" data-category="Fundraisers">Fundraisers</button>
            <button type="button" class="category-btn" data-category="Orientations">Orientations</button>
            <button type="button" class="category-btn" data-category="Webinars">Webinars</button>
        </div>

        <h2>Available Campus Events</h2>

        <?php if (empty($events)): ?>
            <p class="events-empty">No active events scheduled at this time. Check back later!</p>
        <?php else: ?>
            <p id="noEventsMessage" class="events-empty events-no-results" style="display: none;">No events match your selected filters.</p>
            <div class="events-grid">
                <?php 
                $currentDate = date('Y-m-d');
                foreach ($events as $event): 
                    $eventName = $event['name'] ?? 'Campus Event';
                    $eventCategory = $event['category'] ?? 'Seminar';
                    $eventDate = $event['event_date'] ?? '';
                    $rawPrice = (float)($event['price'] ?? 0);
                    
                    // Capacity Logic
                    $maxCapacity = isset($event['max_capacity']) ? (int)$event['max_capacity'] : 0;
                    $currentRegistrations = (int)($event['current_registrations'] ?? 0);
                    $isFull = ($maxCapacity > 0) && ($currentRegistrations >= $maxCapacity);
                    
                    // Lockdown Logic States
                    $isPastEvent = !empty($eventDate) && ($eventDate < $currentDate);
                    $isOpenRegistration = !empty($event['open_registration']);
                    
                    // Registration & Feedback condition checks
                    $canRegister = $isOpenRegistration && !$isPastEvent && !$isFull;
                    $canFeedback = $isPastEvent;

                    $eventImage = !empty($event['thumbnail'])
                        ? $event['thumbnail']
                        : '/Walany/assets/images/Event_Image%20(1).jpg';
                ?>

                    <article class="event-card" 
                             data-event-title="<?= htmlspecialchars(strtolower($eventName)) ?>" 
                             data-category="<?= htmlspecialchars(strtolower($eventCategory)) ?>">
                        
                        <div class="event-card-image">
                            <img src="<?= htmlspecialchars($eventImage) ?>" alt="<?= htmlspecialchars($eventName) ?> event image">
                        </div>
                        
                        <!-- Header Tag Pills -->
                        <div class="event-card-header-tags">
                            <!-- Status Tag -->
                            <span class="event-card-badge">
                                <?= $isPastEvent ? 'Ended' : 'Upcoming' ?>
                            </span>

                            <!-- Category Tag -->
                            <span class="category-tag">
                                <?= htmlspecialchars($eventCategory) ?>
                            </span>

                            <!-- Capacity Tag -->
                            <?php if ($maxCapacity > 0): ?>
                                <?php if ($isFull): ?>
                                    <span class="capacity-tag capacity-full">At Capacity</span>
                                <?php else: ?>
                                    <span class="capacity-tag capacity-available">
                                        <?= ($maxCapacity - $currentRegistrations) ?> / <?= $maxCapacity ?> Spots Left
                                    </span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="capacity-tag capacity-available">Open Spots</span>
                            <?php endif; ?>
                        </div>

                        <h3><?= htmlspecialchars($eventName) ?></h3>
                        <p class="card-price">
                            <strong>Price:</strong> 
                            <?php if ($rawPrice <= 0): ?>
                                <span class="price-free">Free Admission</span>
                            <?php else: ?>
                                <span class="price-paid">₱<?= number_format($rawPrice, 2) ?></span>
                            <?php endif; ?>
                        </p>
                        <p class="card-date"><strong>Date:</strong> <?= htmlspecialchars($eventDate) ?></p>
                        <p class="card-location"><strong>Location:</strong> <?= htmlspecialchars($event['location'] ?? '') ?></p>
                        <p class="card-description"><?= htmlspecialchars($event['description'] ?? '') ?></p>

                        <div class="actions">
                            <!-- Registration Lockdown Button -->
                            <?php if ($canRegister): ?>
                                <a href="/Walany/modules/Registrants/Views/registration-form.php?event_id=<?= htmlspecialchars((string) ($event['id'] ?? 0)) ?>" 
                                   class="btn btn-primary">
                                    Register Now
                                </a>
                            <?php else: ?>
                                <?php
                                    $disabledReason = 'Registration Closed';
                                    $tooltip = 'Registration is currently closed by the event planner.';
                                    if ($isPastEvent) {
                                        $disabledReason = 'Event Ended';
                                        $tooltip = 'Registration is closed because this event has ended.';
                                    } elseif ($isFull) {
                                        $disabledReason = 'At Capacity';
                                        $tooltip = 'Registration is closed because the event has reached its maximum capacity.';
                                    }
                                ?>
                                <button type="button" class="btn btn-primary disabled" disabled title="<?= $tooltip ?>">
                                    <?= $disabledReason ?>
                                </button>
                            <?php endif; ?>

                            <!-- Feedback Lockdown Button -->
                            <?php if ($canFeedback): ?>
                                <a href="/Walany/index.php?module=Events&action=evaluate&event_id=<?= htmlspecialchars((string) ($event['id'] ?? 0)) ?>" 
                                class="btn btn-secondary">
                                    Give Feedback
                                </a>
                            <?php else: ?>
                                <button type="button" class="btn btn-secondary disabled" disabled 
                                        title="Feedback will open after the event takes place.">
                                    Feedback Locked
                                </button>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <script src="/Walany/assets/script.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('eventSearch');
        const categoryButtons = document.querySelectorAll('.category-btn');
        const eventCards = document.querySelectorAll('.event-card');
        const noEventsMsg = document.getElementById('noEventsMessage');

        let currentCategory = 'all';

        function filterEvents() {
            const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
            let visibleCount = 0;

            eventCards.forEach(card => {
                const title = card.getAttribute('data-event-title') || '';
                const category = card.getAttribute('data-category') || '';

                const matchesSearch = title.includes(searchTerm);
                const matchesCategory = (currentCategory === 'all') || (category === currentCategory.toLowerCase());

                if (matchesSearch && matchesCategory) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (noEventsMsg) {
                noEventsMsg.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', filterEvents);
        }

        categoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                categoryButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                currentCategory = this.getAttribute('data-category');
                filterEvents();
            });
        });

        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.altKey && event.key.toLowerCase() === 'l') {
                event.preventDefault();
                window.location.href = '/Walany/index.php?module=Auth&action=login';
            }
        });
    });
    </script>
</body>
</html>