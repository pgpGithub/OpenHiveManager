$("[name='kg_beekeepingmanagementbundle_remerage[reine][clippage]']").bootstrapSwitch();
$("[name='kg_beekeepingmanagementbundle_remerage[reine][marquage]']").bootstrapSwitch();
$("[name='kg_beekeepingmanagementbundle_remerage[naturel]']").bootstrapSwitch();

$('input[name="kg_beekeepingmanagementbundle_remerage[naturel]"]').on('switchChange.bootstrapSwitch', function(event, state) {
    // Si remérage artificiel 
    if( !state ){
      $("#kg_beekeepingmanagementbundle_remerage_reine_race").show();  
      $("#kg_beekeepingmanagementbundle_remerage_reine_anneeReine").show();
    }
    else{
      $("#kg_beekeepingmanagementbundle_remerage_reine_race").hide();
      $("#kg_beekeepingmanagementbundle_remerage_reine_anneeReine").hide();      
    }
});