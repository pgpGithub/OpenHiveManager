$("#type").change(function(){
    var data = {
        type: $(this).val()
    };
 
    $.ajax({
        type: 'post',
        url: '{{ path("select_ruches") }}',
        data: data,
        success: function(data) {
            var $ruche_selector = $('#ruche');
 
            $ruche_selector.html('<option>Ruche</option>');
 
            for (var i=0, total = data.length; i < total; i++) {
                $ruche_selector.append('<option value="' + data[i].id + '">' + data[i].libelle + '</option>');
            }
        }
    });
});