import './bootstrap';
import feather from 'feather-icons';
window.feather = feather;

// Initialize Feather icons when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
    feather.replace();
});

// Also initialize on page load as backup
window.addEventListener('load', function () {
    feather.replace();
});
