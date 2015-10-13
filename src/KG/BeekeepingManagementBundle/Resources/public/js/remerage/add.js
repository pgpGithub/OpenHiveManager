$("[name='kg_beekeepingmanagementbundle_remerage[reine][clippage]']").bootstrapSwitch();
$("[name='kg_beekeepingmanagementbundle_remerage[reine][marquage]']").bootstrapSwitch();
$("[name='kg_beekeepingmanagementbundle_remerage[naturel]']").bootstrapSwitch();

document.getElementById('kg_beekeepingmanagementbundle_remerage_naturel').addEventListener('click',function (event){
    if(event.target.checked){
        document.getElementById('kg_beekeepingmanagementbundle_remerage_reine_race').style.display = "inline-block";
        document.getElementById('kg_beekeepingmanagementbundle_remerage_reine_anneeReine').style.display = "inline-block";
    }else{
        document.getElementById('kg_beekeepingmanagementbundle_remerage_reine_race').style.display = "none";
        document.getElementById('kg_beekeepingmanagementbundle_remerage_reine_anneeReine').style.display = "none";
    }
});

