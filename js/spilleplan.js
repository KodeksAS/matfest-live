document.addEventListener('DOMContentLoaded', function() {
  const tabs = document.querySelectorAll('.spilleplan-tab');
  const events = document.querySelectorAll('.sp-event');

  function showEvents(date) {
    events.forEach(ev => {
      if (ev.dataset.date === date) {
        ev.classList.add('active');
      } else {
        ev.classList.remove('active');
      }
    });
  }

  // Show the first date's events by default
  if (tabs.length) {
    tabs[0].classList.add('active');
    showEvents(tabs[0].dataset.date);
  }

  tabs.forEach(tab => {
    tab.addEventListener('click', function() {
      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      showEvents(tab.dataset.date);
    });
  });
});
