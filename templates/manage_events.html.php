<!-- event management dashboard providing CRUD capabilities -->
<section style="padding: 3.5rem 0;">
  <div class="container">
    <div class="section-header">
      <div>
        <span class="hero-lead-label">Event Dashboard</span>
        <h1 class="section-title" style="font-size: 2.25rem;">Manage Events</h1>
        <p class="section-desc">
          Review, publish, update, and manage all public event listings.
        </p>
      </div>

      <a href="addevent.php" class="btn btn-primary">
        + Add New Event
      </a>
    </div>

    <?php if (!empty($flashMessage)): ?>
      <div class="alert <?= $flashMessage['type'] === 'error' ? 'alert-error' : 'alert-success' ?>" role="alert">
        <span><?= e($flashMessage['text']) ?></span>
      </div>
    <?php endif; ?>

    <div style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; font-size: 0.875rem; color: var(--text-muted);">
      <span>Total Events: <strong><?= count($events) ?></strong></span>
      <span>Status: Active Directory</span>
    </div>

    <div class="data-table-wrapper">
      <table class="data-table" aria-label="Event Management Table">
        <thead>
          <tr>
            <th scope="col" style="width: 70px;">Cover</th>
            <th scope="col" style="min-width: 220px;">Event Title</th>
            <th scope="col" style="white-space: nowrap; min-width: 200px;">Date &amp; Time</th>
            <th scope="col" style="white-space: nowrap;">Organiser</th>
            <th scope="col" style="white-space: nowrap;">Venue</th>
            <th scope="col" style="white-space: nowrap;">Category</th>
            <th scope="col" style="white-space: nowrap;">Price</th>
            <th scope="col" style="text-align: right; width: 180px; white-space: nowrap;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($events)): ?>
            <?php foreach ($events as $ev): ?>
              <tr>
                <td>
                  <img src="<?= e($ev['image_url']) ?>" alt="" class="table-thumb" loading="lazy">
                </td>
                <td>
                  <strong style="color: var(--text-main); display: block; font-size: 0.95rem;">
                    <?= e($ev['title']) ?>
                  </strong>
                  <span style="font-size: 0.75rem; color: var(--text-muted); font-family: var(--font-mono);">
                    ID: #<?= e($ev['id']) ?> · <?= e($ev['status']) ?>
                  </span>
                </td>
                <td style="white-space: nowrap;">
                  <span style="font-weight: 600; color: var(--text-main);"><?= formatDate($ev['event_date']) ?></span>
                  <span style="display: inline-block; margin-left: 0.35rem; color: var(--text-muted); font-size: 0.8rem; font-family: var(--font-mono);">
                    · <?= formatTime($ev['start_time']) ?>–<?= formatTime($ev['end_time']) ?>
                  </span>
                </td>
                <td>
                  <span><?= e($ev['organiser_name']) ?></span>
                </td>
                <td>
                  <span><?= e($ev['venue_name']) ?></span>
                  <span style="display: block; font-size: 0.75rem; color: var(--text-muted);"><?= e($ev['venue_city']) ?></span>
                </td>
                <td>
                  <span class="badge"><?= e($ev['category']) ?></span>
                </td>
                <td>
                  <span class="badge <?= $ev['ticket_price'] <= 0 ? 'badge-free' : '' ?>">
                    <?= formatPrice($ev['ticket_price']) ?>
                  </span>
                </td>
                <td>
                  <div class="table-actions" style="justify-content: flex-end;">
                    <a href="event.php?id=<?= e($ev['id']) ?>" class="btn btn-outline btn-sm" title="View details">
                      View
                    </a>
                    <a href="editevent.php?id=<?= e($ev['id']) ?>" class="btn btn-primary btn-sm" title="Edit this event">
                      Edit
                    </a>

                    <!-- destructive action uses POST to prevent accidental prefetch or CSRF -->
                    <form action="deleteevent.php" method="POST" class="delete-event-form" data-title="<?= e($ev['title']) ?>" style="display: inline;">
                      <input type="hidden" name="id" value="<?= e($ev['id']) ?>">
                      <button type="submit" class="btn btn-danger btn-sm" title="Delete this event">
                        Delete
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                No events currently exist in the database. Click "+ Add New Event" above to create one.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
