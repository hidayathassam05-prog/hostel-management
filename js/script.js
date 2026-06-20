// Utility Functions
const Utils = {
  // Show success message
  showSuccess: function(message, duration = 3000) {
    this.showAlert(message, 'alert-success', duration);
  },

  // Show error message
  showError: function(message, duration = 3000) {
    this.showAlert(message, 'alert-danger', duration);
  },

  // Show info message
  showInfo: function(message, duration = 3000) {
    this.showAlert(message, 'alert-info', duration);
  },

  // Generic alert display
  showAlert: function(message, type = 'alert-info', duration = 3000) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${type}`;
    alertDiv.textContent = message;
    alertDiv.style.marginBottom = '1rem';
    
    const container = document.querySelector('main') || document.body;
    container.insertBefore(alertDiv, container.firstChild);

    if (duration) {
      setTimeout(() => alertDiv.remove(), duration);
    }
  },

  // Form validation
  validateEmail: function(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
  },

  validatePassword: function(password) {
    return password.length >= 6;
  },

  // LocalStorage helpers
  setStorage: function(key, value) {
    localStorage.setItem(key, JSON.stringify(value));
  },

  getStorage: function(key) {
    const item = localStorage.getItem(key);
    return item ? JSON.parse(item) : null;
  },

  removeStorage: function(key) {
    localStorage.removeItem(key);
  },

  // API call helper
  fetch: function(url, options = {}) {
    const defaultOptions = {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
    };

    return fetch(url, { ...defaultOptions, ...options })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .catch(error => {
        console.error('Fetch error:', error);
        throw error;
      });
  },

  // Format date
  formatDate: function(date) {
    if (typeof date === 'string') {
      date = new Date(date);
    }
    return date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    });
  },

  // Confirm dialog
  confirm: function(message) {
    return window.confirm(message);
  },
};

// Form handling
const Form = {
  // Validate and submit form
  submit: function(formId, callback) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(form);
      const data = Object.fromEntries(formData);
      
      if (callback) {
        callback(data);
      }
    });
  },

  // Clear form
  clear: function(formId) {
    const form = document.getElementById(formId);
    if (form) form.reset();
  },

  // Get form data
  getData: function(formId) {
    const form = document.getElementById(formId);
    if (!form) return null;
    
    const formData = new FormData(form);
    return Object.fromEntries(formData);
  },

  // Disable submit button
  disableSubmit: function(formId) {
    const form = document.getElementById(formId);
    if (form) {
      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn) submitBtn.disabled = true;
    }
  },

  // Enable submit button
  enableSubmit: function(formId) {
    const form = document.getElementById(formId);
    if (form) {
      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn) submitBtn.disabled = false;
    }
  },
};

// DOM helpers
const DOM = {
  // Get element
  get: function(selector) {
    return document.querySelector(selector);
  },

  // Get all elements
  getAll: function(selector) {
    return document.querySelectorAll(selector);
  },

  // Create element
  create: function(tag, className = '', textContent = '') {
    const element = document.createElement(tag);
    if (className) element.className = className;
    if (textContent) element.textContent = textContent;
    return element;
  },

  // Add class
  addClass: function(selector, className) {
    const element = this.get(selector);
    if (element) element.classList.add(className);
  },

  // Remove class
  removeClass: function(selector, className) {
    const element = this.get(selector);
    if (element) element.classList.remove(className);
  },

  // Toggle class
  toggleClass: function(selector, className) {
    const element = this.get(selector);
    if (element) element.classList.toggle(className);
  },

  // Set attribute
  setAttribute: function(selector, attr, value) {
    const element = this.get(selector);
    if (element) element.setAttribute(attr, value);
  },

  // Get text
  getText: function(selector) {
    const element = this.get(selector);
    return element ? element.textContent : '';
  },

  // Set text
  setText: function(selector, text) {
    const element = this.get(selector);
    if (element) element.textContent = text;
  },

  // Show element
  show: function(selector) {
    const element = this.get(selector);
    if (element) element.style.display = 'block';
  },

  // Hide element
  hide: function(selector) {
    const element = this.get(selector);
    if (element) element.style.display = 'none';
  },
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
  // Any initialization code here
  console.log('DOM loaded, ready to go!');
});

// Export for use in modules (if needed)
if (typeof module !== 'undefined' && module.exports) {
  module.exports = { Utils, Form, DOM };
}
