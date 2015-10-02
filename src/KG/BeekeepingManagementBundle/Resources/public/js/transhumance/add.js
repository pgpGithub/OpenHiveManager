$("#kg_beekeepingmanagementbundle_transhumance_rucherto").change(function(){
    var data = {
        rucher_id: $(this).val()
    };
 
    $.ajax({
        type: 'post',
        url: Routing.generate('kg_beekeeping_management_select_emplacements_ruche'),
        data: data,
        success: function(data) {
            var $emplacement_selector = $('#kg_beekeepingmanagementbundle_transhumance_colonie_ruche_emplacement');
 
            $emplacement_selector.html('<option></option>');
 
            for (var i=0, total = data.length; i < total; i++) {
                $emplacement_selector.append('<option value="' + data[i].id + '">' + data[i].nom + '</option>');
            }
        }
    });
});
