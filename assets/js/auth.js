document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);
  const mode = params.get("mode");

  // Map mode to CI controller method (without .php)
  let pageToLoad = "";
  if (mode === "login") {
    pageToLoad = "login"; // Auth controller method
  } else if (mode === "register") {
    pageToLoad = "register";
  }

  if (pageToLoad) {
    fetch(`${BASE_URL}auth/${pageToLoad}`)
      .then(response => response.text())
      .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, "text/html");

        // Grab the section from fetched HTML
        const section =
          doc.querySelector(".login-section") ||
          doc.querySelector(".register-section");

        if (section) {
          const container = document.getElementById("auth-container");
          container.innerHTML = ""; // Clear previous content
          container.appendChild(section);

          // --- Login form behavior ---
          const loginForm = document.getElementById("loginForm");
          if (loginForm) {
            loginForm.addEventListener("submit", function (e) {
              e.preventDefault();
              const email = document.getElementById("email").value.trim();
              const password = document.getElementById("password").value.trim();

              // Admin check
              if (email === "admin@gmail.com" && password === "admin") {
                localStorage.setItem("isLoggedIn", "true");
                localStorage.setItem("role", "admin");

                // Admin dashboard (in progress)
                window.location.href = `${BASE_URL}html_admin/admin_dashboard.html`;
              } else {
                // Regular user
                localStorage.setItem("isLoggedIn", "true");
                localStorage.setItem("role", "user");

                // User home page
                window.location.href = `${BASE_URL}pages/home-login.php`;
              }
            });
          }

          // --- Register form behavior ---
          const registerForm = document.getElementById("registerForm");
          if (registerForm) {
            registerForm.addEventListener("submit", function (e) {
              e.preventDefault();
              localStorage.setItem("isLoggedIn", "true");
              localStorage.setItem("role", "user");

              // After registration, go to user home
              window.location.href = `${BASE_URL}pages/home-login.php`;
            });
          }
        }
      })
      .catch(err => console.error("Error loading auth page:", err));
  }
});
