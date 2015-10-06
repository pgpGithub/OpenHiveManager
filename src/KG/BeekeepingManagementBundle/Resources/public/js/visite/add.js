$("[name='kg_beekeepingmanagementbundle_visite[reine]']").bootstrapSwitch();
$("[name='kg_beekeepingmanagementbundle_visite[celroyales]']").bootstrapSwitch();
$("[name='kg_beekeepingmanagementbundle_visite[pollen]']").bootstrapSwitch();


$('.btn-add').click(function(event) {
    var collectionHolder = $('#' + $(this).attr('data-target'));
    var prototype = collectionHolder.attr('data-prototype');
    var form = prototype.replace(/__name__/g, collectionHolder.children().length);

    collectionHolder.append(form);

    return false;
});

$('body').on('click', '.btn-remove', function(event) {
    var name = $(this).attr('data-related');
    $('*[data-content="'+name+'"]').remove();

    return false;
});
