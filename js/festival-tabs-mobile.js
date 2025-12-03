/**
 * Move festival tabs to mobile header on smaller screens
 */
(function() {
  const festivalTabs = document.querySelector('.festival-tabs-wrapper');
  const mobileContainer = document.getElementById('festival-tabs-mobile-container');
  const desktopHeader = document.getElementById('grve-main-header');
  
  if (!festivalTabs || !mobileContainer) {
    return;
  }
  
  let isInMobile = false;
  const breakpoint = 1269; // Adjust based on your theme's mobile breakpoint
  
  function moveTabs() {
    const windowWidth = window.innerWidth;
    
    if (windowWidth < breakpoint && !isInMobile) {
      // Move to mobile
      mobileContainer.appendChild(festivalTabs);
      isInMobile = true;
    } else if (windowWidth >= breakpoint && isInMobile) {
      // Move back to desktop (before main header content)
      desktopHeader.insertBefore(festivalTabs, desktopHeader.firstChild);
      isInMobile = false;
    }
  }
  
  // Run on load
  moveTabs();
  
  // Run on resize (debounced)
  let resizeTimer;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(moveTabs, 250);
  });
})();

