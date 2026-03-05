<?php
$event_id = $_GET['event_id'];
?>

<div class="glass-card" style="padding: 2rem; margin-top: 2rem;">
    <h2 class="gradient-text" style="margin-bottom: 2rem;">Select Your Seat</h2>
    
    <div id="seat-map-container" style="display: flex; flex-direction: column; align-items: center; gap: 2rem;">
        <div class="stadium-screen" style="width: 80%; height: 20px; background: var(--bg-glass); border-radius: 50% / 100% 100% 0 0; text-align: center; font-size: 0.8rem; color: var(--text-muted); padding-top: 5px;">
            STADIUM FIELD / STAGE
        </div>
        
        <div id="seat-grid" style="display: grid; grid-template-columns: repeat(10, 1fr); gap: 10px; padding: 20px;">
            <!-- Seats will be loaded here via JS -->
            <div class="loading">Loading seats...</div>
        </div>

        <div class="seat-legend" style="display: flex; gap: 1.5rem; font-size: 0.9rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 15px; height: 15px; background: var(--bg-glass); border: 1px solid var(--border); border-radius: 3px;"></div> Available
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 15px; height: 15px; background: var(--accent); border-radius: 3px;"></div> Reserved/Pending
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 15px; height: 15px; background: var(--text-muted); border-radius: 3px;"></div> Occupied
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 15px; height: 15px; background: var(--primary); border-radius: 3px;"></div> Selected
            </div>
        </div>

        <div id="selection-summary" class="glass" style="width: 100%; padding: 1.5rem; border-radius: var(--radius-md); display: none;">
            <p>Selected Seat: <span id="selected-seat-name" style="font-weight: 600; color: var(--primary);">None</span></p>
            <form action="/checkout" method="POST" style="margin-top: 1rem;">
                <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">
                <input type="hidden" name="event_id" value="<?= \App\Core\Security::clean($event_id) ?>">
                <input type="hidden" name="seat_id" id="input-seat-id">
                <!-- For simplicity, we assume a default ticket type for now -->
                <input type="hidden" name="ticket_type_id" value="1"> 
                <input type="hidden" name="price" value="50.00">
                <button type="submit" class="btn btn-primary" style="width: 100%;">Proceed to Payment</button>
            </form>
        </div>
    </div>
</div>

<style>
.seat {
    width: 35px;
    height: 35px;
    background: var(--bg-glass);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    transition: var(--transition);
}

.seat:hover:not(.occupied):not(.reserved) {
    border-color: var(--primary);
    transform: scale(1.1);
}

.seat.selected {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.seat.occupied {
    background: var(--text-muted);
    cursor: not-allowed;
    opacity: 0.5;
}

.seat.reserved {
    background: var(--accent);
    cursor: not-allowed;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const eventId = '<?= \App\Core\Security::clean($event_id) ?>';
    const grid = document.getElementById('seat-grid');
    const summary = document.getElementById('selection-summary');
    const seatNameDisplay = document.getElementById('selected-seat-name');
    const inputSeatId = document.getElementById('input-seat-id');

    async function loadSeats() {
        try {
            const response = await fetch(`/api/seats?event_id=${eventId}`);
            const seats = await response.json();
            
            grid.innerHTML = '';
            seats.forEach(seat => {
                const seatDiv = document.createElement('div');
                seatDiv.className = `seat ${seat.current_status}`;
                seatDiv.textContent = `${seat.row_number}${seat.seat_number}`;
                seatDiv.dataset.id = seat.id;
                
                if (seat.current_status === 'available') {
                    seatDiv.onclick = () => selectSeat(seatDiv, seat);
                }
                
                grid.appendChild(seatDiv);
            });
        } catch (error) {
            console.error('Error loading seats:', error);
        }
    }

    async function selectSeat(element, seat) {
        // First try to reserve via API
        try {
            const response = await fetch('/api/reserve', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ seat_id: seat.id, event_id: eventId })
            });
            const result = await response.json();
            
            if (result.success) {
                // Remove previous selection
                document.querySelectorAll('.seat.selected').forEach(s => s.classList.remove('selected'));
                
                element.classList.add('selected');
                summary.style.display = 'block';
                seatNameDisplay.textContent = `Row ${seat.row_number}, Seat ${seat.seat_number}`;
                inputSeatId.value = seat.id;
            } else {
                alert('This seat was just reserved by someone else. Please pick another.');
                loadSeats(); // Refresh
            }
        } catch (error) {
            console.error('Reservation error:', error);
        }
    }

    loadSeats();
    // Refresh seat map every 30 seconds for real-time feel
    setInterval(loadSeats, 30000);
});
</script>
