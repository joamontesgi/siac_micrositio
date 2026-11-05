<div id="siac-validacion-config" hidden
  data-store-url="{{ route('siac.concept_validation.store') }}"
  data-summary-url="{{ route('siac.concept_validation.summary') }}"
  data-csrf="{{ csrf_token() }}"
  data-admin-url="{{ route('admin.concept_validations.index') }}"
  data-acta-url="{{ route('siac.concept_validation.acta', ['format' => 'json']) }}"
></div>

<div class="siac-validacion-concepto" id="validacion-concepto-calidad">
  <section class="portada">
    <div class="wrap">
      <span class="sello">Concepto en validación</span>
      <h2>¿Qué entendemos por calidad en la UNAL?</h2>
      <div class="concepto">
        <p>La calidad en la Universidad Nacional de Colombia se entiende como la capacidad institucional, construida colectiva y participativamente, de garantizar coherencia entre su <b>identidad y mandato público</b> como <b>universidad autónoma y pública de carácter nacional</b>, la <b>dignidad e inclusión de su comunidad universitaria</b>, el <b>rigor y la pertinencia social</b> de sus funciones misionales de <b>formación, investigación y extensión</b>, y la solidez de la <b>gobernanza y la gestión institucional</b> que las sostienen.</p>
        <p>Es un <em>horizonte en permanente construcción</em>, que se fortalece mediante la evaluación, el aprendizaje y el mejoramiento continuo, y cuyo propósito es cumplir la misión y el proyecto educativo de la Universidad, así como la responsabilidad que la sociedad colombiana le ha confiado.</p>
      </div>
    </div>
  </section>

  <section class="instrumento">
    <div class="wrap">
      <div class="titulo-bloque">
        <h2>Preguntas de validación</h2>
        <p class="lede">Responda las tres preguntas y registre las observaciones que considere. Lo que escriba alimenta la consolidación que se consigna en el acta de la sesión.</p>
      </div>

      <form id="form">
        <div class="identidad">
          <label class="campo" for="nombre">Nombre
            <input type="text" id="nombre" name="nombre" autocomplete="name" placeholder="Nombre y apellido">
          </label>
          <label class="campo" for="dependencia">Dependencia o sede
            <input type="text" id="dependencia" name="dependencia" placeholder="Facultad, sede o dirección">
          </label>
          <label class="campo" for="rol">Estamento o cargo
            <select id="rol" name="rol">
              <option value="">Seleccione</option>
              <option>Directivo</option>
              <option>Docente</option>
              <option>Estudiante</option>
              <option>Personal administrativo</option>
              <option>Egresado</option>
              <option>Invitado externo</option>
            </select>
          </label>
        </div>

        <article class="pregunta">
          <div class="num">1</div>
          <div class="criterio">Pertinencia institucional</div>
          <p class="enunciado">¿El concepto expresa de manera adecuada la identidad, el mandato público y la responsabilidad social de la Universidad Nacional de Colombia?</p>
          <div class="opciones" role="radiogroup" aria-label="Pertinencia institucional">
            <label class="opcion"><input type="radio" name="p1" value="Sí" id="p1si"><span>Sí</span></label>
            <label class="opcion"><input type="radio" name="p1" value="No" id="p1no"><span>No</span></label>
          </div>
          <label class="obs" for="o1"><span>Observaciones</span>
            <textarea id="o1" name="o1" placeholder="Qué ajustaría, qué falta, qué sobra"></textarea>
          </label>
        </article>

        <article class="pregunta">
          <div class="num">2</div>
          <div class="criterio">Integralidad de la calidad</div>
          <p class="enunciado">¿Integra coherentemente la vida universitaria, las funciones misionales y la gobernanza y gestión institucional, sin reducir la calidad a resultados o indicadores aislados?</p>
          <div class="opciones" role="radiogroup" aria-label="Integralidad de la calidad">
            <label class="opcion"><input type="radio" name="p2" value="Sí" id="p2si"><span>Sí</span></label>
            <label class="opcion"><input type="radio" name="p2" value="No" id="p2no"><span>No</span></label>
          </div>
          <label class="obs" for="o2"><span>Observaciones</span>
            <textarea id="o2" name="o2" placeholder="Qué ajustaría, qué falta, qué sobra"></textarea>
          </label>
        </article>

        <article class="pregunta">
          <div class="num">3</div>
          <div class="criterio">Capacidad orientadora</div>
          <p class="enunciado">¿Ofrece una base conceptual suficiente para orientar el SIAC, la evaluación, la autorregulación y el mejoramiento continuo?</p>
          <div class="opciones" role="radiogroup" aria-label="Capacidad orientadora">
            <label class="opcion"><input type="radio" name="p3" value="Sí" id="p3si"><span>Sí</span></label>
            <label class="opcion"><input type="radio" name="p3" value="No" id="p3no"><span>No</span></label>
          </div>
          <label class="obs" for="o3"><span>Observaciones</span>
            <textarea id="o3" name="o3" placeholder="Qué ajustaría, qué falta, qué sobra"></textarea>
          </label>
        </article>

        <div class="acciones">
          <button type="submit" class="primario" id="enviar">Enviar validación</button>
          <button type="button" class="secundario" id="descargarMia" hidden>Descargar mi respuesta</button>
          <button type="button" class="secundario" id="otra" hidden>Registrar otra respuesta</button>
        </div>
        <p class="aviso" id="aviso" hidden></p>
      </form>
    </div>
  </section>

  <section class="panel">
    <div class="wrap">
      <details id="detallePanel">
        <summary>Panel de consolidación</summary>
        <p class="panel-nota">Consolidado en tiempo real con las respuestas guardadas en el micrositio. Para importar CSV, editar o vaciar el consolidado, use la <a href="{{ route('admin.concept_validations.index') }}">administración de validaciones</a> (requiere iniciar sesión).</p>

        <div class="cabeza-res">
          <h2>Consolidado de la sesión</h2>
          <span class="conteo" id="conteo">Sin respuestas</span>
        </div>

        <div class="tally" id="tally" hidden>
          <div class="fila">
            <div class="fila-cab"><strong>1 · Pertinencia institucional</strong><span class="cifras" id="c1"></span></div>
            <div class="barra"><i class="b-si" id="b1si"></i><i class="b-no" id="b1no"></i></div>
          </div>
          <div class="fila">
            <div class="fila-cab"><strong>2 · Integralidad de la calidad</strong><span class="cifras" id="c2"></span></div>
            <div class="barra"><i class="b-si" id="b2si"></i><i class="b-no" id="b2no"></i></div>
          </div>
          <div class="fila">
            <div class="fila-cab"><strong>3 · Capacidad orientadora</strong><span class="cifras" id="c3"></span></div>
            <div class="barra"><i class="b-si" id="b3si"></i><i class="b-no" id="b3no"></i></div>
          </div>
          <div class="leyenda"><span><i class="b-si"></i>Sí</span><span><i class="b-no"></i>No</span><span><i style="background:var(--linea);display:inline-block;width:10px;height:10px;border-radius:3px;margin-right:6px"></i>Sin respuesta</span></div>
        </div>

        <div class="vacio" id="vacio">
          <strong>Aún no hay respuestas registradas</strong>
          Las validaciones enviadas desde este formulario aparecerán aquí de inmediato.
        </div>

        <div class="observaciones" id="observaciones"></div>

        <div class="acciones">
          <button type="button" class="secundario" id="csv">Descargar consolidado (CSV)</button>
          <button type="button" class="secundario" id="acta">Copiar resumen para el acta</button>
        </div>
      </details>
    </div>
  </section>
</div>
