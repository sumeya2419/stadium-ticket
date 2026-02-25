<div class="events-container">
    <h2 style="text-align:center; margin-bottom:30px;">Available Events</h2>
    
    <div style="display:flex; flex-wrap:wrap; gap:20px; justify-content:center;">
        <?php foreach ($events as $event): ?>
            <?php if($event['status'] == 'scheduled'): ?>
                <div style="width: 300px; background: #fff; border-radius: 8px; box-shadow: var(--card-shadow); padding: 20px;">
                    <h3 style="color: var(--primary-color);"><?php echo htmlspecialchars($event['title']); ?></h3>
                    <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue_name']); ?></p>
                    <p><strong>Date/Time:</strong> <?php echo $event['event_date'] . ' @ ' . $event['start_time']; ?></p>
                    <hr style="margin: 15px 0;">
                    
                    <h4>Tickets Available</h4>
                    <?php if (isset($ticketsByEvent[$event['id']])): ?>
                        <?php foreach($ticketsByEvent[$event['id']] as $ticket): ?>
                            <div style="margin-bottom: 10px; padding: 10px; background: var(--light-bg); border-radius: 4px;">
                                <p><?php echo htmlspecialchars($ticket['name']); ?> - <strong>$<?php echo $ticket['price']; ?></strong></p>
                                <p style="font-size: 0.85rem; color: var(--text-muted);">Remaining: <?php echo $ticket['quantity_available']; ?></p>
                                
                                <?php if($ticket['quantity_available'] > 0): ?>
                                    <form action="/checkout" method="POST" style="margin-top:10px;">
                                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                        <input type="hidden" name="ticket_type_id" value="<?php echo $ticket['id']; ?>">
                                        <input type="hidden" name="price" value="<?php echo $ticket['price']; ?>">
                                        <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 0.9em; padding: 8px;" <?php echo !isset($_SESSION['user_id']) ? 'disabled' : ''; ?>>
                                            <?php echo !isset($_SESSION['user_id']) ? 'Login to Buy' : 'Buy Now (Mock)'; ?>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span style="color: var(--danger); font-weight: bold;">Sold Out</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No tickets listed yet.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php if(empty($events)): ?>
            <p>No events are currently scheduled.</p>
        <?php endif; ?>
    </div>
</div>
