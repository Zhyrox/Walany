const root = document.documentElement;
const themeToggle = document.getElementById("themeToggle") || document.querySelector("[data-theme-toggle]");
const themeIcon = themeToggle ? themeToggle.querySelector("[data-theme-icon], .theme-icon") : null;
const registerNowBtn = document.getElementById("registerNowBtn");
const backToTopBtn = document.getElementById("backToTop");
const registrationSection = document.getElementById("registration");
const eventSearch = document.getElementById("eventSearch");
const eventSearchBtn = document.getElementById("eventSearchBtn");
const eventCards = document.querySelectorAll(".event-card");
const noEventsMessage = document.getElementById("noEventsMessage");
const savedTheme = localStorage.getItem("walania-theme");

if (savedTheme === "dark" || savedTheme === "light") {
    root.setAttribute("data-theme", savedTheme);
    updateThemeButton(savedTheme);
}

if (themeToggle) {
    themeToggle.addEventListener("click", () => {
        const currentTheme = root.getAttribute("data-theme");
        const nextTheme = currentTheme === "dark" ? "light" : "dark";

        root.setAttribute("data-theme", nextTheme);
        localStorage.setItem("walania-theme", nextTheme);
        updateThemeButton(nextTheme);
    });
}

const loginSlides = Array.from(document.querySelectorAll(".login-slide"));
const loginDots = Array.from(document.querySelectorAll(".slide-dots span"));
let loginActiveIndex = 0;

function showLoginSlide(index) {
    loginSlides.forEach((slide, i) => slide.classList.toggle("is-active", i === index));
    loginDots.forEach((dot, i) => dot.classList.toggle("is-active", i === index));
    loginActiveIndex = index;
}

if (loginSlides.length && loginDots.length) {
    setInterval(() => {
        const nextIndex = (loginActiveIndex + 1) % loginSlides.length;
        showLoginSlide(nextIndex);
    }, 4500);
}

function openUpdateModal(id, name, date, location, description) {
    const updateId = document.getElementById("updateId");
    const updateName = document.getElementById("updateName");
    const updateDate = document.getElementById("updateDate");
    const updateLocation = document.getElementById("updateLocation");
    const updateDescription = document.getElementById("updateDescription");
    const updateModal = document.getElementById("updateModal");

    if (!updateId || !updateName || !updateDate || !updateLocation || !updateDescription || !updateModal) {
        return;
    }

    updateId.value = id;
    updateName.value = name;
    updateDate.value = date;
    updateLocation.value = location;
    updateDescription.value = description;
    updateModal.classList.add('active');
}

function populateRegistrantManagerForm(id, fullName, age, email, contactNumber, preferenceAllergy, eventId) {
    const registrantId = document.getElementById("registrantId");
    const fullnameField = document.getElementById("fullname");
    const ageField = document.getElementById("age");
    const emailField = document.getElementById("email");
    const contactNumberField = document.getElementById("contact_number");
    const preferenceField = document.getElementById("preference_allergy");
    const eventSelect = document.getElementById("event_id");
    const updateButton = document.getElementById("updateRegistrantButton");
    const cancelButton = document.getElementById("cancelRegistrantUpdate");

    if (!registrantId || !fullnameField || !ageField || !emailField || !contactNumberField || !preferenceField || !eventSelect || !updateButton || !cancelButton) {
        return;
    }

    registrantId.value = id;
    fullnameField.value = fullName;
    ageField.value = age;
    emailField.value = email;
    contactNumberField.value = contactNumber;
    preferenceField.value = preferenceAllergy;
    eventSelect.value = eventId;
    updateButton.disabled = false;
    cancelButton.hidden = false;
    window.location.hash = '#registrant-manager';
}

function resetRegistrantForm() {
    const registrantForm = document.getElementById("registrantForm");
    const updateButton = document.getElementById("updateRegistrantButton");
    const cancelButton = document.getElementById("cancelRegistrantUpdate");

    if (!registrantForm || !updateButton || !cancelButton) {
        return;
    }

    registrantForm.reset();
    document.getElementById("registrantId").value = "";
    updateButton.disabled = true;
    cancelButton.hidden = true;
}

