$("#kg_beekeepingmanagementbundle_ruche_corps_type").change(function(){
    var data = {
        type_id: $(this).val()
    };
 
    $.ajax({
        type: 'post',
        url: Routing.generate('kg_beekeeping_management_select_soustypes_ruche'),
        data: data,
        success: function(data) {
            var $soustype_selector = $('#kg_beekeepingmanagementbundle_ruche_corps_soustype');
 
            $soustype_selector.html('<option></option>');
 
            for (var i=0, total = data.length; i < total; i++) {
                $soustype_selector.append('<option value="' + data[i].id + '">' + data[i].nbcadres + '</option>');
            }
        }
    });
});
