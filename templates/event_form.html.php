<!-- reusable template for both add event and edit event operations -->
<section style="padding: 3.5rem 0 5rem;">
  <div class="container">
    <div style="max-width: 780px; margin: 0 auto 2rem;">
      <span class="hero-lead-label"><?= $isEdit ? 'Update Existing Event' : 'Create New Event' ?></span>
      <h1 class="section-title" style="font-size: 2.25rem;">
        <?= $isEdit ? 'Edit Event: ' . e($event['title']) : 'Post a New Bulletin Event' ?>
      </h1>
      <p class="section-desc">
        Complete the fields below. Organisers and venues are bound dynamically via relational foreign keys.
      </p>
    </div>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-error" style="max-width: 780px; margin: 0 auto 2rem;" role="alert">
        <div>
          <strong>Please correct the following errors:</strong>
          <ul style="margin-left: 1.25rem; margin-top: 0.5rem;">
            <?php foreach ($errors as $err): ?>
              <li><?= e($err) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    <?php endif; ?>

    <div class="form-card">
      <form action="<?= $isEdit ? 'editevent.php?id=' . e($event['id']) : 'addevent.php' ?>" method="POST" enctype="multipart/form-data" id="eventForm" novalidate>
        <?php if ($isEdit): ?>
          <input type="hidden" name="id" value="<?= e($event['id']) ?>">
        <?php endif; ?>

        <div class="form-grid">
          <div class="form-group col-full">
            <label for="title" class="form-label">
              Event Title <span class="required">*</span>
            </label>
            <input 
              type="text" 
              name="title" 
              id="title" 
              class="form-control" 
              placeholder="e.g., Vietnam Inter-University AI & Code Hackathon" 
              value="<?= e($event['title'] ?? '') ?>" 
              required
            >
          </div>

          <div class="form-group">
            <label for="category" class="form-label">
              Category <span class="required">*</span>
            </label>
            <input 
              type="text" 
              name="category" 
              id="category" 
              class="form-control" 
              placeholder="e.g., Technology & Code, Academic & Research, Live Music & Arts" 
              value="<?= e($event['category'] ?? '') ?>" 
              required
            >
          </div>

          <div class="form-group">
            <label for="status" class="form-label">Event Status</label>
            <select name="status" id="status" class="form-control">
              <?php
                $statusList = ['Upcoming', 'Ongoing', 'Completed', 'Cancelled'];
                $curStatus = $event['status'] ?? 'Upcoming';
                foreach ($statusList as $st):
              ?>
                <option value="<?= e($st) ?>" <?= $curStatus === $st ? 'selected' : '' ?>>
                  <?= e($st) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="organiser_id" class="form-label">
              Host / Organiser <span class="required">*</span>
            </label>
            <select name="organiser_id" id="organiser_id" class="form-control" required>
              <option value="">-- Select Organiser --</option>
              <?php foreach ($organisers as $org): ?>
                <option value="<?= e($org['id']) ?>" <?= (isset($event['organiser_id']) && (int)$event['organiser_id'] === (int)$org['id']) ? 'selected' : '' ?>>
                  <?= e($org['full_name'] ?? $org['name'] ?? '') ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="venue_id" class="form-label">
              Venue Location <span class="required">*</span>
            </label>
            <select name="venue_id" id="venue_id" class="form-control" required>
              <option value="">-- Select Venue --</option>
              <?php foreach ($venues as $ven): ?>
                <option value="<?= e($ven['id']) ?>" <?= (isset($event['venue_id']) && (int)$event['venue_id'] === (int)$ven['id']) ? 'selected' : '' ?>>
                  <?= e($ven['name']) ?> (<?= e($ven['city']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="event_date" class="form-label">
              Event Date <span class="required">*</span>
            </label>
            <input 
              type="date" 
              name="event_date" 
              id="event_date" 
              class="form-control" 
              value="<?= e($event['event_date'] ?? date('Y-m-d')) ?>" 
              required
            >
          </div>

          <div class="form-group">
            <label for="ticket_price" class="form-label">
              Ticket Price (£)
            </label>
            <input 
              type="number" 
              name="ticket_price" 
              id="ticket_price" 
              class="form-control" 
              step="0.01" 
              min="0" 
              placeholder="0.00 for Free Admission" 
              value="<?= e($event['ticket_price'] ?? '0.00') ?>"
            >
            <span class="form-helper">Enter 0 for Free Admission</span>
          </div>

          <div class="form-group">
            <label for="start_time" class="form-label">
              Start Time <span class="required">*</span>
            </label>
            <input 
              type="time" 
              name="start_time" 
              id="start_time" 
              class="form-control" 
              value="<?= e($event['start_time'] ?? '10:00') ?>" 
              required
            >
          </div>

          <div class="form-group">
            <label for="end_time" class="form-label">
              End Time <span class="required">*</span>
            </label>
            <input 
              type="time" 
              name="end_time" 
              id="end_time" 
              class="form-control" 
              value="<?= e($event['end_time'] ?? '17:00') ?>" 
              required
            >
          </div>

          <div class="form-group col-full">
            <label class="form-label">
              Event Cover Image <span class="required">*</span>
            </label>

            <div class="upload-wrapper">
              <div class="upload-actions">
                <input 
                  type="file" 
                  name="image_file" 
                  id="image_file" 
                  class="file-input-hidden" 
                  accept="image/jpeg,image/png,image/webp,image/gif"
                >
                <label for="image_file" class="btn-upload-trigger" id="uploadBtnLabel">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                  </svg>
                  <span>Upload Image</span>
                </label>
                <span id="fileNameBadge" class="file-name-badge">
                  <?= !empty($event['image_url']) ? basename($event['image_url']) : 'No file chosen' ?>
                </span>
              </div>
              <span class="form-helper">Select an image file from your computer (JPG, PNG, WEBP, GIF, max 5MB).</span>

              <input 
                type="hidden" 
                name="existing_image" 
                id="existing_image" 
                value="<?= e($event['image_url'] ?? '') ?>"
              >
            </div>

            <div style="margin-top: 1.25rem;">
              <span style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); display: block; margin-bottom: 0.4rem; font-family: var(--font-mono);">
                Cover Preview:
              </span>
              <div class="preview-box" id="imagePreviewBox">
                <div class="preview-placeholder" id="previewPlaceholder" style="<?= !empty($event['image_url']) ? 'display: none;' : 'display: flex;' ?>">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                  </svg>
                  <strong>No image selected</strong>
                  <span style="font-size: 0.75rem; color: var(--text-subtle);">Click 'Upload Image' above to select a cover from your computer</span>
                </div>
                <img 
                  id="imagePreview" 
                  src="<?= e($event['image_url'] ?? '') ?>" 
                  alt="Event Cover Preview" 
                  class="preview-image"
                  style="<?= !empty($event['image_url']) ? 'display: block;' : 'display: none;' ?>"
                >
              </div>
            </div>
          </div>

          <div class="form-group col-full">
            <label for="description" class="form-label">
              Event Description &amp; Details <span class="required">*</span>
            </label>
            <textarea 
              name="description" 
              id="description" 
              class="form-control" 
              rows="6" 
              placeholder="Provide a comprehensive synopsis of the event, scheduled speakers, and audience expectations..." 
              required
            ><?= e($event['description'] ?? '') ?></textarea>
          </div>
        </div>

        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
          <a href="manage.php" class="btn btn-outline">
            Cancel
          </a>
          <button type="submit" class="btn btn-primary">
            <?= $isEdit ? 'Update Event &rarr;' : 'Save & Publish Event &rarr;' ?>
          </button>
        </div>
      </form>
    </div>
  </div>
</section>
