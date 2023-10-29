(function () {
  function createEventTdCell(text, link) {
    let tableCell = document.createElement("td");
    if (link != null) {
      let anchor = document.createElement("a");
      anchor.rel = "noreferrer noopener";
      anchor.target = "_blank";
      anchor.href = link;
      anchor.textContent = text;
      tableCell.appendChild(anchor);
    } else {
      let p = document.createElement("p");
      p.textContent = text;
      tableCell.appendChild(p);
    }
    return tableCell;
  }

  function insertEvents(events) {
    const eventsTable = document.querySelector('.events tbody');

    events.forEach(event => {
      const dateCell = createEventTdCell(event.date.text, event.date.link);
      const timeCell = createEventTdCell(event.time.text, event.time.link);
      const locationCell = createEventTdCell(event.location.text, event.location.link);
      const titleCell = createEventTdCell(event.title.text, event.title.link);

      let newRow = document.createElement("tr");

      newRow.appendChild(dateCell);
      newRow.appendChild(timeCell);
      newRow.appendChild(locationCell);
      newRow.appendChild(titleCell);

      eventsTable.appendChild(newRow);
    });
  }

  fetch('api/entropia-wiki-events-json-api.php')
    .then((response) => response.json())
    .then((data) => insertEvents(data["events"]));
})()
