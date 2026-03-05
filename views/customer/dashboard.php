<div class="dashboard-container" style="padding-top: 3rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4rem;">
        <div>
            <h1 class="gradient-text">My Dashboard</h1>
            <p style="color: var(--text-muted); margin-top: 0.5rem;">Welcome back, <strong><?php echo Security::clean($_SESSION['user_name']); ?></strong></p>
        </div>
        <a href="/events" class="btn btn-secondary">
            <i class="fa-solid fa-plus"></i> Get More Tickets
        </a>
    </div>

    <div class="tickets-section">
        <h2 style="margin-bottom: 2rem; font-size: 1.5rem; letter-spacing: 1px;">MY TICKETS</h2>
        
        <?php if (empty($myOrders)): ?>
            <div class="glass-card" style="padding: 6rem 2rem; text-align: center;">
                <div style="font-size: 4rem; color: var(--bg-glass); margin-bottom: 2rem;">
                    <i class="fa-solid fa-ticket-simple"></i>
                </div>
                <h3 style="margin-bottom: 1rem;">No Tickets Found</h3>
                <p style="color: var(--text-muted); margin-bottom: 3rem; max-width: 400px; margin-left: auto; margin-right: auto;">
                    You haven't reserved any match tickets yet. Browse our upcoming events to secure your spot in the stadium!
                </p>
                <a href="/events" class="btn btn-primary" style="padding: 1rem 3rem;">
                    Browse Matches
                </a>
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 2rem;">
                <?php foreach($myOrders as $order): ?>
                    <div class="glass-card" style="padding: 2rem; position: relative; display: flex; flex-direction: column; justify-content: space-between;">
                        <?php if($order['is_used']): ?>
                            <div class="badge" style="position: absolute; top: 1rem; right: 1rem; background: var(--danger); font-size: 0.7rem;">USED</div>
                        <?php endif; ?>

                        <div>
                            <h3 style="margin-bottom: 1rem; color: var(--primary);">
                                <?php echo Security::clean($order['event_title']); ?>
                            </h3>
                            <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 1.5rem;">
                                <i class="fa-solid fa-calendar"></i> <?php echo Security::clean($order['event_date']); ?>
                            </p>
                            
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                                <div>
                                    <p style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase;">Status</p>
                                    <span style="font-size: 0.8rem; font-weight: 700; color: <?php echo $order['status'] == 'paid' ? 'var(--success)' : 'var(--accent)'; ?>; text-transform: uppercase;">
                                        <?php echo Security::clean($order['status']); ?>
                                    </span>
                                </div>
                                <div style="text-align: right;">
                                    <p style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase;">Price</p>
                                    <span style="font-weight: 600;">$<?php echo number_format($order['price'], 2); ?></span>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                            <?php if ($order['status'] == 'pending'): ?>
                                <a href="/payment?order_id=<?php echo $order['order_id']; ?>" class="btn btn-primary" style="width: 100%;">
                                    <i class="fa-solid fa-credit-card"></i> Pay Now
                                </a>
                            <?php else: ?>
                                <div style="display: flex; gap: 1rem; align-items: center;">
                                    <div style="flex-shrink: 0; background: white; padding: 5px; border-radius: 4px;">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=<?php echo urlencode($order['qr_code']); ?>" alt="QR" style="display: block; width: 60px; height: 60px;">
                                    </div>
                                    <div style="flex-grow: 1;">
                                        <a href="/invoice?order_id=<?php echo $order['order_id']; ?>" class="btn btn-secondary" style="width: 100%; font-size: 0.8rem;">
                                            <i class="fa-solid fa-receipt"></i> View Invoice
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
