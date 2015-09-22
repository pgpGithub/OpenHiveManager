$("#kg_beekeepingmanagementbundle_ruche_type").change(function(){
    var data = {
        type_id: $(this).val()
    };
 
    $.ajax({
        type: 'post',
        url: Routing.generate('kg_beekeeping_management_select_soustypes_ruche'),
        data: data,
        success: function(data) {
            var $nbcadres_selector = $('#kg_beekeepingmanagementbundle_ruche_corps_nbmaxcadres');
 
            $nbcadres_selector.html('<option></option>');
 
            for (var i=0, total = data.length; i < total; i++) {
                $nbcadres_selector.append('<option value="' + data[i].id + '">' + data[i].nom + '</option>');
            }
        }
    });
});
