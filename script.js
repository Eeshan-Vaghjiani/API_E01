// scripts.js

document.addEventListener("DOMContentLoaded", function() {
    const toggleButton = document.getElementById("toggle-theme");
    const body = document.body;
    const icon = document.getElementById("toggle-icon");

    // Check for saved user preference
    const currentTheme = localStorage.getItem("theme");
    if (currentTheme) {
        body.classList.toggle("dark-mode", currentTheme === "dark");
        icon.classList.toggle("fa-sun", currentTheme === "dark");
        icon.classList.toggle("fa-moon", currentTheme !== "dark");
    }

    // Add click event to toggle button
    toggleButton.addEventListener("click", function() {
        body.classList.toggle("dark-mode");
        const isDarkMode = body.classList.contains("dark-mode");

        // Change icon
        icon.classList.toggle("fa-sun", isDarkMode);
        icon.classList.toggle("fa-moon", !isDarkMode);

        // Save user preference
        localStorage.setItem("theme", isDarkMode ? "dark" : "light");
    });
});
