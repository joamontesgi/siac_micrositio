(function ($) {
  'use strict';

  var $root = $('#siac-indicators-explorer');
  if (!$root.length) {
    return;
  }

  var $items = $root.find('.siac-indicator-item');
  var $count = $('#siac-indicators-visible-count');
  var $empty = $('#siac-indicators-empty');

  function normalize(text) {
    return (text || '')
      .toString()
      .toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '');
  }

  function applyFilters() {
    var q = normalize($root.find('[data-filter="q"]').val());
    var conjunto = $root.find('[data-filter="conjunto"]').val();
    var eje = $root.find('[data-filter="eje"]').val();
    var estado = $root.find('[data-filter="estado"]').val();
    var visible = 0;

    $items.each(function () {
      var $el = $(this);
      var match =
        (!conjunto || $el.data('conjunto') === conjunto) &&
        (!eje || $el.data('eje') === eje) &&
        (!estado || $el.data('estado') === estado) &&
        (!q || normalize($el.data('search')).indexOf(q) !== -1);

      $el.toggleClass('d-none', !match);
      if (match) {
        visible += 1;
      }
    });

    $root.find('.siac-accordion-line').each(function () {
      var $panel = $(this);
      var hasVisible = $panel.find('.siac-indicator-item:not(.d-none)').length > 0;
      $panel.toggleClass('d-none', !hasVisible);
    });

    $root.find('.siac-conjunto-pane').each(function () {
      var $pane = $(this);
      var hasVisible = $pane.find('.siac-indicator-item:not(.d-none)').length > 0;
      $pane.toggleClass('siac-pane-empty', !hasVisible);
    });

    if ($count.length) {
      $count.text(visible);
    }
    if ($empty.length) {
      $empty.toggleClass('d-none', visible > 0);
    }
  }

  $root.on('input change', '[data-filter]', applyFilters);

  $root.on('click', '[data-action="clear-filters"]', function (e) {
    e.preventDefault();
    $root.find('[data-filter]').val('');
    applyFilters();
  });

  applyFilters();
})(jQuery);
