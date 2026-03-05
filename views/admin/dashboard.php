<div class="admin-dashboard" style="padding-top: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
        <div>
            <h1 class="gradient-text">Command Center</h1>
            <p style="color: var(--text-muted); margin-top: 0.5rem;">Managing StadiumPass Intelligence & Operations</p>
        </div>
        <div style="display: flex; gap: 1rem;">
            <button class="btn btn-secondary" onclick="document.getElementById('modal-venue').style.display='flex'">
                <i class="fa-solid fa-plus"></i> Add Venue
            </button>
            <button class="btn btn-primary" onclick="document.getElementById('modal-event').style.display='flex'">
                <i class="fa-solid fa-calendar-plus"></i> Create Event
            </button>
        </div>
    </div>

    <!-- KPI Section -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 2rem; margin-bottom: 4rem;">
        <div class="glass-card" style="padding: 2rem; border-left: 4px solid var(--primary);">
            <p style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Total Revenue</p>
            <h2 style="font-size: 2.5rem; margin-top: 0.5rem;">$<?php echo number_format($stats['total_revenue'], 2); ?></h2>
            <p style="font-size: 0.75rem; color: var(--success); margin-top: 1rem;"><i class="fa-solid fa-arrow-up"></i> 12% vs last month</p>
        </div>
        <div class="glass-card" style="padding: 2rem; border-left: 4px solid var(--accent);">
            <p style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Tickets Sold</p>
            <h2 style="font-size: 2.5rem; margin-top: 0.5rem;"><?php echo $stats['tickets_sold']; ?></h2>
            <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 1rem;">Out of <?php echo $stats['total_capacity']; ?> total capacity</p>
        </div>
        <div class="glass-card" style="padding: 2rem; border-left: 4px solid #f39c12;">
            <p style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Occupancy Rate</p>
            <?php 
                $rate = ($stats['total_capacity'] > 0) ? ($stats['tickets_sold'] / $stats['total_capacity']) * 100 : 0;
            ?>
            <h2 style="font-size: 2.5rem; margin-top: 0.5rem;"><?php echo round($rate, 1); ?>%</h2>
            <div style="width: 100%; height: 6px; background: var(--bg-glass); border-radius: 3px; margin-top: 1.5rem;">
                <div style="width: <?php echo $rate; ?>%; height: 100%; background: #f39c12; border-radius: 3px;"></div>
            </div>
        </div>
        <div class="glass-card" style="padding: 2rem; border-left: 4px solid var(--text-secondary);">
            <p style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Active Events</p>
            <h2 style="font-size: 2.5rem; margin-top: 0.5rem;"><?php echo $stats['active_events']; ?></h2>
            <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 1rem;">Scheduled for this month</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem;">
        <!-- Left: Event Management -->
        <div>
            <div class="glass-card" style="padding: 2rem;">
                <h3 style="margin-bottom: 2rem; font-size: 1.2rem;">Live Events</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: left; border-bottom: 1px solid var(--border);">
                            <th style="padding: 1rem 0; font-size: 0.8rem; color: var(--text-muted);">Event</th>
                            <th style="padding: 1rem 0; font-size: 0.8rem; color: var(--text-muted);">Venue</th>
                            <th style="padding: 1rem 0; font-size: 0.8rem; color: var(--text-muted);">Date</th>
                            <th style="padding: 1rem 0; font-size: 0.8rem; color: var(--text-muted);">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($events as $event): ?>
                        <tr style="border-bottom: 1px solid var(--bg-glass);">
                            <td style="padding: 1.5rem 0; font-weight: 600;"><?php echo Security::clean($event['title']); ?></td>
                            <td style="padding: 1.5rem 0; color: var(--text-secondary); font-size: 0.9rem;"><?php echo Security::clean($event['venue_name']); ?></td>
                            <td style="padding: 1.5rem 0; color: var(--text-secondary); font-size: 0.9rem;"><?php echo $event['event_date']; ?></td>
                            <td style="padding: 1.5rem 0;">
                                <span class="badge" style="background: <?php echo $event['status'] == 'scheduled' ? 'var(--success)' : 'var(--text-muted)'; ?>; font-size: 0.65rem;">
                                    <?php echo strtoupper($event['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Activity Feed -->
        <div>
            <div class="glass-card" style="padding: 2rem;">
                <h3 style="margin-bottom: 2rem; font-size: 1.2rem;">Recent Activity</h3>
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <?php foreach($activities as $activity): ?>
                    <div style="display: flex; gap: 1rem; align-items: flex-start; padding-bottom: 1.5rem; border-bottom: 1px solid var(--bg-glass);">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0; opacity: 0.8;">
                            <i class="fa-solid <?php echo $activity['status'] == 'paid' ? 'fa-cart-shopping' : 'fa-clock'; ?>" style="font-size: 0.9rem;"></i>
                        </div>
                        <div>
                            <p style="font-size: 0.85rem; font-weight: 600;">
                                <?php echo Security::clean($activity['user_name']); ?> 
                                <span style="font-weight: 400; color: var(--text-muted);">purchased ticket for</span>
                                <?php echo Security::clean($activity['event_title']); ?>
                            </p>
                            <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.3rem;">
                                $<?php echo number_format($activity['total_amount'], 2); ?> • <?php echo date('H:i', strtotime($activity['created_at'])); ?>
                            </p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button class="btn btn-secondary" style="width: 100%; margin-top: 2rem; font-size: 0.8rem;">View All Transactions</button>
            </div>
        </div>
    </div>
</div>

<!-- Simple Modals (Internal CSS/JS for brevity) -->
<style>
.modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); display: none; align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(10px); }
.modal-content { background: var(--bg-dark); border: 1px solid var(--border); padding: 3rem; border-radius: var(--radius-lg); width: 100%; max-width: 500px; }
.form-input { width: 100%; padding: 1rem; background: var(--bg-glass); border: 1px solid var(--border); border-radius: var(--radius-sm); color: white; margin-bottom: 1.5rem; }
</style>

