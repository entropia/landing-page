function isInternetExplorer() {
  try {
    return !CSS.supports('(--foo: red)');
  } catch (e) {
    return true;
  }
}

function bewareOfInternetExplorer() {
  var bewareOfInternetExplorerMarkup = '<div class="beware-of-ie"> <div class="beware-of-ie-content"> <p>Ihr Webbrowser, Internet&nbsp;Explorer, wird von Microsoft<br>seit 2013 nicht mehr weiterentwickelt.</p><p>Wir empfehlen Ihnen, auf eine<br>zeitgemäße Alternative umzusteigen:</span></p><div class="alternatives"> <a class="alternative alternative-firefox" href="https://www.mozilla.org/de/firefox" target="_blank"> <span>Mozilla Firefox</span> </a> <a class="alternative alternative-chrome" href="https://www.google.com/chrome" target="_blank"> <span>Google Chrome</span> </a> <a class="alternative alternative-opera" href="https://www.opera.com" target="_blank"> <span>Opera</span> </a> <a class="alternative alternative-edge" href="https://www.microsoft.com/de-de/windows/microsoft-edge" target="_blank"> <span>Microsoft Edge</span> </a> </div></div></div>';

  document.body.innerHTML += bewareOfInternetExplorerMarkup;
}

if (isInternetExplorer()) {
  bewareOfInternetExplorer();
}
