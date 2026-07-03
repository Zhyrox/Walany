<div class="registration-container" style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2>Event Registration Form</h2>
    <p>Please fill out your details to reserve your slot and generate your unique reference ID token.</p>

    <!-- The form submits to the Registrants module action processor -->
    <form action="/PHP_Project/Walany/index.php?module=Registrants&action=submit_registration" method="POST" style="background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #eee;">
        
        <!-- Crucial: Capture the incoming event_id from the landing page click -->
        <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($_GET['event_id'] ?? '1'); ?>">

        <div style="margin-bottom: 15px; display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <div>
                <label for="first_name" style="display: block; font-weight: bold; margin-bottom: 5px;">First Name</label>
                <input type="text" id="first_name" name="first_name" required style="width: 100%; padding: 8px; box-sizing: border-box;">
            </div>
            <div>
                <label for="last_name" style="display: block; font-weight: bold; margin-bottom: 5px;">Last Name</label>
                <input type="text" id="last_name" name="last_name" required style="width: 100%; padding: 8px; box-sizing: border-box;">
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="middle_name" style="display: block; font-weight: bold; margin-bottom: 5px;">Middle Name <span style="font-weight: normal; color: #888;">(Optional)</span></label>
            <input type="text" id="middle_name" name="middle_name" style="width: 100%; padding: 8px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="email" style="display: block; font-weight: bold; margin-bottom: 5px;">Email Address</label>
            <input type="email" id="email" name="email" required style="width: 100%; padding: 8px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="phone_number" style="display: block; font-weight: bold; margin-bottom: 5px;">Contact Number</label>
            <input type="text" id="contact_number" name="contact_number" required placeholder="e.g., 09123456789" style="width: 100%; padding: 8px; box-sizing: border-box;">
        </div>

        <button type="submit" style="background: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%;">Complete Registration</button>
    </form>
</div>