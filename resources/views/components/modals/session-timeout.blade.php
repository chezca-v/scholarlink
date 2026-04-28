<!-- Session Timeout Warning Modal -->
<div id="session-timeout-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 32px; max-width: 400px; width: 90%; box-shadow: 0 20px 25px rgba(0, 0, 0, 0.15); animation: slideUp 0.3s ease-out;">
        <!-- Icon -->
        <div style="text-align: center; margin-bottom: 20px; font-size: 48px;">
            ⏱️
        </div>

        <!-- Title -->
        <h2 style="font-size: 20px; font-weight: 700; color: #1a2e2c; margin-bottom: 12px; text-align: center;">
            Session Expiring Soon
        </h2>

        <!-- Message -->
        <p style="font-size: 14px; color: #4a6460; line-height: 1.6; margin-bottom: 24px; text-align: center;">
            You've been inactive for 13 minutes. Your session will expire in 2 minutes for your security. Please click "Stay Logged In" to continue.
        </p>

        <!-- Button Group -->
        <div style="display: flex; gap: 12px;">
            <button 
                onclick="document.querySelector('[x-data]')?.__x.$data?.stayLoggedIn()" 
                style="flex: 1; padding: 12px 16px; background: linear-gradient(135deg, #1a6b63, #2a8a80); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; transition: background 0.2s ease;">
                Stay Logged In
            </button>
            <button 
                onclick="document.querySelector('[x-data]')?.__x.$data?.logout()" 
                style="flex: 1; padding: 12px 16px; background: #f5f5f5; color: #1a2e2c; border: 1px solid #e2e8e6; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; transition: background 0.2s ease;">
                Log Out
            </button>
        </div>

        <!-- Timer -->
        <div style="text-align: center; margin-top: 16px; font-size: 12px; color: #8aaba6;">
            Automatically logging out in <span id="session-timeout-countdown">2:00</span>
        </div>
    </div>
</div>

<style>
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #session-timeout-modal button:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    #session-timeout-modal {
        display: none !important;
    }

    #session-timeout-modal[style*="display: block"] {
        display: flex !important;
    }
</style>

<script>
    // Update countdown timer every second
    setInterval(() => {
        const modal = document.getElementById('session-timeout-modal');
        if (modal && modal.style.display !== 'none') {
            const countdownEl = document.getElementById('session-timeout-countdown');
            const text = countdownEl.textContent;
            const [minutes, seconds] = text.split(':').map(Number);
            let totalSeconds = minutes * 60 + seconds - 1;
            
            if (totalSeconds < 0) totalSeconds = 0;
            
            const newMinutes = Math.floor(totalSeconds / 60);
            const newSeconds = totalSeconds % 60;
            countdownEl.textContent = `${newMinutes}:${newSeconds.toString().padStart(2, '0')}`;
        }
    }, 1000);
</script>
