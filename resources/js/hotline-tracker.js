/**
 * Hotline Tracking Module
 * - Requests geolocation on page load
 * - Logs all WhatsApp/hotline clicks with location data
 */

const HotlineTracker = {
  location: null,
  locationError: null,
  isReady: false,

  init() {
    this.requestLocation();
    this.bindClickHandlers();
  },

  requestLocation() {
    if (!navigator.geolocation) {
      this.locationError = 'Geolocation not supported';
      this.isReady = true;
      return;
    }

    navigator.geolocation.getCurrentPosition(
      (position) => {
        this.location = {
          latitude: position.coords.latitude,
          longitude: position.coords.longitude,
          accuracy: position.coords.accuracy
        };
        this.isReady = true;
        console.log('[HotlineTracker] Location acquired');
      },
      (error) => {
        this.locationError = error.message || 'Location denied';
        this.isReady = true;
        console.log('[HotlineTracker] Location error:', this.locationError);
      },
      {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 300000 // 5 minutes cache
      }
    );
  },

  bindClickHandlers() {
    // Track all links with data-hotline-track attribute
    document.addEventListener('click', (e) => {
      const link = e.target.closest('[data-hotline-track]');
      if (!link) return;

      this.logAccess();
    });
  },

  async logAccess() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) return;

    const payload = {
      ...(this.location || {}),
      ...(this.locationError ? { location_error: this.locationError } : {})
    };

    try {
      await fetch('/api/hotline-access', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
      });
    } catch (e) {
      // Silently fail - don't block user
    }
  }
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
  HotlineTracker.init();
});

// Expose globally
window.HotlineTracker = HotlineTracker;
