(function () {
  'use strict';
  const query = (new URLSearchParams(window.location.search).get('q') || '').trim();
  const input = document.getElementById('siac-search-q');
  const status = document.getElementById('search-status');
  const results = document.getElementById('search-results');
  input.value = query;
  if (!query) return;
  const normalize = value => value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLocaleLowerCase('es');
  status.textContent = 'Buscando…';
  fetch('/search-index.json')
    .then(response => {
      if (!response.ok) throw new Error('Search index unavailable');
      return response.json();
    })
    .then(entries => {
      const terms = normalize(query).split(/\s+/);
      const matches = entries.filter(entry => terms.every(term => normalize(entry.text).includes(term)));
      results.replaceChildren();
      matches.forEach(entry => {
        const item = document.createElement('li');
        const link = document.createElement('a');
        link.href = entry.href;
        link.textContent = entry.title;
        item.appendChild(link);
        results.appendChild(item);
      });
      status.textContent = matches.length ? matches.length + ' resultados encontrados.' : 'No se encontraron resultados. Pruebe con otro término.';
    })
    .catch(() => {
      status.textContent = 'No se pudo cargar la búsqueda. Recargue la página para intentarlo de nuevo.';
    });
}());
