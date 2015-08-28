$("#kg_beekeepingmanagementbundle_transhumer_rucher").change(function(){
    var data = {
        rucher_id: $(this).val()
    };
 
    $.ajax({
        type: 'post',
        url: Routing.generate('kg_beekeeping_management_select_emplacements_ruche'),
        data: data,
        success: function(data) {
            var $ruche_selector = $('#kg_beekeepingmanagementbundle_transhumer_emplacement');
 
            $ruche_selector.html('<option></option>');
 
            for (var i=0, total = data.length; i < total; i++) {
                $ruche_selector.append('<option value="' + data[i].id + '">' + data[i].nom + '</option>');
            }
        }
    });
});
