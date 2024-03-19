const escapeHTML = string => {
  let p = document.createElement('p');
  p.textContent = string;
  return p.innerHTML;
};

const makeLinksClickable = string => {
  let nodes = [];

  string.split(/\s/).map(word => {
    try {
      const url = new URL(word);

      // prevent XSS by using DOM API instead of string concatenation
      let a = document.createElement('a');
      a.textContent = word;
      a.href = url;
      nodes.push(a.outerHTML);
    } catch {
      nodes.push(escapeHTML(word));
    }
  });

  return nodes.join(' ');
}

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
  'location': makeLinksClickable,
  'title': escapeHTML,
  'description': makeLinksClickable,
};

(() => {
  function insertEvents(events) {
    const eventsTable = document.querySelector('#events tbody');

    events.forEach(event => {
      eventsTable.innerHTML += `
        <tr>
          <td>${formatters['date'](event.datetime)}</td>
          <td>${formatters['time'](event.datetime)}</td>
          <td>${formatters['location'](event.location)}</td>
          <td>${formatters['title'](event.title)}</td>
          <td>${formatters['description'](event.description)}</td>
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
