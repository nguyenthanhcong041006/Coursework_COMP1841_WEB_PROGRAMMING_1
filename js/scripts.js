
document.addEventListener('DOMContentLoaded', () => {
  // mobile menu toggle
  const mobileToggle = document.getElementById('mobileNavToggle');
  const mainNav = document.getElementById('mainNav');

  if (mobileToggle && mainNav) {
    mobileToggle.addEventListener('click', () => {
      const isExpanded = mobileToggle.getAttribute('aria-expanded') === 'true';
      mobileToggle.setAttribute('aria-expanded', !isExpanded);
      mainNav.classList.toggle('open');
    });
  }

  // tabs switcher (organisers & venues page)
  const tabButtons = document.querySelectorAll('.tab-btn');
  const tabPanes = document.querySelectorAll('.tab-pane');

  if (tabButtons.length > 0) {
    tabButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        const targetId = btn.getAttribute('data-target');

        tabButtons.forEach(b => {
          b.classList.remove('active');
          b.setAttribute('aria-selected', 'false');
        });
        tabPanes.forEach(p => p.classList.remove('active'));

        btn.classList.add('active');
        btn.setAttribute('aria-selected', 'true');
        const activePane = document.getElementById(targetId);
        if (activePane) {
          activePane.classList.add('active');
        }

        // update URL hash without causing a page reload
        if (history.replaceState) {
          history.replaceState(null, null, '#' + targetId);
        }
      });
    });

    // check URL hash on initial page load
    const currentHash = window.location.hash.replace('#', '');
    if (currentHash) {
      const matchingBtn = document.querySelector(`.tab-btn[data-target="${currentHash}"]`);
      if (matchingBtn) {
        matchingBtn.click();
      }
    }
  }

  // delete confirmation dialog for safety
  const deleteForms = document.querySelectorAll('.delete-event-form');
  deleteForms.forEach(form => {
    form.addEventListener('submit', (e) => {
      const eventTitle = form.getAttribute('data-title') || 'this event';
      const confirmed = window.confirm(`Are you sure you want to permanently delete "${eventTitle}"?\nThis action cannot be undone.`);
      if (!confirmed) {
        e.preventDefault();
      }
    });
  });

  // minimalist image upload & live preview handler (always visible box)
  const fileInput = document.getElementById('image_file');
  const fileNameBadge = document.getElementById('fileNameBadge');
  const existingImageInput = document.getElementById('existing_image');
  const previewPlaceholder = document.getElementById('previewPlaceholder');
  const imagePreviewEl = document.getElementById('imagePreview');

  const showPreview = (src) => {
    if (imagePreviewEl && previewPlaceholder) {
      imagePreviewEl.src = src;
      imagePreviewEl.style.display = 'block';
      previewPlaceholder.style.display = 'none';
    }
  };

  const clearPreview = () => {
    if (imagePreviewEl && previewPlaceholder) {
      imagePreviewEl.src = '';
      imagePreviewEl.style.display = 'none';
      previewPlaceholder.style.display = 'flex';
    }
  };

  // handle local file selection
  if (fileInput && fileNameBadge) {
    fileInput.addEventListener('change', () => {
      if (fileInput.files && fileInput.files[0]) {
        const file = fileInput.files[0];
        const fileSizeKb = Math.round(file.size / 1024);
        
        fileNameBadge.textContent = `${file.name} (${fileSizeKb} KB)`;
        fileNameBadge.style.color = 'var(--text-main)';
        fileNameBadge.style.fontWeight = '600';

        // read and preview the selected local file
        const reader = new FileReader();
        reader.onload = (e) => {
          showPreview(e.target.result);
        };
        reader.readAsDataURL(file);
      } else {
        const existingImg = existingImageInput ? existingImageInput.value.trim() : '';
        if (existingImg) {
          fileNameBadge.textContent = existingImg.split('/').pop();
          fileNameBadge.style.color = 'var(--text-muted)';
          fileNameBadge.style.fontWeight = 'normal';
          showPreview(existingImg);
        } else {
          fileNameBadge.textContent = 'No file chosen';
          fileNameBadge.style.color = 'var(--text-muted)';
          fileNameBadge.style.fontWeight = 'normal';
          clearPreview();
        }
      }
    });
  }

  // client-side form validation for add/edit event
  const eventForm = document.getElementById('eventForm');
  if (eventForm) {
    eventForm.addEventListener('submit', (e) => {
      const title = document.getElementById('title')?.value.trim();
      const eventDate = document.getElementById('event_date')?.value;
      const startTime = document.getElementById('start_time')?.value;
      const endTime = document.getElementById('end_time')?.value;
      const organiserId = document.getElementById('organiser_id')?.value;
      const venueId = document.getElementById('venue_id')?.value;
      const existingImg = document.getElementById('existing_image')?.value.trim();
      const hasFile = fileInput && fileInput.files && fileInput.files.length > 0;

      let errors = [];

      if (!title) errors.push('Event title cannot be empty.');
      if (!eventDate) errors.push('Please select a valid event date.');
      if (!organiserId) errors.push('Please select an event organiser.');
      if (!venueId) errors.push('Please select a venue.');

      if (startTime && endTime && startTime >= endTime) {
        errors.push('End time must be later than start time.');
      }

      // check if image file is uploaded or already exists (for editing)
      if (!hasFile && !existingImg) {
        errors.push('Please upload a cover image file for the event.');
      }

      if (errors.length > 0) {
        e.preventDefault();
        alert('Please review the following requirements:\n• ' + errors.join('\n• '));
      }
    });
  }

  // client-side validation for contact form
  const contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
      const name = document.getElementById('contact_name')?.value.trim();
      const email = document.getElementById('contact_email')?.value.trim();
      const message = document.getElementById('contact_message')?.value.trim();

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      let errors = [];

      if (!name) errors.push('Please provide your name.');
      if (!email || !emailRegex.test(email)) errors.push('Please enter a valid email address.');
      if (!message || message.length < 10) errors.push('Your message must be at least 10 characters.');

      if (errors.length > 0) {
        e.preventDefault();
        alert('Please complete the contact form:\n• ' + errors.join('\n• '));
      }
    });
  }
});
