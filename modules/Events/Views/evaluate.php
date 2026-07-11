<div class="evaluation-container" style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2>Event Evaluation & Feedback</h2>
    <p>Please provide your unique registration reference ID code to submit your rating metrics.</p>

    <form action="/PHP_Project/Walany/index.php?module=Events&action=submit_evaluation" method="POST" style="background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #eee;">
        <!-- Hidden input tracking the specific event target index -->
        <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($_GET['event_id'] ?? '1'); ?>">

        <div style="margin-bottom: 15px;">
            <label for="reference_id" style="display: block; font-weight: bold; margin-bottom: 5px;">Your Registration Reference ID</label>
            <input type="text" id="reference_id" name="reference_id" required
                placeholder="e.g., ABCD-1234" maxlength="9" style="width: 100%; padding: 8px; box-sizing: border-box; text-transform: uppercase;">
            <small style="color: #777;">Provide the 8-character token code generated when you registered.</small>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="rating_metric" style="display: block; font-weight: bold; margin-bottom: 5px;">Overall Rating</label>
            <select id="rating_metric" name="rating_metric" required style="width: 100%; padding: 8px;">
                <option value="">-- Select Rating --</option>
                <option value="5">⭐⭐⭐⭐⭐ 5 - Excellent</option>
                <option value="4">⭐⭐⭐⭐ 4 - Very Good</option>
                <option value="3">⭐⭐⭐ 3 - Good</option>
                <option value="2">⭐⭐ 2 - Fair</option>
                <option value="1">⭐ 1 - Poor</option>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="feedback_text" style="display: block; font-weight: bold; margin-bottom: 5px;">Comments & Suggestions</label>
            <textarea id="feedback_text" name="feedback_text" rows="5" required 
                    placeholder="Share your thoughts about the event experience..." style="width: 100%; padding: 8px; box-sizing: border-box;"></textarea>
        </div>

        <button type="submit" style="background: #28a745; color: #fff; border: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; cursor: pointer;">Submit Feedback</button>
    </form>
</div>