// Import Chart.js with full bundle
import Chart from 'chart.js/auto';

// Make Chart available globally
window.Chart = Chart;

// Add a ready event
window.addEventListener('DOMContentLoaded', function() {
    console.log('Chart.js initialized locally:', typeof Chart !== 'undefined');
    
    // Dispatch a custom event when Chart.js is ready
    const event = new CustomEvent('chartjsReady', { detail: { Chart } });
    document.dispatchEvent(event);
});
