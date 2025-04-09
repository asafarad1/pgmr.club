$(function() {
  // Function to scroll to the current week
  function scrollToCurrentWeek() {
    // Get current date
    const now = new Date();
    
    // Array to store each week with its dates
    const weeks = [];
    
    // Collect all weeks and their dates
    $('.workshops-week').each(function() {
      const weekElement = $(this);
      const mondayDateStr = $(this).find('.week-header-date').first().text();
      const wednesdayDateStr = $(this).find('.week-header-date').last().text();
      
      // Convert DD/MM format to a proper date object for the current year
      function getDateFromDDMM(dateStr) {
        const [day, month] = dateStr.split('/');
        const date = new Date(now.getFullYear(), parseInt(month) - 1, parseInt(day));
        
        // If the date is in the past but more than 6 months ago, it's probably for next year
        if (date < now && now.getMonth() - date.getMonth() > 6) {
          date.setFullYear(date.getFullYear() + 1);
        }
        
        return date;
      }
      
      const mondayDate = getDateFromDDMM(mondayDateStr);
      const wednesdayDate = getDateFromDDMM(wednesdayDateStr);
      
      // Store week info
      weeks.push({
        element: weekElement,
        mondayDate: mondayDate,
        wednesdayDate: wednesdayDate
      });
    });
    
    // Sort weeks by date
    weeks.sort((a, b) => a.wednesdayDate - b.wednesdayDate);
    
    // Find the first week that starts now or in the future,
    // or the last past week if all weeks are in the past
    let targetWeek = null;
    
    // First try to find a week that starts today or in the future
    for (const week of weeks) {
      // Set time to start of day for comparison
      const startOfToday = new Date(now.getFullYear(), now.getMonth(), now.getDate());
      
      if (week.wednesdayDate >= startOfToday) {
        targetWeek = week;
        break;
      }
    }
    
    // If all weeks are in the past, get the most recent one
    if (!targetWeek && weeks.length > 0) {
      targetWeek = weeks[weeks.length - 1];
    }
    
    // If we found a target week, scroll to it
    if (targetWeek) {
      // Add some delay to ensure the page is fully loaded
      setTimeout(function() {
        // Get the offset of the target week
        const offset = targetWeek.element.offset().top;
        
        // Account for sticky header (approximate height)
        const headerOffset = 120;
        
        // Smooth scroll to the target week
        $('html, body').animate({
          scrollTop: offset - headerOffset
        }, 500);
      }, 300);
    }
  }
  
  // Run the function when the page loads
  scrollToCurrentWeek();
});
