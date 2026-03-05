<div class="invoice-container" style="max-width: 800px; margin: 4rem auto;">
    <div class="glass-card" style="padding: 4rem; position: relative; overflow: hidden;">
        <!-- Decorative Elements -->
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: var(--primary); opacity: 0.1; border-radius: 50%; filter: blur(40px);"></div>
        
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4rem; position: relative;">
            <div>
                <h1 class="gradient-text" style="font-size: 2.5rem; margin-bottom: 0.5rem;">StadiumPass</h1>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Official Match Ticket & Receipt</p>
            </div>
            <div style="text-align: right;">
                <h2 style="font-size: 1.2rem; margin-bottom: 0.5rem;">INVOICE #STP-<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></h2>
                <p style="color: var(--text-muted); font-size: 0.8rem;">Issued on: <?php echo date('F d, Y'); ?></p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; margin-bottom: 4rem;">
            <div>
                <h4 style="text-transform: uppercase; letter-spacing: 1px; font-size: 0.75rem; color: var(--primary); margin-bottom: 1rem;">Customer Details</h4>
                <p style="font-weight: 600;"><?php echo Security::clean($_SESSION['user_name'] ?? 'Valued Customer'); ?></p>
                <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem;">Digital Purchase</p>
            </div>
            <div>
                <h4 style="text-transform: uppercase; letter-spacing: 1px; font-size: 0.75rem; color: var(--primary); margin-bottom: 1rem;">Event Information</h4>
                <p style="font-weight: 600;"><?php echo Security::clean($order['event_title']); ?></p>
                <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem;">
                    <?php echo Security::clean($order['venue_name']); ?><br>
                    <?php echo Security::clean($order['event_date']); ?> @ <?php echo Security::clean($order['start_time']); ?>
                </p>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 4rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th style="padding: 1rem 0; text-align: left; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Description</th>
                    <th style="padding: 1rem 0; text-align: center; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Seat</th>
                    <th style="padding: 1rem 0; text-align: right; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 2rem 0;">
                        <p style="font-weight: 600;">Match Ticket</p>
                        <p style="font-size: 0.8rem; color: var(--text-muted);">Standard Entry</p>
                    </td>
                    <td style="padding: 2rem 0; text-align: center;">
                        <span class="badge" style="background: var(--bg-glass); border: 1px solid var(--border);">
                            Row <?php echo Security::clean($order['row_number']); ?>, Seat <?php echo Security::clean($order['seat_number']); ?>
                        </span>
                    </td>
                    <td style="padding: 2rem 0; text-align: right; font-weight: 600;">$<?php echo number_format($order['total_amount'], 2); ?></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="padding: 2rem 0; text-align: right; color: var(--text-muted);">Total Paid</td>
                    <td style="padding: 2rem 0; text-align: right; font-size: 1.5rem; font-weight: 700; color: var(--primary);">$<?php echo number_format($order['total_amount'], 2); ?></td>
                </tr>
            </tfoot>
        </table>

        <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 3rem; border-top: 1px dashed var(--border);">
            <div style="max-width: 60%;">
                <h4 style="font-size: 0.9rem; margin-bottom: 1rem;">Ticket Verification</h4>
                <p style="font-size: 0.8rem; color: var(--text-muted); line-height: 1.6;">
                    Please present this digital invoice or the QR code at the stadium gate. This ticket is non-transferable and subject to stadium terms and conditions.
                </p>
            </div>
            <div style="text-align: center;">
                <div class="glass" style="padding: 1rem; border-radius: var(--radius-md); background: white; margin-bottom: 0.5rem;">
                    <!-- Simulated complex QR code using CSS grid -->
                    <div style="width: 100px; height: 100px; display: grid; grid-template-columns: repeat(10, 1fr); gap: 2px;">
                        <?php for($i=0; $i<100; $i++): ?>
                            <div style="background: <?php echo rand(0,1) ? '#000' : '#fff'; ?>;"></div>
                        <?php endfor; ?>
                    </div>
                </div>
                <p style="font-size: 0.6rem; color: var(--text-muted); font-family: monospace;">HASH: <?php echo substr($order['qr_code'], 0, 16); ?>...</p>
            </div>
        </div>

        <div style="margin-top: 4rem; text-align: center;">
            <button onclick="window.print()" class="btn btn-secondary" style="padding: 0.8rem 2rem;">
                <i class="fa-solid fa-print"></i> Print Invoice
            </button>
        </div>
    </div>
</div>
