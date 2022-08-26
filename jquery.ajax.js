jQuery(document).ready(function() {
  jQuery("#wp-admin-bar-ayphu_clear_cache").bind("click", function() {
    jQuery("#ayphu-admin-notice").html('<div class="notice notice-info is-dismissible"><p>Estamos borrando la cache un momento por favor ...</p></div>');

    jQuery.ajax({
      url: ajaxurl,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'clearCache'
      },
      success: function () {
        jQuery("#ayphu-admin-notice").html('<div class="notice notice-success is-dismissible"><p>Se borro la caché correctamente!</p></div>');
      }
    });
  });
});