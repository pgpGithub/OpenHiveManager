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
    
    
    // Add hausse
    // ------------------------------    
    jQuery(function($) {
        $(document).on('click', '.btn-add[data-target]', function(event) {
            var collectionHolder = $('#' + $(this).attr('data-target'));

            if (!collectionHolder.attr('data-counter')) {
                collectionHolder.attr('data-counter', collectionHolder.children().length);
            }

            var prototype = collectionHolder.attr('data-prototype');
            var form = prototype.replace(/__name__/g, collectionHolder.attr('data-counter'));

            collectionHolder.attr('data-counter', Number(collectionHolder.attr('data-counter')) + 1);
            collectionHolder.append(form);

            event && event.preventDefault();
        });

        $(document).on('click', '.btn-remove[data-related]', function(event) {
            var name = $(this).attr('data-related');
            $('*[data-content="'+name+'"]').remove();

            event && event.preventDefault();
        });
    }); 
    
});
