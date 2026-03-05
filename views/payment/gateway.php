<div class="payment-gateway-container" style="max-width: 500px; margin: 4rem auto;">
    <div class="glass-card" style="padding: 3rem;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
            <h2 class="gradient-text">Secure Payment</h2>
            <div style="font-size: 1.5rem; color: var(--primary);">
                <i class="fa-brands fa-stripe"></i>
            </div>
        </div>

        <div class="order-summary-mini glass" style="padding: 1.5rem; border-radius: var(--radius-md); margin-bottom: 2rem;">
            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.5rem;">Total Amount</p>
            <h1 style="font-size: 2.5rem; font-weight: 700;">$<?php echo number_format($orderDetails['total_amount'], 2); ?></h1>
            <p style="font-size: 0.8rem; margin-top: 1rem;">
                <i class="fa-solid fa-ticket"></i> <?php echo Security::clean($orderDetails['event_title']); ?><br>
                <i class="fa-solid fa-chair"></i> Row <?php echo Security::clean($orderDetails['row_number']); ?>, Seat <?php echo Security::clean($orderDetails['seat_number']); ?>
            </p>
        </div>

        <form action="/payment-process" method="POST" id="payment-form">
            <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
            <input type="hidden" name="order_id" value="<?php echo $orderDetails['id']; ?>">
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.8rem; text-transform: uppercase;">Card Details</label>
                <div class="glass" style="padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                    <input type="text" placeholder="1234 5678 1234 5678" style="background: transparent; border: none; color: white; width: 100%; outline: none;" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
                <div class="form-group">
                    <label style="display: block; margin-bottom: 0.5rem; font-size: 0.8rem;">Expiry</label>
                    <input type="text" placeholder="MM/YY" class="glass" style="width: 100%; padding: 0.8rem; border: 1px solid var(--border); border-radius: var(--radius-sm); color: white; background: transparent; outline: none;" required>
                </div>
                <div class="form-group">
                    <label style="display: block; margin-bottom: 0.5rem; font-size: 0.8rem;">CVC</label>
                    <input type="text" placeholder="123" class="glass" style="width: 100%; padding: 0.8rem; border: 1px solid var(--border); border-radius: var(--radius-sm); color: white; background: transparent; outline: none;" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-weight: 600;" id="pay-button">
                Pay Now
            </button>
            <p style="text-align: center; font-size: 0.7rem; color: var(--text-muted); margin-top: 1.5rem;">
                <i class="fa-solid fa-lock"></i> Encrypted & Secure Payment Simulation
            </p>
        </form>
    </div>
</div>

<script>
document.getElementById('payment-form').onsubmit = function() {
    const btn = document.getElementById('pay-button');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Processing...';
};
</script>
