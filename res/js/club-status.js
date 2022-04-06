document.addEventListener('DOMContentLoaded', function() {
  const OPENED_CLASS = 'club-status-opened';
  const CLOSED_CLASS = 'club-status-closed';

  function setClubStatus(data) {
    const clubStatusMarker = document.querySelector('.club-status');

    if (data.isOpen) {
      clubStatusMarker.classList.remove(CLOSED_CLASS);
      clubStatusMarker.classList.add(OPENED_CLASS);
    }
    else {
      clubStatusMarker.classList.remove(OPENED_CLASS);
      clubStatusMarker.classList.add(CLOSED_CLASS);
    }

    const lastChangeDateTimeString = (new Date(data.lastChange).toLocaleString('de-DE'));

    clubStatusMarker.setAttribute('title', 'letzte Änderung: ' + lastChangeDateTimeString);
  }

  fetch('api/entropia-club-status-json-api.php')
    .then((response) => response.json())
    .then((data) => setClubStatus(data));
});
