(() => {
  const OPENED_CLASS = 'club-status-opened';
  const CLOSED_CLASS = 'club-status-closed';

  const clubStatusMarker = document.querySelector('.club-status');

  function setClubStatus(isOpen, lastChange) {
    if (isOpen) {
      clubStatusMarker.classList.remove(CLOSED_CLASS);
      clubStatusMarker.classList.add(OPENED_CLASS);
      clubStatusMarker.innerHTML = 'geöffnet';
    }
    else {
      clubStatusMarker.classList.remove(OPENED_CLASS);
      clubStatusMarker.classList.add(CLOSED_CLASS);
      clubStatusMarker.innerHTML = 'geschlossen';
    }

    const lastChangeDateTimeString = (new Date(lastChange).toLocaleString('de-DE'));

    clubStatusMarker.setAttribute('title', 'letzte Änderung: ' + lastChangeDateTimeString);
  }

  function updateClubStatus() {
    fetch('api/entropia-club-status-json-api.php')
      .then(response => response.json())
      .then(data => setClubStatus(data['isOpen'], data['lastChange']));
  }

  setInterval(updateClubStatus, 30000);

  updateClubStatus();
})();
