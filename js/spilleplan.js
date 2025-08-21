document.addEventListener('DOMContentLoaded', function () {
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

  function activateTab(date) {
    let found = false;
    tabs.forEach(tab => {
      if (tab.dataset.date === date) {
        tab.classList.add('active');
        found = true;
      } else {
        tab.classList.remove('active');
      }
    });
    if (found) showEvents(date);
    return found;
  }

  // Read 'dato' from URL
  const urlParams = new URLSearchParams(window.location.search);
  const urlDate = urlParams.get('dato');

  let activated = false;
  if (urlDate) {
    activated = activateTab(urlDate);
  }
  // Fallback to default date from data-default-date attribute
  if (!activated && tabs.length) {
    const tabsContainer = document.querySelector('.spilleplan-tabs');
    const defaultDate = tabsContainer ? tabsContainer.getAttribute('data-default-date') : null;
    if (defaultDate && activateTab(defaultDate)) {
      activated = true;
    }
  }
  // Fallback to first tab if no valid dato param or default date
  if (!activated && tabs.length) {
    tabs[0].classList.add('active');
    showEvents(tabs[0].dataset.date);
  }

  tabs.forEach(tab => {
    tab.addEventListener('click', function () {
      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      showEvents(tab.dataset.date);

      // Update URL parameter to 'dato' without reloading
      const newUrl = new URL(window.location);
      newUrl.searchParams.set('dato', tab.dataset.date);
      window.history.replaceState({}, '', newUrl);
    });
  });

  // Click and drag to pan
  const wrapper = document.querySelector('.spilleplan-wrapper');
  let isDown = false;
  let startX, scrollLeft;

  if (wrapper) {
    wrapper.addEventListener('mousedown', function (e) {
      isDown = true;
      wrapper.classList.add('dragging');
      startX = e.pageX - wrapper.offsetLeft;
      scrollLeft = wrapper.scrollLeft;
      e.preventDefault();
    });

    wrapper.addEventListener('mouseleave', function () {
      isDown = false;
      wrapper.classList.remove('dragging');
    });

    wrapper.addEventListener('mouseup', function () {
      isDown = false;
      wrapper.classList.remove('dragging');
    });

    wrapper.addEventListener('mousemove', function (e) {
      if (!isDown) return;
      e.preventDefault();
      const x = e.pageX - wrapper.offsetLeft;
      const walk = (x - startX) * -1; // Negative for natural direction
      wrapper.scrollLeft = scrollLeft + walk;
    });
  }
});
