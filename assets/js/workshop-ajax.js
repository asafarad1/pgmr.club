$(function() {
  // Handle workshop registration/unregistration via AJAX
  $('.workshop').on('click', '.apply-button.register, .apply-button.unregister', function(e) {
    e.preventDefault();
    
    // Get the form
    const $form = $(this).closest('form');
    const $workshop = $(this).closest('.workshop');
    const action = $form.find('input[name="action"]').val();
    const workshopId = $form.find('input[name="workshop_id"]').val();
    const emoji = $workshop.find('.workshop-emoji').data('emoji') || '✓';
    
    // Show loading state
    const $button = $(this);
    const originalButtonText = $button.find('h2').text();
    $button.find('h2').text();
    
    // Send AJAX request
    $.ajax({
      url: window.location.href,
      type: 'POST',
      data: {
        action: action,
        workshop_id: workshopId
      },
      success: function(response) {
        // Update UI based on action
        if (action === 'register') {
          $workshop.addClass('registered');
          
          // Replace the button with unregister button
          const unregisterButton = 
            `<form method="post">
              <input type="hidden" name="action" value="unregister">
              <input type="hidden" name="workshop_id" value="${workshopId}">
              <button type="submit" class="apply-button unregister">
                <h2>נרשמתי &nbsp;${emoji}</h2>
              </button>
            </form>`;
          
          $form.replaceWith(unregisterButton);
          
        } else if (action === 'unregister') {
          $workshop.removeClass('registered');
          
          // Replace the button with register button
          const registerButton = 
            `<form method="post">
              <input type="hidden" name="action" value="register">
              <input type="hidden" name="workshop_id" value="${workshopId}">
              <button type="submit" class="apply-button register">
                <h2>הרשמה</h2>
              </button>
            </form>`;
          
          $form.replaceWith(registerButton);
        }
      },
      error: function(xhr, status, error) {
        // Handle errors
        console.error('Error:', error);
        alert('שגיאה');
        $button.find('h2').text(originalButtonText);
      }
    });
  });
});
