// Dark / Light mode
const toggleBtn = document.getElementById("darkModeToggle");
const prefersLight = window.matchMedia("(prefers-color-scheme: light)").matches;
if (prefersLight) document.body.classList.add("light");

toggleBtn?.addEventListener("click", () => {
  document.body.classList.toggle("light");
  toggleBtn.innerHTML = document.body.classList.contains("light")
    ? '<i class="bi bi-brightness-high"></i> <span class="d-none d-sm-inline">Light</span>'
    : '<i class="bi bi-moon-stars"></i> <span class="d-none d-sm-inline">Dark</span>';
});

// Date & time ticker
function tick() {
  const el = document.getElementById("datetime");
  if (el) el.textContent = new Date().toLocaleString();
}
tick();
setInterval(tick, 1000);
document.getElementById("year").textContent = new Date().getFullYear();

// Contact form -> send email via PHP (contact.php)
const form = document.getElementById("contactForm");
const alertBox = document.getElementById("formAlert");

form?.addEventListener("submit", async (e) => {
  e.preventDefault();
  alertBox.classList.add("d-none");

  const formData = new FormData(form);
  try {
    const res = await fetch("contact.php", {
      method: "POST",
      body: formData
    });
    const data = await res.json();

    alertBox.className = "alert mt-3";
    if (data.success) {
      alertBox.classList.add("alert-success");
      alertBox.textContent = "Sweet! Your message has been sent. We'll reply shortly.";
      form.reset();
    } else {
      alertBox.classList.add("alert-danger");
      alertBox.textContent = data.error || "Hmm, something went wrong. Please try again.";
    }
    alertBox.classList.remove("d-none");
  } catch (err) {
    alertBox.className = "alert mt-3 alert-danger";
    alertBox.textContent = "Network error. Please check your connection and try again.";
    alertBox.classList.remove("d-none");
  }
});
