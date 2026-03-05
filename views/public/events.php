<div class="events-container" style="padding-top: 3rem;">
    <h1 class="gradient-text" style="text-align:center; margin-bottom:4rem; font-size: 3rem;">Epic Events Await</h1>
    
    <div style="display:flex; flex-wrap:wrap; gap:3rem; justify-content:center;">
        <?php foreach ($events as $event): ?>
            <?php if($event['status'] == 'scheduled'): ?>
                <div class="glass-card" style="width: 350px; padding: 2rem;">
                    <h3 class="gradient-text"><?php echo \App\Core\Security::clean($event['title']); ?></h3>
                    <p style="color: var(--text-secondary); margin: 1rem 0;">
                        <i class="fa-solid fa-location-dot"></i> <?php echo \App\Core\Security::clean($event['venue_name']); ?><br>
                        <i class="fa-solid fa-calendar"></i> <?php echo \App\Core\Security::clean($event['event_date']); ?> @ <?php echo \App\Core\Security::clean($event['start_time']); ?>
                    </p>
                    
                    <div class="glass" style="padding: 1rem; border-radius: var(--radius-md);">
                        <h4 style="font-size: 0.9rem; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px;">Pricing</h4>
                        <?php if (isset($ticketsByEvent[$event['id']])): ?>
                            <?php foreach($ticketsByEvent[$event['id']] as $ticket): ?>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                    <span style="font-size: 0.9rem;"><?php echo \App\Core\Security::clean($ticket['name']); ?></span>
                                    <span style="font-weight: 700; color: var(--primary);">$<?php echo $ticket['price']; ?></span>
                                </div>
                            <?php endforeach; ?>
                            <a href="/select-seats?event_id=<?php echo $event['id']; ?>" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                                Select Seats
                            </a>
                        <?php else: ?>
                            <p style="font-size: 0.8rem; color: var(--text-muted);">No tickets available yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php if(empty($events)): ?>
            <p>No events are currently scheduled.</p>
        <?php endif; ?>
    </div>
</div>
