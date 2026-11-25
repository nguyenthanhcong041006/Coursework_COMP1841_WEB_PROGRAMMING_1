<!-- relational event directory: demonstrates 3-table SQL JOIN (events, organisers, venues) -->
<section style="padding: 3.5rem 0;">
  <div class="container">
    <div class="section-header">
      <div>
        <span class="hero-lead-label">Schedule &amp; Listings</span>
        <h1 class="section-title" style="font-size: 2.25rem;">Events Directory</h1>
        <p class="section-desc">
          Browse all current and upcoming university bulletin events, research symposiums, hackathons, and exhibitions across campuses in Viet Nam.
        </p>
      </div>

      <div style="display: flex; gap: 0.75rem;">
        <a href="addevent.php" class="btn btn-primary btn-sm">+ Add New Event</a>
        <a href="manage.php" class="btn btn-outline btn-sm">Manage Events</a>
      </div>
    </div>

    <div class="filter-toolbar">
      <form action="events.php" method="GET" class="filter-form">
        <div class="form-group-filter">
          <label for="search" class="form-label" style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.35rem; display: block;">
            Search Keyword
          </label>
          <input 
            type="text" 
            name="search" 
            id="search" 
            class="input-filter" 
            placeholder="Search by title, description or city..." 
            value="<?= e($search) ?>"
          >
        </div>

        <div class="form-group-filter">
          <label for="category" class="form-label" style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.35rem; display: block;">
            Category
          </label>
          <select name="category" id="category" class="input-filter">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= e($cat) ?>" <?= $selectedCategory === $cat ? 'selected' : '' ?>>
                <?= e($cat) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div style="display: flex; gap: 0.5rem; align-items: flex-end; height: 100%;">
          <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.25rem;">
            Filter
          </button>
          <?php if (!empty($search) || !empty($selectedCategory)): ?>
            <a href="events.php" class="btn btn-outline" style="padding: 0.65rem 1rem;" title="Reset Filters">
              Reset
            </a>
          <?php endif; ?>
        </div>
      </form>

      <div class="category-pills">
        <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">Quick Filter:</span>
        <a href="events.php" class="pill-link <?= empty($selectedCategory) ? 'active' : '' ?>">All</a>
        <?php foreach ($categories as $cat): ?>
          <a href="events.php?category=<?= urlencode($cat) ?>" class="pill-link <?= $selectedCategory === $cat ? 'active' : '' ?>">
            <?= e($cat) ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

    <div style="margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between;">
      <p style="font-size: 0.9rem; color: var(--text-muted);">
        Showing <strong><?= count($events) ?></strong> event(s)
        <?php if (!empty($selectedCategory)): ?> in category <em>"<?= e($selectedCategory) ?>"</em><?php endif; ?>
        <?php if (!empty($search)): ?> matching <em>"<?= e($search) ?>"</em><?php endif; ?>
      </p>
    </div>

    <div class="events-grid">
      <?php if (!empty($events)): ?>
        <?php foreach ($events as $ev): ?>
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

              <h2 class="event-card-title">
                <a href="event.php?id=<?= e($ev['id']) ?>">
                  <?= e($ev['title']) ?>
                </a>
              </h2>

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
              <span class="badge badge-status"><?= e($ev['status']) ?></span>
              <a href="event.php?id=<?= e($ev['id']) ?>" class="btn btn-primary btn-sm">
                More Details &rarr;
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <div style="grid-column: 1 / -1; background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 4rem 2rem; text-align: center;">
          <svg style="width: 48px; height: 48px; stroke: var(--text-muted); margin: 0 auto 1rem;" viewBox="0 0 24 24" fill="none" stroke-width="1.5">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="8" y1="12" x2="16" y2="12"></line>
          </svg>
          <h3 style="font-family: var(--font-heading); font-size: 1.25rem; margin-bottom: 0.5rem;">No Events Found</h3>
          <p style="color: var(--text-muted); margin-bottom: 1.5rem;">There are no events matching your selected search query or category filter.</p>
          <a href="events.php" class="btn btn-primary">Clear Filters</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
