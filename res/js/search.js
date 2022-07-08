document.addEventListener('DOMContentLoaded', function() {
  const searchTrigger = document.querySelector('.search-trigger');
  const searchDialog = document.querySelector('.search-dialog');
  const searchClose = document.querySelector('.search-close');

  searchTrigger.addEventListener('click', function() {
    window.location.hash = 'search';
    searchDialog.showModal();
  });

  searchDialog.addEventListener('close', function() {
    window.location.hash = '';
    history.pushState({}, '', window.location.href.replace(/\/?\#?$/, ''));
  });

  searchDialog.addEventListener('click', function(event) {
    if (event.target === searchDialog) {
      searchDialog.close();
    }
  });

  searchClose.addEventListener('click', function(event) {
    searchDialog.close();
  });
});
