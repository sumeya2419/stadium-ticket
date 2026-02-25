<div class="auth-container" style="max-width: 450px; margin: 50px auto; padding: 20px; background: #fff; box-shadow: var(--card-shadow); border-radius: 8px;">
    <h2 style="text-align: center; margin-bottom: 20px;">Create an Account</h2>
    <form action="/register-process" method="POST">
        <div style="margin-bottom: 15px;">
            <label for="name" style="display: block; margin-bottom: 5px;">Full Name</label>
            <input type="text" id="name" name="name" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 4px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label for="email" style="display: block; margin-bottom: 5px;">Email Address</label>
            <input type="email" id="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 4px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label for="phone" style="display: block; margin-bottom: 5px;">Phone Number</label>
            <input type="text" id="phone" name="phone" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 4px;">
        </div>
        <div style="margin-bottom: 20px;">
            <label for="password" style="display: block; margin-bottom: 5px;">Password</label>
            <input type="password" id="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 4px;">
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">Register</button>
    </form>
    <p style="text-align: center; margin-top: 15px; font-size: 0.9rem;">
        Already have an account? <a href="/login" style="color: var(--primary-color);">Login here</a>
    </p>
</div>
