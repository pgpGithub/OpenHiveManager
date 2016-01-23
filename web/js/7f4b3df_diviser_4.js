/* 
 * Copyright (C) 2015 Kévin Grenèche < kevin.greneche at openhivemanager.org >
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
$("#kg_beekeepingmanagementbundle_colonie_ruche_rucher").change(function(){
    var data = {
        rucher_id: $(this).val()
    };
    
    var num = false;
    var id;
 
    $.ajax({
        type: 'post',
        url: Routing.generate('kg_beekeeping_management_select_emplacements_ruche'),
        data: data,
        success: function(data) {
            var $ruche_selector = $('#kg_beekeepingmanagementbundle_colonie_ruche_emplacement');
 
            $ruche_selector.html('<option></option>');
 
            for (var i=0, total = data.length; i < total; i++) {
                $ruche_selector.append('<option value="' + data[i].id + '">' + data[i].numero + '</option>');
                
                if( data[i].numero != '?'){
                    num = true;
                }       
                id = data[i].id;
            }
            
            if( num ){
                $("#numerotation").show('slow'); 
            }else{
                $("#numerotation").hide('slow');  
                $("#kg_beekeepingmanagementbundle_colonie_ruche_emplacement").val(id);
            }            
        }
    });
});

$(function() {


    // Select2 select
    // ------------------------------

    // Basic
    $('.select').select2();


    //
    // Select with icons
    //

    // Format icon
    function iconFormat(icon) {
        var originalOption = icon.element;
        if (!icon.id) { return icon.text; }
        var $icon = "<i class='icon-" + $(icon.element).data('icon') + "'></i>" + icon.text;

        return $icon;
    }

    // Initialize with options
    $(".select-icons").select2({
        templateResult: iconFormat,
        minimumResultsForSearch: Infinity,
        templateSelection: iconFormat,
        escapeMarkup: function(m) { return m; }
    });



    // Styled form components
    // ------------------------------

    // Checkboxes, radios
    $(".styled").uniform({ radioClass: 'choice' });

    // File input
    $('.file-input-custom').fileinput({
        previewFileType: 'image',
        browseLabel: 'Select',
        browseClass: 'btn bg-slate-700',
        browseIcon: '<i class="icon-image2 position-left"></i> ',
        removeLabel: 'Remove',
        removeClass: 'btn btn-danger',
        removeIcon: '<i class="icon-cancel-square position-left"></i> ',
        uploadClass: 'btn bg-teal-400',
        uploadIcon: '<i class="icon-file-upload position-left"></i> ',
        layoutTemplates: {
            icon: '<i class="icon-file-check"></i>'
        },
        initialCaption: "Please select image",
        mainClass: 'input-group'
    });   
});

