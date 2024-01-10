const escapeHTML = string => {
  let p = document.createElement('p');
  p.textContent = string;
  return p.innerHTML;
};

const formatters = {
  'date': x => new Date(x).toLocaleDateString('de-DE', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  }),
  'time': x => new Date(x).toLocaleString('de-DE', {
    hour: '2-digit',
    minute: '2-digit',
  }) + ' Uhr',
  'location': escapeHTML,
  'title': escapeHTML,
};

(() => {
  function insertEvents(events) {
    const eventsTable = document.querySelector('.events tbody');

    events.forEach(event => {
      eventsTable.innerHTML += `
        <tr>
          <td>${formatters['date'](event.datetime)}</td>
          <td>${formatters['time'](event.datetime)}</td>
          <td>${formatters['location'](event.location)}</td>
          <td>${formatters['title'](event.title)}</td>
        </tr>
      `;
    });
  }

  fetch('api/entropia-cloud-events-json-api.php')
    .then((response) => response.json())
    .then((data) => data.events.sort(
      (a, b) => new Date(a.datetime) - new Date(b.datetime))) // make sure events are sorted
    .then((events) => insertEvents(events));
})();
