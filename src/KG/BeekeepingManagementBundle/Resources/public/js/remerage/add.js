$("[name='kg_beekeepingmanagementbundle_remerage[reine][clippage]']").bootstrapSwitch();
$("[name='kg_beekeepingmanagementbundle_remerage[reine][marquage]']").bootstrapSwitch();
$("[name='kg_beekeepingmanagementbundle_remerage[naturel]']").bootstrapSwitch();

$("#kg_beekeepingmanagementbundle_remerage_naturel").change(function() {

    if($("#kg_beekeepingmanagementbundle_remerage_naturel").attr("checked")) {
        $("#kg_beekeepingmanagementbundle_remerage_reine_race").show();
        $("#kg_beekeepingmanagementbundle_remerage_reine_anneeReine").show();
    }else{
        $("#kg_beekeepingmanagementbundle_remerage_reine_race").hide();
        $("#kg_beekeepingmanagementbundle_remerage_reine_anneeReine").hide();
    }
});

