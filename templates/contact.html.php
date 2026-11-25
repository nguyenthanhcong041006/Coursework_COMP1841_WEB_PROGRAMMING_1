<!-- contact administrator template with server-side validation and database persistence -->
<section style="padding: 3.5rem 0 5rem;">
  <div class="container">
    <div style="max-width: 780px; margin: 0 auto 2.5rem;">
      <span class="hero-lead-label">Inquiries &amp; Administration</span>
      <h1 class="section-title" style="font-size: 2.25rem;">Contact Administrator</h1>
      <p class="section-desc">
        Have questions regarding bulletin listings, venue bookings, or technical feedback? Complete the form below to reach the system administrator.
      </p>
    </div>

    <?php if (!empty($successMessage)): ?>
      <div class="alert alert-success" style="max-width: 780px; margin: 0 auto 2rem;" role="alert">
        <div>
          <strong><?= e($successMessage) ?></strong>
          <p style="margin-top: 0.25rem; font-size: 0.85rem;">
            Your message has been safely logged in the system database. An administrator will reply shortly.
          </p>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-error" style="max-width: 780px; margin: 0 auto 2rem;" role="alert">
        <div>
          <strong>Please address the following items:</strong>
          <ul style="margin-left: 1.25rem; margin-top: 0.5rem;">
            <?php foreach ($errors as $err): ?>
              <li><?= e($err) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    <?php endif; ?>

    <div class="form-card">
      <form action="contact.php" method="POST" id="contactForm" novalidate>
        <div class="form-grid">
          <div class="form-group">
            <label for="contact_name" class="form-label">
              Your Full Name <span class="required">*</span>
            </label>
            <input 
              type="text" 
              name="full_name" 
              id="contact_name" 
              class="form-control" 
              placeholder="e.g., Alex Morgan" 
              value="<?= e($formData['full_name'] ?? $formData['name'] ?? '') ?>" 
              required
            >
          </div>

          <div class="form-group">
            <label for="contact_email" class="form-label">
              Email Address <span class="required">*</span>
            </label>
            <input 
              type="email" 
              name="email" 
              id="contact_email" 
              class="form-control" 
              placeholder="name@gre.ac.uk" 
              value="<?= e($formData['email'] ?? '') ?>" 
              required
            >
          </div>

          <div class="form-group">
            <label for="contact_subject" class="form-label">
              Subject <span class="required">*</span>
            </label>
            <select name="subject" id="contact_subject" class="form-control" required>
              <?php
                $subjects = [
                  'Event Inquiry / Question',
                  'Venue Partnership Proposal',
                  'Accessibility Assistance',
                  'Technical / Bug Report',
                  'General Feedback'
                ];
                $curSub = $formData['subject'] ?? '';
                foreach ($subjects as $sb):
              ?>
                <option value="<?= e($sb) ?>" <?= $curSub === $sb ? 'selected' : '' ?>>
                  <?= e($sb) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="event_id" class="form-label">
              Related Event (Optional)
            </label>
            <select name="event_id" id="event_id" class="form-control">
              <option value="">-- None (General Query) --</option>
              <?php foreach ($eventsList as $evItem): ?>
                <option value="<?= e($evItem['id']) ?>" <?= ((isset($formData['event_id']) && (int)$formData['event_id'] === (int)$evItem['id']) || (isset($_GET['event_id']) && (int)$_GET['event_id'] === (int)$evItem['id'])) ? 'selected' : '' ?>>
                  #<?= e($evItem['id']) ?> - <?= e($evItem['title']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group col-full">
            <label for="contact_message" class="form-label">
              Message / Inquiries <span class="required">*</span>
            </label>
            <textarea 
              name="message" 
              id="contact_message" 
              class="form-control" 
              rows="6" 
              placeholder="Type your message, query or feedback here..." 
              required
            ><?= e($formData['message'] ?? '') ?></textarea>
            <span class="form-helper">Please enter at least 10 characters.</span>
          </div>
        </div>

        <div style="margin-top: 1.5rem; display: flex; align-items: center; justify-content: flex-end;">
          <button type="submit" class="btn btn-primary">
            Send Message to Administrator &rarr;
          </button>
        </div>
      </form>
    </div>

    <div style="max-width: 780px; margin: 3rem auto 0; padding-top: 2rem; border-top: 1px solid var(--border-light); display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; font-size: 0.875rem;">
      <div>
        <strong style="display: block; color: var(--text-main); margin-bottom: 0.25rem;">Campus Location</strong>
        <p style="color: var(--text-muted);">Greenwich Viet Nam<br>20 Cong Hoa, Bay Hien ward<br>Ho Chi Minh City, Viet Nam</p>
      </div>
      <div>
        <strong style="display: block; color: var(--text-main); margin-bottom: 0.25rem;">Direct Inquiries</strong>
        <p style="color: var(--text-muted);">Email: <a href="mailto:greenwichvietnam@gmail.com" style="text-decoration: underline;">greenwichvietnam@gmail.com</a><br>Phone: 0987654321</p>
      </div>
      <div>
        <strong style="display: block; color: var(--text-main); margin-bottom: 0.25rem;">Office Hours</strong>
        <p style="color: var(--text-muted);">Monday – Friday<br>08:00 – 18:00 GMT</p>
      </div>
    </div>
  </div>
</section>
