const root = document.documentElement;
const themeToggle = document.getElementById("themeToggle");
const themeIcon = themeToggle.querySelector(".theme-icon");
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

themeToggle.addEventListener("click", () => {
    const currentTheme = root.getAttribute("data-theme");
    const nextTheme = currentTheme === "dark" ? "light" : "dark";

    root.setAttribute("data-theme", nextTheme);
    localStorage.setItem("walania-theme", nextTheme);
    updateThemeButton(nextTheme);
});

registerNowBtn.addEventListener("click", () => {
    registrationSection.scrollIntoView({ behavior: "smooth", block: "start" });
});

backToTopBtn.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
});

window.addEventListener("scroll", () => {
    const showButtonAt = document.body.scrollHeight * 0.45;
    backToTopBtn.classList.toggle("is-visible", window.scrollY > showButtonAt);
});



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
    const isDark = theme === "dark";

    themeIcon.textContent = isDark ? "L" : "D";
    themeToggle.setAttribute("aria-label", isDark ? "Switch to light mode" : "Switch to dark mode");
}
