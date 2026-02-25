<div class="auth-container" style="max-width: 400px; margin: 50px auto; padding: 20px; background: #fff; box-shadow: var(--card-shadow); border-radius: 8px;">
    <h2 style="text-align: center; margin-bottom: 20px;">Login to StadiumTix</h2>
    <form action="/login-process" method="POST">
        <div style="margin-bottom: 15px;">
            <label for="email" style="display: block; margin-bottom: 5px;">Email Address</label>
            <input type="email" id="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 4px;">
        </div>
        <div style="margin-bottom: 20px;">
            <label for="password" style="display: block; margin-bottom: 5px;">Password</label>
            <input type="password" id="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 4px;">
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">Login</button>
    </form>
    <p style="text-align: center; margin-top: 15px; font-size: 0.9rem;">
        Don't have an account? <a href="/register" style="color: var(--primary-color);">Register here</a>
    </p>
</div>
