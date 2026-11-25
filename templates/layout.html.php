<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="A curated minimalist event bulletin board showcasing university exhibitions, technical symposia, and cultural gatherings.">
  <title><?= isset($title) ? htmlspecialchars($title) : 'Home' ?></title>
  
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <!-- accessibility skip link (WCAG AA) -->
  <a href="#mainContent" class="skip-link">Skip to main content</a>

  <header class="site-header">
    <div class="container header-inner">
      <a href="index.php" class="brand-logo" aria-label="Greenwich Event Bulletin Home">
        <img src="assets/logo.webp" alt="Greenwich Logo" class="brand-logo-img">
      </a>

      <?php
        $currentPage = basename($_SERVER['PHP_SELF']);
      ?>

      <nav class="main-nav" id="mainNav" aria-label="Primary Navigation">
        <ul>
          <li><a href="index.php" class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>">Home</a></li>
          <li><a href="events.php" class="nav-link <?= in_array($currentPage, ['events.php', 'event.php']) ? 'active' : '' ?>">Events</a></li>
          <li><a href="organisers_venues.php" class="nav-link <?= $currentPage === 'organisers_venues.php' ? 'active' : '' ?>">Organisers & Venues</a></li>
          <li><a href="manage.php" class="nav-link <?= in_array($currentPage, ['manage.php', 'addevent.php', 'editevent.php']) ? 'active' : '' ?>">Manage Events</a></li>
          <li><a href="contact.php" class="nav-link <?= $currentPage === 'contact.php' ? 'active' : '' ?>">Contact</a></li>
          <li>
            <a href="addevent.php" class="btn-nav-action">
              <span>+ Add Event</span>
            </a>
          </li>
        </ul>
      </nav>

      <button class="mobile-toggle" id="mobileNavToggle" aria-label="Toggle Navigation Menu" aria-expanded="false" aria-controls="mainNav">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="3" y1="12" x2="21" y2="12"></line>
          <line x1="3" y1="6" x2="21" y2="6"></line>
          <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
      </button>
    </div>
  </header>

  <!-- template output buffer injection (week 5 lab pattern) -->
  <main id="mainContent">
    <?= $output ?>
  </main>

  <footer class="site-footer">
    <div class="container">
      <div class="footer-top">
        <div class="footer-brand">
          <a href="index.php" style="display: inline-block; margin-bottom: 0.75rem;">
            <img src="assets/logofooter.webp" alt="University of Greenwich" class="footer-logo-img">
          </a>
          <p>
            Coursework COMP1841 - Web Programming 1: A curated bulletin platform for discovering, organizing, and managing academic, technology, and cultural events across university campuses in Viet Nam.
          </p>
        </div>

        <div class="footer-col">
          <h4>Quick links</h4>
          <ul class="footer-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="events.php">Events</a></li>
            <li><a href="organisers_venues.php">Organisers & Venues Directory</a></li>
            <li><a href="manage.php">Manage Events</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="addevent.php">Add Event</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h4>Coursework information</h4>
          <ul class="footer-links">
            <li><span>COMP1841 Web Programming 1</span></li>
            <li><span>University of Greenwich</span></li>
            <li><span>Full name: Nguyen Thanh Cong</span></li>
            <li><span>Student ID: 001514371</span></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <p>Final Coursework</p>
        <p>Developed by Nguyen Thanh Cong</p>
      </div>
    </div>
  </footer>

  <script src="js/bootstrap.bundle.js"></script>
  <script src="js/scripts.js"></script>
</body>
</html>
