(function() {
  jQuery(function($) {
    var el;
    el = '.facetwp-template.edd_downloads_list';
    $(document).on('ready', function() {
      return $(el).removeData('columns').removeAttr('data-columns');
    });
    return $(document).on('facetwp-loaded', function() {
      var grid;
      $(el).find($('.edd_download.content-grid-download')).attr('style', '');
      grid = document.querySelector(el);
      salvattore['registerGrid'](grid);
      return $('#edd_download_pagination').remove();
    });
  });

}).call(this);
