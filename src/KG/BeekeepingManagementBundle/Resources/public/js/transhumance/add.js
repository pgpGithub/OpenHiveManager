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

$("#kg_beekeepingmanagementbundle_transhumance_rucher").change(function(){
    var data = {
        rucher_id: $(this).val(),
    };
 
    var num = false;
    
    $.ajax({
        type: 'post',
        url: Routing.generate('kg_beekeeping_management_select_emplacements_ruche'),
        data: data,
        success: function(data) {
            var $emplacement_selector = $('#kg_beekeepingmanagementbundle_transhumance_emplacementto');
 
            $emplacement_selector.html('<option></option>');
 
            for (var i=0, total = data.length; i < total; i++) {
                $emplacement_selector.append('<option value="' + data[i].id + '">' + data[i].numero + '</option>');
                
                if( data[i].numero != '?'){
                    num = true;
                }
            }
            
            if( num ){
                $("#numerotation").show('slow'); 
            }else{
                $("#numerotation").hide('slow');  
            }
        }
    });   
});
