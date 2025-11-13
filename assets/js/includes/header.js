document.addEventListener("DOMContentLoaded", () => {
  // Get current page segment (last part of URI)
  const currentPath = window.location.pathname.split("/").filter(Boolean);
  const currentFile = currentPath[currentPath.length - 1]; // e.g., "login" or "home"
  const currentMode = new URLSearchParams(window.location.search).get("mode");

  const links = document.querySelectorAll(".menu a, .icons a");

  links.forEach(link => {
    // Get the href path segment
    const linkUrl = new URL(link.href, window.location.origin);
    const linkPath = linkUrl.pathname.split("/").filter(Boolean);
    const linkFile = linkPath[linkPath.length - 1]; // e.g., "about" or "login"
    const linkMode = linkUrl.searchParams.get("mode");

    // Highlight link if path matches (ignores folder)
    if (currentFile === linkFile && (!linkMode || currentMode === linkMode)) {
      link.classList.add("active");
    }

    // Special case for login/register
    if (
      link.id === "auth-link" &&
      (currentMode === "login" || currentMode === "register")
    ) {
      link.classList.add("active");
    }
  });
});
