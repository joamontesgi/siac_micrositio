<footer class="siac-site-footer clear container-fluid">
  <div class="row">
    <nav class="col-lg-3 col-md-3 col-sm-4 col-6 gobiernoLinea">
      <a href="https://legal.unal.edu.co" target="_top">Régimen Legal</a>
      <a href="https://personal.unal.edu.co" target="_top">Talento humano</a>
      <a href="https://portaladquisiciones.unal.edu.co/" target="_top">Contratación</a>
      <a href="https://pagovirtual.unal.edu.co/" target="_top">Pago Virtual</a>
      <a href="http://siga.unal.edu.co" target="_top">Calidad</a>
    </nav>
    <nav class="col-lg-3 col-md-3 col-sm-4 col-6 gobiernoLinea">
      <a href="https://smartkey.xertica.com/cloudkey/a/unal.edu.co/user/login" target="_top">Correo institucional</a>
      <a href="{{ route('siac.page', ['slug' => 'contacto']) }}">Contacto SIAC</a>
      <a href="https://unal.edu.co/contactenos">Contáctenos</a>
    </nav>
    <div class="col-lg-4 col-md-4 col-sm-4 col-12 footer-info">
      <div class="row footer-info-spacing">
        <p class="col-lg-6 col-md-12 col-sm-12 col-6 contacto">
          <b>Contacto micrositio SIAC:</b><br />
          Universidad Nacional de Colombia<br />
          PBX: (+57) 601 4068888
        </p>
        <p class="col-lg-6 col-md-12 col-sm-12 col-6 derechos">
          <a href="https://unal.edu.co/archivos/user_upload/docs/legal.pdf" target="_blank">&copy; Copyright {{ date('Y') }}</a><br />
          Algunos derechos reservados.<br />
          <a href="mailto:{{ config('siac.contact_email') }}">{{ config('siac.contact_email') }}</a><br />
          Actualización: {{ date('d/m/y') }}
        </p>
      </div>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-12 col-12 logos">
      <div class="row px-3">
        <div class="col-lg-6 col-md-12 col-sm-6 col-6 no-padding">
          <div class="row mx-1">
            <a class="col-md-12 col-sm-6 col-6" href="https://orgullo.unal.edu.co">
              <img class="hidden-print" alt="Orgullo UN" src="{{ asset('unal/images/log_orgullo.png') }}" width="78" height="21" />
            </a>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-6 col-6 no-padding">
          <div class="row mx-1">
            <a class="col-md-12 col-sm-6 col-6" href="https://www.gov.co/">
              <img alt="Portal Único del Estado Colombiano" src="{{ asset('unal/images/log_gobiern.png') }}" width="67" height="51" />
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>
