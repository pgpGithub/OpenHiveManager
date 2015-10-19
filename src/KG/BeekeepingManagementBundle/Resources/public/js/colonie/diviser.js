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
$("[name='kg_beekeepingmanagementbundle_colonie[remerages][0][reine][clippage]']").bootstrapSwitch();
$("[name='kg_beekeepingmanagementbundle_colonie[remerages][0][reine][marquage]']").bootstrapSwitch();

$("#kg_beekeepingmanagementbundle_colonie_ruche_rucher").change(function(){
    var data = {
        rucher_id: $(this).val()
    };
 
    $.ajax({
        type: 'post',
        url: Routing.generate('kg_beekeeping_management_select_emplacements_ruche'),
        data: data,
        success: function(data) {
            var $ruche_selector = $('#kg_beekeepingmanagementbundle_colonie_ruche_emplacement');
 
            $ruche_selector.html('<option></option>');
 
            for (var i=0, total = data.length; i < total; i++) {
                $ruche_selector.append('<option value="' + data[i].id + '">' + data[i].nom + '</option>');
            }
        }
    });
});
