<div class="customer-dashboard" style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <h2>My Dashboard</h2>
    <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>!</p>
    
    <div style="margin-top: 30px;">
        <a href="/events" class="btn btn-primary">Browse More Events</a>
    </div>

    <h3 style="margin-top: 40px; border-bottom: 2px solid var(--border); padding-bottom: 10px;">My Tickets</h3>
    
    <?php if (empty($myOrders)): ?>
        <p style="margin-top: 20px;">You haven't bought any tickets yet.</p>
    <?php else: ?>
        <ul style="list-style: none; padding: 0; margin-top: 20px;">
            <?php foreach($myOrders as $order): ?>
                <li style="background: #fff; margin-bottom: 15px; padding: 20px; border-radius: 8px; box-shadow: var(--card-shadow); display: flex; align-items: center; justify-content: space-between;">
                    
                    <div>
                        <h4 style="color: var(--primary-color); margin-bottom: 5px;"><?php echo htmlspecialchars($order['event_title']); ?></h4>
                        <p style="font-size: 0.9rem; margin-bottom: 5px;"><strong>Date:</strong> <?php echo $order['event_date']; ?></p>
                        <p style="font-size: 0.9rem;"><strong>Status:</strong> <span style="color: var(--success); text-transform: uppercase;"> <?php echo $order['status']; ?></span> - Paid $<?php echo $order['price']; ?></p>
                        
                        <?php if($order['is_used']): ?>
                            <p style="margin-top: 5px; color: var(--danger); font-weight: bold; font-size: 0.9rem;">[TICKET USED]</p>
                        <?php endif; ?>
                    </div>
                    
                    <div style="text-align: center; border-left: 1px dashed var(--border); padding-left: 20px;">
                       <span style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 5px;">QR Code Token</span>
                       <!-- We simulate a QR code visually using an external API for the generated token -->
                       <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?php echo $order['qr_code']; ?>" alt="Ticket QR" style="border: 1px solid var(--border); border-radius: 4px; <?php echo $order['is_used'] ? 'opacity: 0.5;' : ''; ?>">
                       <p style="font-size: 0.65rem; color: #888; max-width: 100px; word-wrap: break-word; margin-top: 5px;"><?php echo substr($order['qr_code'], 0, 15) . '...'; ?></p>
                    </div>

                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
