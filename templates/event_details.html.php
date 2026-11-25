<section class="page-section">
  <div class="container">
    <nav aria-label="Breadcrumb" class="breadcrumb-nav">
      <ol class="breadcrumb-list">
        <li><a href="index.php">Home</a></li>
        <li><span>/</span></li>
        <li><a href="events.php">Events</a></li>
        <li><span>/</span></li>
        <li class="breadcrumb-active" aria-current="page"><?= e($event['title']) ?></li>
      </ol>
    </nav>

    <div class="detail-hero">
      <img src="<?= e($event['image_url']) ?>" alt="<?= e($event['title']) ?>" class="detail-hero-image">
      <div class="detail-hero-overlay">
        <div class="detail-hero-content">
          <div style="display: flex; gap: 0.5rem; margin-bottom: 0.75rem; flex-wrap: wrap;">
            <span class="badge badge-accent"><?= e($event['category']) ?></span>
            <span class="badge badge-free"><?= formatPrice($event['ticket_price']) ?></span>
            <span class="badge badge-status"><?= e($event['status']) ?></span>
          </div>
          <h1 style="font-family: var(--font-heading); font-size: clamp(1.75rem, 3.5vw, 2.75rem); font-weight: 800; line-height: 1.15; letter-spacing: -0.03em;">
            <?= e($event['title']) ?>
          </h1>
        </div>
      </div>
    </div>

    <div class="detail-layout">
      <article>
        <div class="detail-content-card">
          <h2 class="detail-section-title" style="margin-top: 0;">Overview &amp; Purpose</h2>
          <div class="detail-description">
            <p><?= nl2br(e($event['description'])) ?></p>
          </div>

          <h2 class="detail-section-title">Schedule &amp; Timings</h2>
          <div class="timing-grid">
            <div class="timing-box">
              <span class="timing-label">Event Date</span>
              <strong class="timing-value"><?= formatDate($event['event_date']) ?></strong>
            </div>

            <div class="timing-box">
              <span class="timing-label">Time Window</span>
              <strong class="timing-value">
                <?= formatTime($event['start_time']) ?> – <?= formatTime($event['end_time']) ?>
              </strong>
            </div>

            <div class="timing-box">
              <span class="timing-label">Admission</span>
              <strong class="timing-value"><?= formatPrice($event['ticket_price']) ?></strong>
            </div>
          </div>

          <div class="detail-actions-bar" style="justify-content: flex-end;">
            <div style="display: flex; gap: 0.75rem;">
              <a href="contact.php?event_id=<?= e($event['id']) ?>" class="btn btn-outline">
                Inquire via Contact
              </a>
              <a href="editevent.php?id=<?= e($event['id']) ?>" class="btn btn-primary">
                Edit Event
              </a>
            </div>
          </div>
        </div>
      </article>

      <aside>
        <div class="meta-card">
          <span style="font-family: var(--font-mono); font-size: 0.7rem; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.1em; display: block; margin-bottom: 0.5rem;">
            Event Venue
          </span>
          <div class="meta-card-header">
            <?php if (!empty($event['venue_image'])): ?>
              <img src="<?= e($event['venue_image']) ?>" alt="<?= e($event['venue_name']) ?>" class="meta-avatar">
            <?php endif; ?>
            <div>
              <h3 class="meta-title"><?= e($event['venue_name']) ?></h3>
              <p style="font-size: 0.85rem; color: var(--text-muted);"><?= e($event['venue_city']) ?></p>
            </div>
          </div>
          <div style="font-size: 0.875rem; color: var(--text-muted); line-height: 1.6;">
            <p><strong>Address:</strong> <?= e($event['venue_address']) ?></p>
            <p><strong>Capacity:</strong> <?= e($event['venue_capacity']) ?> attendees</p>
            <?php if (!empty($event['venue_contact'])): ?>
              <p><strong>Curator / Contact:</strong> <?= e($event['venue_contact']) ?></p>
            <?php endif; ?>
          </div>
          <div style="margin-top: 1rem; padding-top: 0.75rem; border-top: 1px solid var(--border-light);">
            <a href="organisers_venues.php#venues" style="font-size: 0.8rem; font-weight: 600; color: var(--text-main);">
              View all events at this venue &rarr;
            </a>
          </div>
        </div>

        <div class="meta-card">
          <span style="font-family: var(--font-mono); font-size: 0.7rem; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.1em; display: block; margin-bottom: 0.5rem;">
            Organised By
          </span>
          <div class="meta-card-header">
            <?php if (!empty($event['organiser_logo'])): ?>
              <img src="<?= e($event['organiser_logo']) ?>" alt="<?= e($event['organiser_name']) ?>" class="meta-avatar" style="border-radius: 0;">
            <?php endif; ?>
            <div>
              <h3 class="meta-title"><?= e($event['organiser_name']) ?></h3>
              <p style="font-size: 0.85rem; color: var(--text-muted);">Verified Bulletin Host</p>
            </div>
          </div>
          <div style="font-size: 0.875rem; color: var(--text-muted); line-height: 1.6;">
            <?php if (!empty($event['organiser_bio'])): ?>
              <p style="margin-bottom: 0.75rem;"><?= e($event['organiser_bio']) ?></p>
            <?php endif; ?>
            <p><strong>Email:</strong> <a href="mailto:<?= e($event['organiser_email']) ?>" style="text-decoration: underline;"><?= e($event['organiser_email']) ?></a></p>
            <?php if (!empty($event['organiser_phone'])): ?>
              <p><strong>Hotline:</strong> <?= e($event['organiser_phone']) ?></p>
            <?php endif; ?>
            <?php if (!empty($event['organiser_website'])): ?>
              <p><strong>Website:</strong> <a href="<?= e($event['organiser_website']) ?>" target="_blank" rel="noopener noreferrer" style="text-decoration: underline;">Visit Site</a></p>
            <?php endif; ?>
          </div>
          <div style="margin-top: 1rem; padding-top: 0.75rem; border-top: 1px solid var(--border-light);">
            <a href="organisers_venues.php#organisers" style="font-size: 0.8rem; font-weight: 600; color: var(--text-main);">
              View organiser profile &rarr;
            </a>
          </div>
        </div>
      </aside>
    </div>
  </div>
</section>