<!-- Add Venue Modal -->
<div id="modal-venue" class="modal">
    <div class="modal-content glass">
        <h2 class="gradient-text" style="margin-bottom: 2rem;">New Venue</h2>
        <form action="/admin/venue/create" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
            <input type="text" name="name" placeholder="Venue Name" class="form-input" required>
            <input type="text" name="location" placeholder="Location" class="form-input" required>
            <input type="number" name="capacity" placeholder="Total Capacity" class="form-input" required>
            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Create Venue</button>
                <button type="button" class="btn btn-secondary" onclick="this.closest('.modal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Create Event Modal -->
<div id="modal-event" class="modal">
    <div class="modal-content glass">
        <h2 class="gradient-text" style="margin-bottom: 2rem;">Create Event</h2>
        <form action="/admin/event/create" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
            <label style="font-size: 0.8rem; color: var(--text-muted);">Event Title</label>
            <input type="text" name="title" class="form-input" required>
            
            <label style="font-size: 0.8rem; color: var(--text-muted);">Select Venue</label>
            <select name="venue_id" class="form-input" required>
                <?php foreach($venues as $v): ?>
                    <option value="<?php echo $v['id']; ?>"><?php echo Security::clean($v['name']); ?></option>
                <?php endforeach; ?>
            </select>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="font-size: 0.8rem; color: var(--text-muted);">Date</label>
                    <input type="date" name="event_date" class="form-input" required>
                </div>
                <div>
                    <label style="font-size: 0.8rem; color: var(--text-muted);">Time</label>
                    <input type="time" name="start_time" class="form-input" required>
                </div>
            </div>

            <h4 style="margin: 1rem 0; font-size: 0.9rem; opacity: 0.8;">Initial Ticket Category</h4>
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                <input type="text" name="ticket_name" placeholder="VIP / General" class="form-input">
                <input type="number" name="ticket_price" placeholder="Price" class="form-input">
            </div>
            <input type="number" name="ticket_quantity" placeholder="Quantity Available" class="form-input">

            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Schedule Event</button>
                <button type="button" class="btn btn-secondary" onclick="this.closest('.modal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>
