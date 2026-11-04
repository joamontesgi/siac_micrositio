<section class="band alt" id="recursos-tecnicos" aria-labelledby="recursos-tecnicos-title">
  <div class="wrap campana-tools">
    <div class="shead">
      <h2 id="recursos-tecnicos-title">Recursos del micrositio</h2>
      <p>Consulta documentos, indicadores y contenidos técnicos del SIAC integrados en esta plataforma.</p>
    </div>
    <ul>
      <li><a href="{{ route('siac.documents.index') }}">Documentos del SIAC</a></li>
      <li><a href="{{ route('siac.page', ['slug' => 'matriz-indicadores']) }}">Matriz de indicadores</a></li>
      <li><a href="{{ route('siac.page', ['slug' => 'presentacion-simulacro']) }}">Presentación simulacro (6 de octubre)</a></li>
      <li><a href="{{ route('siac.page', ['slug' => 'que-es-siac']) }}">¿Qué es el SIAC?</a> (contenido ampliado)</li>
      <li><a href="{{ route('siac.search') }}">Buscar en el sitio</a></li>
    </ul>
  </div>
</section>
