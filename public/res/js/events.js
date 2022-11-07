document.addEventListener('DOMContentLoaded', function() {
  function insertEvents(events) {
    const eventsTable = document.querySelector('.events tbody');

    events.forEach(event => {
      eventsTable.innerHTML += `
        <tr>
          <td>${event.date}</td>
          <td>${event.time}</td>
          <td>${event.location}</td>
          <td>${event.title}</td>
        </tr>
      `;
    });
  }

  fetch('api/entropia-wiki-events-json-api.php')
    .then((response) => response.json())
    .then((data) => insertEvents(data.events));
});
