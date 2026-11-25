<!-- directory template demonstrating relational 1-to-many relationships -->
<section style="padding: 3.5rem 0 5rem;">
  <div class="container">
    <div class="section-header">
      <div>
        <span class="hero-lead-label">Directory &amp; Partnerships</span>
        <h1 class="section-title" style="font-size: 2.25rem;">Organisers &amp; Venues</h1>
        <p class="section-desc">
          Meet the verified event curators and architectural venues powering our bulletin board.
        </p>
      </div>

      <a href="contact.php" class="btn btn-outline btn-sm">Partner With Us</a>
    </div>

    <div class="tabs-nav" role="tablist" aria-label="Directory Navigation">
      <button class="tab-btn active" role="tab" aria-selected="true" data-target="organisers" id="tab-organisers">
        Event Organisers (<?= count($organisers) ?>)
      </button>
      <button class="tab-btn" role="tab" aria-selected="false" data-target="venues" id="tab-venues">
        Partner Venues (<?= count($venues) ?>)
      </button>
    </div>

    <div class="tab-pane active" id="organisers" role="tabpanel" aria-labelledby="tab-organisers">
      <div class="entity-grid">
        <?php foreach ($organisers as $org): ?>
          <article class="entity-card">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
              <img src="<?= e($org['logo_url']) ?>" alt="<?= e($org['full_name'] ?? $org['name'] ?? '') ?>" style="width: 56px; height: 56px; border-radius: 0; object-fit: cover; border: 1px solid var(--border-light);">
              <div>
                <h2 style="font-family: var(--font-heading); font-size: 1.15rem; font-weight: 700; line-height: 1.2;">
                  <?= e($org['full_name'] ?? $org['name'] ?? '') ?>
                </h2>
                <span style="font-size: 0.8rem; color: var(--text-muted); font-family: var(--font-mono);">
                  <?= $org['event_count'] ?> Hosted Event(s)
                </span>
              </div>
            </div>

            <p style="font-size: 0.875rem; color: var(--text-muted); line-height: 1.6; margin-bottom: 1rem;">
              <?= e($org['bio']) ?>
            </p>

            <div style="font-size: 0.8rem; color: var(--text-main); display: flex; flex-direction: column; gap: 0.35rem; margin-bottom: 1.25rem;">
              <div>
                <strong>Email:</strong> 
                <a href="mailto:<?= e($org['email']) ?>" style="color: var(--text-muted); text-decoration: underline;"><?= e($org['email']) ?></a>
              </div>
              <?php if (!empty($org['phone'])): ?>
                <div>
                  <strong>Phone:</strong> <span style="color: var(--text-muted);"><?= e($org['phone']) ?></span>
                </div>
              <?php endif; ?>
              <?php if (!empty($org['website'])): ?>
                <div>
                  <strong>Web:</strong> 
                  <a href="<?= e($org['website']) ?>" target="_blank" rel="noopener noreferrer" style="color: var(--text-muted); text-decoration: underline;">
                    <?= e($org['website']) ?>
                  </a>
                </div>
              <?php endif; ?>
            </div>

            <div class="entity-linked-events">
              <span style="font-family: var(--font-mono); font-size: 0.7rem; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem;">
                Scheduled Events by this Organiser:
              </span>
              <?php if (!empty($org['events'])): ?>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.4rem;">
                  <?php foreach ($org['events'] as $eItem): ?>
                    <li style="display: flex; align-items: baseline; justify-content: space-between; gap: 0.75rem; font-size: 0.825rem;">
                      <a href="event.php?id=<?= e($eItem['id']) ?>" style="font-weight: 600; color: var(--text-main); text-decoration: underline; flex: 1; min-width: 0;">
                        <?= e($eItem['title']) ?>
                      </a>
                      <span style="font-size: 0.75rem; color: var(--text-muted); font-family: var(--font-mono); white-space: nowrap; flex-shrink: 0;">
                        <?= formatDate($eItem['event_date']) ?>
                      </span>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php else: ?>
                <span style="font-size: 0.8rem; color: var(--text-muted); font-style: italic;">No events currently scheduled.</span>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="tab-pane" id="venues" role="tabpanel" aria-labelledby="tab-venues">
      <div class="entity-grid">
        <?php foreach ($venues as $ven): ?>
          <article class="entity-card">
            <?php if (!empty($ven['image_url'])): ?>
              <div style="width: 100%; height: 160px; border-radius: var(--radius-sm); overflow: hidden; margin-bottom: 1rem; border: 1px solid var(--border-light);">
                <img src="<?= e($ven['image_url']) ?>" alt="<?= e($ven['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
              </div>
            <?php endif; ?>

            <div style="margin-bottom: 0.75rem;">
              <h2 style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 700;">
                <?= e($ven['name']) ?>
              </h2>
              <span style="font-size: 0.85rem; color: var(--text-muted);">
                <?= e($ven['address']) ?>, <?= e($ven['city']) ?>
              </span>
            </div>

            <div style="display: flex; gap: 1rem; font-size: 0.8rem; margin-bottom: 1rem;">
              <span class="badge">Capacity: <?= e($ven['capacity']) ?> guests</span>
              <span class="badge"><?= $ven['event_count'] ?> Event(s)</span>
            </div>

            <?php if (!empty($ven['contact_person'])): ?>
              <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">
                <strong>Curator / Point of Contact:</strong> <?= e($ven['contact_person']) ?>
              </p>
            <?php endif; ?>

            <div class="entity-linked-events">
              <span style="font-family: var(--font-mono); font-size: 0.7rem; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem;">
                Events Scheduled at this Space:
              </span>
              <?php if (!empty($ven['events'])): ?>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.4rem;">
                  <?php foreach ($ven['events'] as $eItem): ?>
                    <li style="display: flex; align-items: baseline; justify-content: space-between; gap: 0.75rem; font-size: 0.825rem;">
                      <a href="event.php?id=<?= e($eItem['id']) ?>" style="font-weight: 600; color: var(--text-main); text-decoration: underline; flex: 1; min-width: 0;">
                        <?= e($eItem['title']) ?>
                      </a>
                      <span style="font-size: 0.75rem; color: var(--text-muted); font-family: var(--font-mono); white-space: nowrap; flex-shrink: 0;">
                        <?= formatDate($eItem['event_date']) ?>
                      </span>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php else: ?>
                <span style="font-size: 0.8rem; color: var(--text-muted); font-style: italic;">No events currently scheduled at this venue.</span>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
