<!DOCTYPE html>
<html>
<head>
    <style>
        .success { color: green; background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .error { color: red; background: #f8d7da; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .info { background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 20px 0; }
        pre { background: #f5f5f5; padding: 15px; border: 1px solid #ddd; overflow-x: auto; }
        .config-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .config-table td { padding: 8px; border: 1px solid #ddd; }
        .config-table td:first-child { font-weight: bold; background: #f9f9f9; }
    </style>
</head>
<body style="max-width: 900px; margin: 50px auto; padding: 20px; font-family: Arial, sans-serif;">
    <h2>Email Configuration Test</h2>
    
    <div class="info">
        <h3>Current SMTP Configuration:</h3>
        <table class="config-table">
            <tr><td>Protocol</td><td><?php echo $smtp_config['protocol']; ?></td></tr>
            <tr><td>SMTP Host</td><td><?php echo $smtp_config['host']; ?></td></tr>
            <tr><td>SMTP User</td><td><?php echo $smtp_config['user']; ?></td></tr>
            <tr><td>SMTP Port</td><td><?php echo $smtp_config['port']; ?></td></tr>
            <tr><td>SMTP Crypto</td><td><?php echo $smtp_config['crypto']; ?></td></tr>
            <tr><td>Test Email To</td><td><?php echo htmlspecialchars($test_email); ?></td></tr>
        </table>
    </div>

    <hr>

<?php if ($email_sent): ?>
    <div class="success">
        <h3>✓ SUCCESS! Email sent successfully!</h3>
        <p>Check your inbox (and spam folder) at: <strong><?php echo htmlspecialchars($test_email); ?></strong></p>
        <p>If you don't see it, check your spam/junk folder. It may take a few minutes to arrive.</p>
    </div>
<?php else: ?>
    <div class="error">
        <h3>✗ FAILED! Email could not be sent.</h3>
        <h4>Debug Information:</h4>
        <pre><?php echo htmlspecialchars($email_debug); ?></pre>
        
        <h4>Common Issues & Solutions:</h4>
        <ul>
            <li><strong>2-Step Verification:</strong> Make sure it's enabled on glassifytesting@gmail.com</li>
            <li><strong>App Password:</strong> Verify the 16-character app password is correct (no spaces)</li>
            <li><strong>App Password Account:</strong> Make sure the app password was generated for glassifytesting@gmail.com</li>
            <li><strong>Firewall:</strong> Check if your firewall/antivirus is blocking port 587</li>
            <li><strong>Internet:</strong> Verify you have an active internet connection</li>
            <li><strong>Gmail Security:</strong> Check if Gmail has blocked the login attempt (check Gmail security settings)</li>
        </ul>
        
        <h4>Quick Fixes to Try:</h4>
        <ol>
            <li>Go to <a href="https://myaccount.google.com/apppasswords" target="_blank">Google App Passwords</a></li>
            <li>Generate a NEW app password for glassifytesting@gmail.com</li>
            <li>Update the password in: <code>application/config/email.php</code></li>
            <li>Try this test again</li>
        </ol>
    </div>
<?php endif; ?>

    <hr>
    <h3>Test with Different Email Address:</h3>
    <form method="get" action="<?php echo base_url('test-email'); ?>" style="margin-top: 10px;">
        <input type="email" name="email" value="<?php echo htmlspecialchars($test_email); ?>" 
               placeholder="Enter email address" style="padding: 8px; width: 300px;" required>
        <button type="submit" style="padding: 8px 15px; background: #083c5d; color: white; border: none; border-radius: 3px; cursor: pointer;">Test Email</button>
    </form>
    
    <hr>
    <p>
        <a href="<?php echo base_url('projects'); ?>">← Back to Projects Page</a>
    </p>
</body>
</html>

