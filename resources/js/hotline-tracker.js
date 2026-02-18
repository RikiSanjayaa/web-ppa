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

  async requestLocation() {
    if (!navigator.geolocation) {
      this.locationError = 'Geolocation not supported';
      this.isReady = true;
      return;
    }

    // Check Permissions API if available
    if (navigator.permissions && navigator.permissions.query) {
      try {
        const perm = await navigator.permissions.query({ name: 'geolocation' });
        if (perm.state === 'denied') {
          this.locationError = 'Location permission denied';
          this.isReady = true;
          console.log('[HotlineTracker] Location permission denied, skipping request');
          return;
        }
      } catch (e) {
        // Safari doesn't support permissions.query for geolocation, continue
      }
    }

    try {
      // Try high accuracy first
      const position = await this._attemptGeolocation(true);
      this._onSuccess(position);
    } catch (highAccError) {
      // Fallback to low accuracy on timeout or position unavailable
      if (highAccError.code === 2 || highAccError.code === 3) {
        try {
          const position = await this._attemptGeolocation(false);
          this._onSuccess(position);
        } catch (lowAccError) {
          this.locationError = lowAccError.message || 'Location denied';
          this.isReady = true;
          console.log('[HotlineTracker] Location error (fallback):', this.locationError);
        }
      } else {
        this.locationError = highAccError.message || 'Location denied';
        this.isReady = true;
        console.log('[HotlineTracker] Location error:', this.locationError);
      }
    }
  },

  _attemptGeolocation(highAccuracy) {
    return new Promise((resolve, reject) => {
      navigator.geolocation.getCurrentPosition(resolve, reject, {
        enableHighAccuracy: highAccuracy,
        timeout: 15000,
        maximumAge: 300000 // 5 minutes cache
      });
    });
  },

  _onSuccess(position) {
    this.location = {
      latitude: position.coords.latitude,
      longitude: position.coords.longitude,
      accuracy: position.coords.accuracy
    };
    this.isReady = true;
    console.log('[HotlineTracker] Location acquired');
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
