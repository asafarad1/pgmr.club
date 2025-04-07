$(function() {
    // Function to check if a date has passed
    function hasDatePassed(dateString) {
      // Parse the date string in format YYYY-MM-DD to a Date object
      const workshopDate = new Date(dateString);
      // Set time to end of day (23:59:59)
      workshopDate.setHours(23, 59, 59, 999);
      // Get current date
      const currentDate = new Date();
      // Compare dates
      return workshopDate < currentDate;
    }
  
    // Process all workshop weeks
    $('.workshops-week').each(function() {
      // Get date strings from the week header
      const mondayDateStr = $(this).find('.week-header-date').first().text();
      const wednesdayDateStr = $(this).find('.week-header-date').last().text();
      
      // Convert DD/MM format to YYYY-MM-DD
      const currentYear = new Date().getFullYear();
      
      // Function to convert DD/MM to a proper date string
      function convertDateFormat(dateStr) {
        const [day, month] = dateStr.split('/');
        return `${currentYear}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
      }
      
      const mondayDateFormatted = convertDateFormat(mondayDateStr);
      const wednesdayDateFormatted = convertDateFormat(wednesdayDateStr);
      
      // Check if Monday workshops have passed
      if (hasDatePassed(mondayDateFormatted)) {
        $(this).find('.workshops-list-monday .workshop').addClass('passed');
      }
      
      // Check if Wednesday workshops have passed
      if (hasDatePassed(wednesdayDateFormatted)) {
        $(this).find('.workshops-list-wednesday .workshop').addClass('passed');
      }
    });
  });