function closeModal() {
    const updateModal = document.getElementById("updateModal");
    if (!updateModal) return;
    updateModal.classList.remove('active');
}

(function initConfirmPanel() {
    const confirmPanel = document.getElementById('confirmPanel');
    const modalEventId = document.getElementById('modalEventId');
    const deleteEventName = document.getElementById('deleteEventName');
    const cancelBtn = document.getElementById('cancelBtn');

    if (!confirmPanel || !modalEventId || !deleteEventName || !cancelBtn) {
        return;
    }

    document.querySelectorAll('.delete-trigger').forEach(button => {
        button.addEventListener('click', function() {
            const eventId = this.getAttribute('data-id');
            const eventName = this.getAttribute('data-name');

            modalEventId.value = eventId;
            deleteEventName.textContent = eventName;
            confirmPanel.classList.add('active');
            confirmPanel.setAttribute('aria-hidden', 'false');
        });
    });

    cancelBtn.addEventListener('click', function() {
        confirmPanel.classList.remove('active');
        confirmPanel.setAttribute('aria-hidden', 'true');
    });
})();

const passwordToggleButtons = document.querySelectorAll(".toggle-password");
passwordToggleButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
        const targetId = btn.getAttribute("data-target");
        const input = document.getElementById(targetId);
        if (!input) return;

        const showing = input.type === "text";
        input.type = showing ? "password" : "text";
        btn.textContent = showing ? "Show" : "Hide";
        btn.setAttribute("aria-label", showing ? "Show password" : "Hide password");
    });
});

if (registerNowBtn && registrationSection) {
    registerNowBtn.addEventListener("click", () => {
        registrationSection.scrollIntoView({ behavior: "smooth", block: "start" });
    });
}

if (backToTopBtn) {
    backToTopBtn.addEventListener("click", () => {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });

    window.addEventListener("scroll", () => {
        const showButtonAt = document.body.scrollHeight * 0.45;
        backToTopBtn.classList.toggle("is-visible", window.scrollY > showButtonAt);
    });
}

if (eventSearch && eventSearchBtn && noEventsMessage) {
    eventSearchBtn.addEventListener("click", filterEvents);

    eventSearch.addEventListener("keydown", (event) => {
        if (event.key === "Enter") {
            event.preventDefault();
            filterEvents();
        }
    });

    eventSearch.addEventListener("input", () => {
        if (eventSearch.value.trim() === "") {
            filterEvents();
        }
    });
}

function filterEvents() {
    const searchTerm = eventSearch.value.trim().toLowerCase();
    let visibleCount = 0;

    eventCards.forEach((card) => {
        const eventText = card.textContent.toLowerCase();
        const isMatch = searchTerm === "" || eventText.includes(searchTerm);

        card.classList.toggle("is-hidden", !isMatch);

        if (isMatch) {
            visibleCount += 1;
        }
    });

    noEventsMessage.classList.toggle("is-visible", visibleCount === 0);
}

function updateThemeButton(theme) {
    if (!themeToggle) {
        return;
    }

    const isDark = theme === "dark";
    themeToggle.setAttribute("aria-label", isDark ? "Switch to light mode" : "Switch to dark mode");

    if (!themeIcon) {
        return;
    }

    if (themeIcon.tagName === "IMG") {
        themeIcon.src = isDark ? "images/DarkModeIcon.svg" : "images/LightModeIcon.svg";
        return;
    }

    themeIcon.textContent = isDark ? "L" : "D";
}

function startImport(action) {
    const input = document.getElementById('import-file');
    const actionInput = document.getElementById('import-action');
    if (!input || !actionInput) return;
    actionInput.value = action;
    input.value = '';
    input.click();
}

function exportXml(type) {
    const action = type === 'events' ? 'export_events' : 'export_registrants';
    window.location.href = '../controllers/XML.php?action=' + action;
}
