//not in use!!!
//@todo
/*
 * Remote Chained - jQuery AJAX(J) chained selects plugin
 *
 * Copyright (c) 2010-2011 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 */

(function($) {

    $.fn.remoteChained = function(parent_selector, url, options) { 
        
        return this.each(function() {
            
            /* Save this to self because this changes when scope changes. */            
            var self   = this;
            var backup = $(self).clone();
                        
            /* Handles maximum two parents now. */
            $(parent_selector).each(function() {
                $(this).bind("change", function() {

                    /* Build data array from parents values. */
                    var data = {};
                    $(parent_selector).each(function() {
                        var id = $(this).attr("id");
                        //var value = $(":selected", this).val();//orig
                        var value = $(this).val();//modified
                        data[id] = value;
                    });
                    
                    $.getJSON(url, data, function(json) {

                        /* Clear the select. */
                        $("option", self).remove();

                        /* Add new options from json. */
                        for (var key in json) {
                            if (!json.hasOwnProperty(key)) {
                                continue;
                            }
                            /* This sets the default selected. */
                            if ("selected" == key) {
                                continue;
                            }
                            var option = $("<option />").val(key).append(json[key]);
                            $(self).append(option);    
                        }
                        
                        /* Loop option again to set selected. IE needed this... */ 
                        $(self).children().each(function() {
                            if ($(this).val() == json["selected"]) {
                                $(this).attr("selected", "selected");
                            }
                        });

                        /* If we have only the default value disable select. */
                        if (1 == $("option", self).size() && $(self).val() === "") {
                            $(self).attr("disabled", "disabled");
                        } else {
                            $(self).removeAttr("disabled");
                        }
                        
                        /* Force updating the children. */
                        $(self).trigger("change");

                        //refresh chosen!
                        $(self).trigger("liszt:updated");
                        
                    });
                });

                /* Force updating the children. */
                $(this).trigger("change");             

            });
        });
    };
    
    /* Alias for those who like to use more English like syntax. */
    $.fn.remoteChainedTo = $.fn.remoteChained;
    
})(jQuery);

$("#cities").remoteChained("#countries", "http://panel.adserum.lo/location/cities");