<section class="hero-minimal">
  <div class="container">
    <span class="hero-lead-label">Coursework COMP1841 - Web Programming 1</span>
    <h1 class="hero-title">Distinctive events, curated spaces, and cultural gatherings.</h1>
    <p class="hero-subtitle">
      Explore academic symposiums, university hackathons, student design exhibitions, and cultural gatherings across campus venues in Viet Nam.
    </p>

    <div class="hero-actions">
      <a href="events.php" class="btn btn-primary">
        Explore All Events
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="5" y1="12" x2="19" y2="12"></line>
          <polyline points="12 5 19 12 12 19"></polyline>
        </svg>
      </a>
      <a href="addevent.php" class="btn btn-outline">+ Post a New Event</a>
      <a href="organisers_venues.php" class="btn btn-outline">Browse Directory</a>
    </div>

    <div class="hero-stats-bar" aria-label="System Statistics">
      <div class="stat-item">
        <span class="stat-value"><?= e($stats['events_count']) ?></span>
        <span class="stat-label">Upcoming Events</span>
      </div>
      <div class="stat-item">
        <span class="stat-value"><?= e($stats['organisers_count']) ?></span>
        <span class="stat-label">Verified Organisers</span>
      </div>
      <div class="stat-item">
        <span class="stat-value"><?= e($stats['venues_count']) ?></span>
        <span class="stat-label">Partner Venues</span>
      </div>
      <div class="stat-item">
        <span class="stat-value"><?= count($categories) ?></span>
        <span class="stat-label">Event Categories</span>
      </div>
    </div>
  </div>
</section>

<section style="padding: 4.5rem 0;">
  <div class="container">
    <div class="section-header">
      <div>
        <h2 class="section-title">Upcoming Highlights</h2>
        <p class="section-desc">Hand-picked gatherings and conferences scheduled for the upcoming weeks.</p>
      </div>
      <a href="events.php" class="btn btn-outline btn-sm">
        View Entire Schedule &rarr;
      </a>
    </div>

    <div style="margin-bottom: 2.5rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
      <span style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted); margin-right: 0.5rem;">Explore by Category:</span>
      <a href="events.php" class="pill-link active">All Categories</a>
      <?php foreach ($categories as $cat): ?>
        <a href="events.php?category=<?= urlencode($cat) ?>" class="pill-link">
          <?= e($cat) ?>
        </a>
      <?php endforeach; ?>
    </div>

    <div class="events-grid">
      <?php if (!empty($featuredEvents)): ?>
        <?php foreach ($featuredEvents as $ev): ?>
          <?php $dateParts = formatDayAndMonth($ev['event_date']); ?>
          <article class="event-card">
            <div class="event-card-media">
              <img src="<?= e($ev['image_url']) ?>" alt="<?= e($ev['title']) ?>" class="event-card-image" loading="lazy">
              
              <div class="event-date-badge">
                <span class="date-month"><?= e($dateParts['month']) ?></span>
                <span class="date-day"><?= e($dateParts['day']) ?></span>
              </div>
            </div>

            <div class="event-card-body">
              <div class="event-meta-top">
                <span class="badge"><?= e($ev['category']) ?></span>
                <span class="badge <?= $ev['ticket_price'] <= 0 ? 'badge-free' : '' ?>">
                  <?= formatPrice($ev['ticket_price']) ?>
                </span>
              </div>

              <h3 class="event-card-title">
                <a href="event.php?id=<?= e($ev['id']) ?>">
                  <?= e($ev['title']) ?>
                </a>
              </h3>

              <p class="event-excerpt">
                <?= e($ev['description']) ?>
              </p>

              <div class="event-info-rows">
                <div class="info-row">
                  <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                  </svg>
                  <span><strong><?= e($ev['venue_name']) ?></strong> (<?= e($ev['venue_city']) ?>)</span>
                </div>
                <div class="info-row">
                  <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                  </svg>
                  <span>Host: <?= e($ev['organiser_name']) ?></span>
                </div>
                <div class="info-row">
                  <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                  </svg>
                  <span><?= formatTime($ev['start_time']) ?> – <?= formatTime($ev['end_time']) ?></span>
                </div>
              </div>
            </div>

            <div class="event-card-footer">
              <span style="font-size: 0.8rem; color: var(--text-muted); font-family: var(--font-mono);">
                Ref #<?= e($ev['id']) ?>
              </span>
              <a href="event.php?id=<?= e($ev['id']) ?>" class="btn btn-primary btn-sm">
                View Details &rarr;
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="grid-column: 1 / -1; color: var(--text-muted); padding: 2rem; text-align: center; background: #fff; border: 1px solid var(--border-light); border-radius: var(--radius-md);">
          No upcoming events available at the moment.
        </p>
      <?php endif; ?>
    </div>

    <div style="background-color: var(--bg-surface); border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 2.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem;">
      <div>
        <h3 style="font-family: var(--font-heading); font-size: 1.35rem; margin-bottom: 0.4rem;">Are you an event organiser or venue curator?</h3>
        <p style="color: var(--text-muted); font-size: 0.95rem;">Join the directory to showcase public lectures, exhibitions, and performances to students and local communities.</p>
      </div>
      <div style="display: flex; gap: 0.75rem;">
        <a href="organisers_venues.php" class="btn btn-outline">Explore Directory</a>
        <a href="contact.php" class="btn btn-primary">Contact Admin</a>
      </div>
    </div>
  </div>
</section>
