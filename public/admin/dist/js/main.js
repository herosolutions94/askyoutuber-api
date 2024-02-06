$(document).ready(function () {

    jQuery(document).on('change', '.change_thumbnail_image input', function () {
        var preview = jQuery(this).parents(".change_thumbnail_image").find("img");
        var oFReader = new FileReader();
        oFReader.readAsDataURL(jQuery(this)[0].files[0]);
        oFReader.addEventListener("load", function () {
            preview.attr('src', oFReader.result);
        }, false);
    });
    jQuery(document).on('change', '#faq_category', function () {
        let cat_id = jQuery(this).val();
        console.log(base_url + '/admin/get-faq-sub-categories/' + cat_id)
        $.ajax({
            url: base_url + '/admin/get-faq-sub-categories/' + cat_id,
            dataType: 'JSON',
            method: 'GET',
            success: function (rs) {
                if (rs.status == 1) {
                    $("#faq_sub_category").html(rs.html)
                }

            },
            error: function (rs) {
                console.log(rs);
            },
            complete: function (rs) {
                console.log(rs);
            }
        })
    });


});
