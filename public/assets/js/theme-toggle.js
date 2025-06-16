document.addEventListener("DOMContentLoaded", function () {
  // Dark mode functionality
  const htmlElement = document.documentElement;
  const switchElement = document.getElementById("darkModeSwitch");
  const currentTheme = htmlElement.getAttribute("data-bs-theme");
  switchElement.checked = currentTheme === "dark";

  switchElement.addEventListener("change", function () {
    if (this.checked) {
      htmlElement.setAttribute("data-bs-theme", "dark");
      localStorage.setItem("bsTheme", "dark");
    } else {
      htmlElement.setAttribute("data-bs-theme", "light");
      localStorage.setItem("bsTheme", "light");
    }
  });
});
