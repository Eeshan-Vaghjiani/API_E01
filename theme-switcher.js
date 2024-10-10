document.addEventListener("DOMContentLoaded", function () {
    const toggleSwitch = document.getElementById("theme-toggle");

    // Check the user's system theme preference
    const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)").matches;
    const currentTheme = localStorage.getItem("theme") || (prefersDarkScheme ? "dark" : "light");
    document.body.classList.add(currentTheme + "-mode");

    // Set the toggle switch based on the current theme
    toggleSwitch.checked = currentTheme === "dark";

    toggleSwitch.addEventListener("change", function () {
        if (this.checked) {
            document.body.classList.remove("light-mode");
            document.body.classList.add("dark-mode");
            localStorage.setItem("theme", "dark");
        } else {
            document.body.classList.remove("dark-mode");
            document.body.classList.add("light-mode");
            localStorage.setItem("theme", "light");
        }
    });
});
