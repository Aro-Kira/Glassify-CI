document.addEventListener("DOMContentLoaded", () => {
  const currentPath = window.location.pathname.split("/").filter(Boolean);
  const currentFile = currentPath[currentPath.length - 1]; // e.g., "login" or "home"
  const currentRedirect = new URLSearchParams(window.location.search).get("redirect");

  const links = document.querySelectorAll(".menu a, .icons a");

  links.forEach(link => {
    const linkUrl = new URL(link.href, window.location.origin);
    const linkPath = linkUrl.pathname.split("/").filter(Boolean);
    const linkFile = linkPath[linkPath.length - 1]; // e.g., "about" or "login"
    const linkRedirect = linkUrl.searchParams.get("redirect");

    // Reset all links
    link.classList.remove("active");

    // Normal active highlighting
    if (currentFile === linkFile && (!linkRedirect || currentRedirect === linkRedirect)) {
      link.classList.add("active");
    }

    // Special case: if current page is login/register, only highlight login/register link
    if (link.id === "auth-link") {
      if (currentFile === "login" || currentFile === "register") {
        link.classList.add("active");
      } else {
        link.classList.remove("active");
      }
    }

    // For icon links: only remove active if they redirect to login (not on actual page)
    if (link.classList.contains("icon-link")) {
      // If link has redirect param, it means user is not logged in and it goes to login
      // Only highlight if we're actually on the target page (no redirect param in link)
      if (linkRedirect) {
        link.classList.remove("active");
      }
    }
  });

  // Handle profile dropdown button highlighting
  const profileBtn = document.querySelector('.header-dropbtn');
  if (profileBtn) {
    const currentLower = currentFile ? currentFile.toLowerCase() : '';
    if (currentLower === 'profile') {
      profileBtn.classList.add('active');
    }
  }

});
