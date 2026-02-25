<div style="max-width: 600px; margin: 50px auto; text-align: center;">
    <h2>Staff Device - Scan Ticket</h2>
    <p style="color: var(--text-muted); margin-bottom: 30px;">Use your physical scanner to input the QR code hash, or paste the token directly below.</p>

    <!-- Scanner Form Simulator -->
    <div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: var(--card-shadow); margin-bottom: 30px;">
        <form action="/staff/scan-process" method="POST">
            <input type="text" name="qr_code" placeholder="Scan or paste QR Hash Token here..." required autofocus autocomplete="off" style="width: 100%; padding: 15px; font-size: 1.1em; border: 2px solid var(--border); border-radius: 5px; margin-bottom: 20px; text-align: center;">
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 1.1em;">Verify Ticket</button>
        </form>
    </div>

    <!-- Scan Result Visual Feedback -->
    <?php if (isset($_SESSION['scan_result'])): ?>
        <?php 
            $res = $_SESSION['scan_result']; 
            $bgColor = '#f8d7da'; // default invalid/red
            $textColor = '#721c24';
            $icon = '❌';

            if ($res['status'] == 'valid') {
                $bgColor = '#d4edda';
                $textColor = '#155724';
                $icon = '✅';
            } elseif ($res['status'] == 'already_used') {
                $bgColor = '#fff3cd';
                $textColor = '#856404';
                $icon = '⚠️';
            }
        ?>
        <div style="background: <?php echo $bgColor; ?>; color: <?php echo $textColor; ?>; padding: 25px; border-radius: 8px; font-size: 1.2rem; font-weight: bold; box-shadow: var(--card-shadow);">
            <div style="font-size: 3rem; margin-bottom: 10px;"><?php echo $icon; ?></div>
            <?php echo htmlspecialchars($res['message']); ?>
        </div>
        <?php unset($_SESSION['scan_result']); ?>
    <?php endif; ?>
</div>
