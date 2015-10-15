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