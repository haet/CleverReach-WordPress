var haet_cleverreach = haet_cleverreach || {};

// ********************************************
// Serialize form builder
haet_cleverreach.serialize_form_fields = function(){
    var $ = jQuery;
    var used_ids = $( '#haet_cleverreach_formfields_used' ).sortable( "toArray");
    var used = new Array();
    for (var i = 0; i < used_ids.length; i++) {
        var $attribute = $('#'+used_ids[i]);
        var type = $attribute.data('type');
        var label;
        if( type == 'text' || type == 'email' || type == 'submit' || type == 'gender' )
            label = $attribute.find('.field-label input').val();
        if( type == 'description' )
            label = $attribute.find('.field-description textarea').val();
        used.push({
            'field' : $attribute.data('key'),
            'label' : label,
            'type'  : type,
            'options': ($attribute.hasClass('type-gender')?$attribute.find('.field-options textarea').val():'')
        });
    }
    var available_ids = $( '#haet_cleverreach_formfields_available' ).sortable( "toArray");
    var available = new Array();
    for (var i = 0; i < available_ids.length; i++) {
        var $attribute = $('#'+available_ids[i]);
        var type = $attribute.data('type');
        var label;
        if( type == 'text' || type == 'email' || type == 'submit' || type == 'gender' )
            label = $attribute.find('.field-label input').val();
        if( type == 'description' )
            label = $attribute.find('.field-description textarea').val();
        available.push({
            'field' : $attribute.data('key'),
            'label' : label,
            'type'  : type,
            'options': ($attribute.hasClass('type-gender')?$attribute.find('.field-options textarea').val():'')
        });
    }
    $('input[name="haet_cleverreach_settings[attributes_used]"]').val( JSON.stringify( used ) );
    $('input[name="haet_cleverreach_settings[attributes_available]"]').val( JSON.stringify( available ) );
}
// END: Serialize form builder
// ********************************************

// ********************************************
// UNSerialize form builder
haet_cleverreach.unserialize_form_fields = function(){
    var $ = jQuery;
    var used = $('input[name="haet_cleverreach_settings[attributes_used]"]').val();
    if( used.length>0 )
        used = JSON.parse(used);
    var available = $('input[name="haet_cleverreach_settings[attributes_available]"]').val();
    if( available.length>0 )
        available = JSON.parse(available);
    for(var i=0; i < used.length; i++ ){
        $( '#haet_cleverreach_formfields_used' ).append( haet_cleverreach.get_attribute_sortable_html( used[i] ) );
    }
    for(var i=0; i < available.length; i++ ){
        $( '#haet_cleverreach_formfields_available' ).append( haet_cleverreach.get_attribute_sortable_html( available[i] ) );
    }
}
// END: UNSerialize form builder
// ********************************************

// ********************************************
// get html code for form builder element
haet_cleverreach.get_attribute_sortable_html = function(attribute){
    var icon = '<span class="dashicons dashicons-marker"></span>';
    if( attribute.type == 'gender' )
        icon = '<span class="dashicons dashicons-universal-access"></span>';
    if( attribute.type == 'submit' )
        icon = '<span class="dashicons dashicons-yes"></span>';
    if( attribute.type == 'text' )
        icon = '<span class="dashicons dashicons-feedback"></span>';
    if( attribute.type == 'email' )
        icon = '<span class="dashicons dashicons-email"></span>';
    if( attribute.type == 'description' )
        icon = '<span class="dashicons dashicons-editor-alignleft"></span>';

    var $ = jQuery;
    var html = 
        '<li id="cleverreach-attribute-'+attribute.field+'" data-key="'+attribute.field+'" data-type="'+attribute.type+'" class="attribute clearfix type-'+attribute.type+'">'+
            '<span class="attribute-name">'+icon+' '+
                attribute.field.replace('cleverreach_','').replace('_',' ')+
            '</span>';
    if( attribute.type == 'text' || attribute.type == 'email' || attribute.type == 'submit' || attribute.type == 'gender' ){
        html += 
            '<div class="field-label">'+
                '<label>'+ajax_object.translations.label+'</label>'+
                '<input type="text" value="'+attribute.label+'">'+
            '</div>';
    }
    
    if( attribute.type == 'description' ){
        html += 
            '<div class="field-description">'+
                '<label>'+ajax_object.translations.text+'</label>'+
                '<textarea>'+attribute.label+'</textarea>'+
            '</div>';
    }

    if( attribute.type == 'gender' ){
        html += 
                '<div class="field-options">'+
                    '<label>'+ajax_object.translations.available_options+'</label>'+
                    '<textarea>'+attribute.options+'</textarea>'+
                '</div>';
    }
    
    html += '</li>';
    return html;
}
// END: get html code for form builder element
// ********************************************


jQuery(document).ready(function($) {

    $( '#haet_cleverreach_formfields_available, #haet_cleverreach_formfields_used' ).sortable({
        connectWith: '.connected-sortable',
        stop: function( event, ui ) {
            haet_cleverreach.serialize_form_fields();
        }
    });

    $( 'li.attribute input, li.attribute textarea' ).live('change',function(){
        haet_cleverreach.serialize_form_fields();
    });
    

    if ( $( '#haet_cleverreach_formfields_available, #haet_cleverreach_formfields_used' ).length ){
        if( $( '#haet_cleverreach_formfields_available li.attribute' ).length ){
            $('input[name="haet_cleverreach_settings[attributes_used]"]').val('');
            $('input[name="haet_cleverreach_settings[attributes_available]"]').val('');
        }
        haet_cleverreach.unserialize_form_fields();
    }

    $('input[name="haet_cleverreach_settings[show_in_comments]"]').change(function(){
        var disabled = true;
        if( $('input[name="haet_cleverreach_settings[show_in_comments]"]:checked').val() == "1" )
            disabled = false;

        
        $('input[name="haet_cleverreach_settings[show_in_comments_caption]"]').prop('disabled',disabled);
        $('select[name="haet_cleverreach_settings[show_in_comments_form]"]').prop('disabled',disabled);
        $('input[name="haet_cleverreach_settings[show_in_comments_defaultchecked]"]').prop('disabled',disabled);

    });
    $('input[name="haet_cleverreach_settings[show_in_comments]"]').change();
}); 


