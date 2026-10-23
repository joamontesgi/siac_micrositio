<div class="tx-unal-accesibilidad">
  <div id="panel-accesibilidad" style="display: none" class="panel-content container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-3">
            <h4>Tamaño letra</h4>
            <button class="boton-panel" id="letra-disminuir" onclick="cambiarTamanioLetra('-')" type="button">A<sup>-</sup></button>
            <button class="boton-panel" id="letra-aumentar" onclick="cambiarTamanioLetra('+')" type="button">A<sup>+</sup></button>
            <input disabled class="letras-porcentaje" id="letter-percent" type="text" value="100%" />
          </div>
          <div class="col-md-3">
            <h4>Cambiar Contrastes</h4>
            <button class="boton-panel" id="contraste-1" onclick="cambiarContrastes(1)" type="button">1</button>
            <button class="boton-panel" id="contraste-2" onclick="cambiarContrastes(2)" type="button">2</button>
            <button class="boton-panel" id="contrate-3" onclick="cambiarContrastes(3)" type="button">3</button>
          </div>
          <div class="col-md-3">
            <h4>Invertir colores</h4>
            <button class="boton-panel" id="inversor" onclick="invertirColores()" type="button">Aplicar</button>
          </div>
          <div class="col-md-3">
            <h4>Restablecer ajustes</h4>
            <button class="boton-panel" id="defaul-config" onclick="defaultConfig()" type="button">Aplicar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="pestania-accesibilidad" class="">Panel de Accesibilidad</div>
</div>
