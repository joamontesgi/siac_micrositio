// Replaces the build-time document list with the rows stored in Netlify Database.
(function () {
  var list = document.querySelector('[data-siac-documents]');
  var status = document.querySelector('[data-siac-doc-status]');
  if (!list || !window.fetch) return;

  function href(url) {
    if (!url) return null;
    return /^https?:\/\//i.test(url) || url.charAt(0) === '/' ? url : '/' + url;
  }

  function item(doc) {
    var li = document.createElement('li');
    li.className = 'siac-home-doc-item';
    var body = document.createElement('div');
    body.className = 'siac-home-doc-body';
    var title = document.createElement('h2');
    title.className = 'siac-home-doc-title';
    title.textContent = doc.title;
    body.appendChild(title);
    if (doc.description) {
      var p = document.createElement('p');
      p.textContent = doc.description;
      body.appendChild(p);
    }
    li.appendChild(body);
    var link = href(doc.url);
    if (link) {
      var a = document.createElement('a');
      a.className = 'siac-btn siac-btn-primary siac-btn-sm';
      a.href = link;
      a.textContent = 'Descargar';
      li.appendChild(a);
    }
    return li;
  }

  fetch('/api/documentos', { headers: { Accept: 'application/json' } })
    .then(function (res) {
      if (!res.ok) throw new Error('HTTP ' + res.status);
      return res.json();
    })
    .then(function (docs) {
      if (!Array.isArray(docs)) throw new Error('Respuesta inválida');
      list.replaceChildren.apply(list, docs.map(item));
      if (status) {
        status.textContent = docs.length
          ? docs.length + ' documento(s) cargado(s) desde la base de datos.'
          : 'La base de datos no tiene documentos vigentes.';
      }
    })
    .catch(function () {
      // Keep the build-time list as a fallback.
      if (status) status.textContent = 'Mostrando la lista publicada; la base de datos no está disponible en este momento.';
    });
})();
