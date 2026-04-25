document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardLogic', () => ({
        lastRefreshed: 'Just now',

        // This function simulates the "Auto-refreshes every 5 min" from your design
        refreshStats() {
            console.log('Fetching live DB counts for Admin...');
            this.lastRefreshed = new Date().toLocaleTimeString();
        },

        init() {
            // Set interval to refresh dashboard data every 5 minutes
            setInterval(() => this.refreshStats(), 300000);
        }
    }))
});
