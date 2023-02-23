document.addEventListener('DOMContentLoaded', function() {
  function createTableCellContent(text, link) {
    if (link === null) {
      return text;
    }

    return `<a href="${link}" target="_blank" rel="noreferrer noopener">${text}</a>`;
  }

  function insertEvents(events) {
    const eventsTable = document.querySelector('.events tbody');

    events.forEach(event => {
      const dateMarkup = createTableCellContent(event.date.text, event.date.link);
      const timeMarkup = createTableCellContent(event.time.text, event.time.link);
      const locationMarkup = createTableCellContent(event.location.text, event.location.link);
      const titleMarkup = createTableCellContent(event.title.text, event.title.link);

      eventsTable.innerHTML += `
        <tr>
          <td>${dateMarkup}</td>
          <td>${timeMarkup}</td>
          <td>${locationMarkup}</td>
          <td>${titleMarkup}</td>
        </tr>
      `;
    });
  }

  fetch('api/entropia-wiki-events-json-api.php')
    .then((response) => response.json())
    .then((data) => insertEvents(data.events));
});
