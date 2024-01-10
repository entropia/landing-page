(() => {
  const activateMapTrigger = document.querySelector('.activate-map-trigger');
  const mapIframe = document.querySelector('.streetmap iframe');
  const mapWrapper = document.querySelector('.streetmap');

  activateMapTrigger.addEventListener('click', () => {
    mapIframe.setAttribute('src', mapIframe.dataset.source);
    mapWrapper.classList.add('loaded');
  });
})();
