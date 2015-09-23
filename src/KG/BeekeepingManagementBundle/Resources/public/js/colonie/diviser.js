$("#kg_beekeepingmanagementbundle_diviser_rucher").change(function(){
    var data = {
        rucher_id: $(this).val()
    };
 
    $.ajax({
        type: 'post',
        url: Routing.generate('kg_beekeeping_management_select_emplacements_ruche'),
        data: data,
        success: function(data) {
            var $ruche_selector = $('#kg_beekeepingmanagementbundle_diviser_emplacement');
 
            $ruche_selector.html('<option></option>');
 
            for (var i=0, total = data.length; i < total; i++) {
                $ruche_selector.append('<option value="' + data[i].id + '">' + data[i].nom + '</option>');
            }
        }
    });
});

$("#kg_beekeepingmanagementbundle_diviser_ruche_corps_type").change(function(){
    var data = {
        type_id: $(this).val()
    };
 
    $.ajax({
        type: 'post',
        url: Routing.generate('kg_beekeeping_management_select_soustypes_ruche'),
        data: data,
        success: function(data) {
            var $soustype_selector = $('#kg_beekeepingmanagementbundle_diviser_ruche_corps_soustype');
 
            $soustype_selector.html('<option></option>');
 
            for (var i=0, total = data.length; i < total; i++) {
                $soustype_selector.append('<option value="' + data[i].id + '">' + data[i].nbcadres + '</option>');
            }
        }
    });
});
