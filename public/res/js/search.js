(() => {
  const searchTrigger = document.querySelector('.search-trigger');
  const searchDialog = document.querySelector('.search-dialog');
  const searchClose = document.querySelector('.search-close');

  searchTrigger.addEventListener('click', () => {
    window.location.hash = 'search';
    searchDialog.showModal();
  });

  searchDialog.addEventListener('close', () => {
    window.location.hash = '';
    history.pushState({}, '', window.location.href.replace(/\/?#?$/, ''));
  });

  searchDialog.addEventListener('click', (event) => {
    if (event.target === searchDialog) {
      searchDialog.close();
    }
  });

  searchClose.addEventListener('click', () => {
    searchDialog.close();
  });
})();
