<div class="admin-dashboard">
    <h2>Admin Dashboard</h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div style="background:#d4edda; color:#155724; padding:10px; margin-bottom:20px; border-radius:4px;">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div style="background:#f8d7da; color:#721c24; padding:10px; margin-bottom:20px; border-radius:4px;">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div style="display: flex; gap: 20px; margin-top: 20px;">
        <!-- VENUE MANAGEMENT -->
        <div style="flex: 1; background: #fff; padding: 20px; border-radius: 8px; box-shadow: var(--card-shadow);">
            <h3>Create a Venue</h3>
            <form action="/admin/venue/create" method="POST">
                <div style="margin-bottom: 10px;">
                    <label>Venue Name</label>
                    <input type="text" name="name" required style="width: 100%; padding: 8px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label>Location</label>
                    <input type="text" name="location" required style="width: 100%; padding: 8px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label>Capacity</label>
                    <input type="number" name="capacity" required style="width: 100%; padding: 8px;">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Add Venue</button>
            </form>

            <h4 style="margin-top: 20px;">Existing Venues</h4>
            <ul style="list-style: none; padding: 0;">
                <?php foreach ($venues as $v): ?>
                    <li style="padding: 10px; border-bottom: 1px solid var(--border);">
                        <strong><?php echo htmlspecialchars($v['name']); ?></strong> (<?php echo htmlspecialchars($v['location']); ?>) - Capacity: <?php echo $v['capacity']; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- EVENT MANAGEMENT -->
        <div style="flex: 1; background: #fff; padding: 20px; border-radius: 8px; box-shadow: var(--card-shadow);">
            <h3>Create an Event</h3>
            <form action="/admin/event/create" method="POST">
                <div style="margin-bottom: 10px;">
                    <label>Event Title</label>
                    <input type="text" name="title" required style="width: 100%; padding: 8px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label>Select Venue</label>
                    <select name="venue_id" required style="width: 100%; padding: 8px;">
                        <option value="">-- Choose a Venue --</option>
                        <?php foreach ($venues as $v): ?>
                            <option value="<?php echo $v['id']; ?>"><?php echo htmlspecialchars($v['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                    <div style="flex: 1;">
                        <label>Date</label>
                        <input type="date" name="event_date" required style="width: 100%; padding: 8px;">
                    </div>
                    <div style="flex: 1;">
                        <label>Time</label>
                        <input type="time" name="start_time" required style="width: 100%; padding: 8px;">
                    </div>
                </div>

                <hr style="margin: 15px 0;">
                <h4>Initial Ticket Type</h4>
                <div style="margin-bottom: 10px;">
                    <label>Ticket Name (e.g. General)</label>
                    <input type="text" name="ticket_name" required style="width: 100%; padding: 8px;">
                </div>
                <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                    <div style="flex: 1;">
                        <label>Price</label>
                        <input type="number" step="0.01" name="ticket_price" required style="width: 100%; padding: 8px;">
                    </div>
                    <div style="flex: 1;">
                        <label>Quantity Available</label>
                        <input type="number" name="ticket_quantity" required style="width: 100%; padding: 8px;">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; background: var(--success);">Publish Event</button>
            </form>

             <h4 style="margin-top: 20px;">Existing Events</h4>
            <ul style="list-style: none; padding: 0;">
                <?php foreach ($events as $e): ?>
                    <li style="padding: 10px; border-bottom: 1px solid var(--border);">
                        <strong><?php echo htmlspecialchars($e['title']); ?></strong><br>
                        <small><?php echo $e['event_date'] . ' ' . $e['start_time']; ?> | Venue: <?php echo htmlspecialchars($e['venue_name']); ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
