
var dataListExist = false;

$(document).ready(function() {
    $("body").tooltip({ selector: '[data-bs-toggle=tooltip]' });
});

$('.community-item').on('click', function() {

    updateItemActiveStatus('.community-item', $(this).data('id'));
    updateItemActiveStatus('.resource-item', '0');
    updateItemActiveStatus('.form-item', '0');
    updateItemActiveStatus('.jobform-item', '0');
    updateItemActiveStatus('.sector-item', '0');

    $('#btn-communities').html('Comunidades');
    $('#btn-resources').html('Recursos');
    $('#btn-forms').html('Régimen');
    $('#btn-jobforms').html('Régimen');
    $('#btn-sectors').html('Sector');

    $('.data-item').addClass('d-none');
    $('.data-list').addClass('d-none');


    if ($(this).data('id') == 0 ) {
        $('#btn-communities').html('Comunidades');
    } else {
        $('#btn-communities').html($(this).text());
    }

    showCommunity();

});

$('.resource-item').on('click', function() {

    updateItemActiveStatus('.resource-item', $(this).data('id'));
    updateItemActiveStatus('.form-item', '0');
    updateItemActiveStatus('.jobform-item', '0');
    updateItemActiveStatus('.sector-item', '0');

    $('#btn-sectors').html('Sector');

    $('.data-item').addClass('d-none');
    $('.data-list').addClass('d-none');

    if ($(this).data('id') == 0 ) {
        $('#btn-resources').html('Recursos');
    } else {
        $('#btn-resources').html($(this).text());
    }

    if ($(this).hasClass('Trabajos')) {
        $('.btn-group.form').toggleClass('d-none');
        $('.btn-group.formjob').toggleClass('d-none');
        $('.btn-group.sector').removeClass('d-none');
    } else {
        $('.btn-group.form').removeClass('d-none');
        $('.btn-group.formjob').addClass('d-none');
        $('.btn-group.sector').addClass('d-none');

        if ($(this).hasClass('Negocios')) {
            $('.btn-group.sector').removeClass('d-none');
        } else {
            $('.btn-group.sector').addClass('d-none');      
        }           
    }

    showCommunity();

});    

$('.form-item').on('click', function() {

    updateItemActiveStatus('.form-item', $(this).data('id'));

    $('.data-item').addClass('d-none');
    $('.data-list').addClass('d-none');

    if ($(this).data('id') == 0 ) {
        $('#btn-forms').html('Régimen');
    } else {
        $('#btn-forms').html($(this).text());
    }

    showCommunity();

});   

$('.jobform-item').on('click', function() {

    updateItemActiveStatus('.jobform-item', $(this).data('id'));
    
    $('.data-item').addClass('d-none');
    $('.data-list').addClass('d-none');

    if ($(this).data('id') == 0 ) {
        $('#btn-jobforms').html('Régimen');
    } else {
        $('#btn-jobforms').html($(this).text());
    }

    showCommunity();

});   

$('.sector-item').on('click', function() {

    updateItemActiveStatus('.sector-item', $(this).data('id'));
    
    $('.data-item').addClass('d-none');
    $('.data-list').addClass('d-none');

    if ($(this).data('id') == 0 ) {
        $('#btn-sectors').html('Sector');
    } else {
        $('#btn-sectors').html($(this).text());
    }

    showCommunity();

});  

$('.icon-close').on('click', function() {


    fabio = $(this)


    if($(this).hasClass('icon-data-list')) { // - closing the data-list
        dataListExist = false;
        $('#data-item').addClass('d-none');
        $('.contenedor').addClass('d-none');
    } else { // closing data-item
        $(this).parents().find('#data-item').addClass('d-none');
        if (!dataListExist) {
            $('.contenedor').addClass('d-none');
        }
    }



});   

$('#ver-cdr').on('click', function(e) {
    e.preventDefault();

    $('#datos-cdr .logo').attr('src', '') ;
    $('#datos-cdr span').html('');
    $('#datos-cdr a').html('').attr('href', '') ;

    $('#datos-cdr .logo').attr('src', '/storage/' + $(this).attr('data-logo'));
    $('#datos-cdr .nombre').html($(this).attr('data-nombre'));
    $('#datos-cdr .direccion').html($(this).attr('data-direccion'));
    $('#datos-cdr .ciudad').html($(this).attr('data-ciudad'));
    $('#datos-cdr .horario').html($(this).attr('data-horario'));
    $('#datos-cdr .telefono').html($(this).attr('data-telefono'));
    $('#datos-cdr .email-link').attr('href', 'mailto:' +  $(this).attr('data-email'));
    $('#datos-cdr .email-link').html($(this).attr('data-email'));
    $('#datos-cdr a.web').attr('href', $(this).attr('data-web')).html($(this).data('web'));
    $('#datos-cdr a.link').attr('href', $(this).attr('data-link')).html($(this).attr('data-linkTitle'));
})

function initMap() {

    var styles = [
      {
        "featureType": "administrative.province",
        "elementType": "geometry.stroke",
        "stylers": [
          { "visibility": "on" },
          { "weight": 1.5 },
          { "color": "#000" }
        ]
      },{
        "featureType": "road",
        "elementType": "geometry",
        "stylers": [
          { "visibility": "off" }
        ]
      },{
        "featureType": "administrative.locality",
        "stylers": [
          { "visibility": "on" }
        ]
      },{
        "featureType": "road",
        "elementType": "labels",
        "stylers": [
          { "visibility": "on" }
        ]
      }
    ];   

    var options = {
        zoom:6,
        maxZoom:12,
        styles:styles,
        center:{lat:39.86338991167967,lng:-4.027926176082693}
    }
    // New map
    map = new google.maps.Map(document.getElementById('map'), options);

    $('#map').children('div')[0].appendChild(document.getElementById('main-container'));
    $('#map').children('div')[0].appendChild(document.getElementById('data-container'));

    $('#main-container').removeClass('d-none');
    $('#data-container').removeClass('d-none');


    showCommunity();
} 

function showCommunity() {

    var ccaa = $('.community-item[class*="active"]');
    var resource  = $('.resource-item[class*="active"]');
    var form = $('.form-item[class*="active"]');
    var jobform = $('.jobform-item[class*="active"]');
    var sector = $('.sector-item[class*="active"]');


    // - On the very first load of the page, this object doen't exists yet.
    if (typeof clusters == 'object') {
        clusters.clearMarkers(); // - Clear al clusters (and markers!)
    }

    populateMarkers(ccaa, resource, form, jobform, sector);

    map.setCenter({lat:$(ccaa).data('lat'), lng:$(ccaa).data('lng')})
    map.setZoom(Number($(ccaa).data('zoom')));

}


// - Populate all markes, filtering by community and type
function populateMarkers(c, r, f, j, s) {   

    ms = [];

    for(var i = 0; i < markers.length; i++) {

        if($(c).data('id') == 0 || markers[i].community == $(c).data('id')) {

            if($(r).data('res') == 'Todo' || markers[i].type == $(r).data('res')) {

                if($(s).data('id') == 0 || markers[i].sector == $(s).data('id')) { 


                    if($(r).data('res') != 'Job') {
                        if($(f).data('id') == 0 || markers[i].form == $(f).data('id')) {                   
                            // Add marker
                            a = addMarker(markers[i], i);
                            ms.push(a);                          
                        }                    

                    } else { // - es un trabajo, usar regimenes de trabajo

                        if($(j).data('id') == 0 || markers[i].jobform == $(j).data('id')) {                   
                            // Add marker
                            a = addMarker(markers[i], i);
                            ms.push(a);                          
                        }
                    }
                }

            }    
        }
    }

    if(ms.length == 0 ) {
        alert('Lo sentimos, no hay resultados para esa búsqueda');
    } else {
        addClusters();
    }
}


// Add an indivicual marker an set its properties
function addMarker(m, index) {

    var path = '';

    if (m.type == 'Business') {
        path = "M36.8 192H603.2c20.3 0 36.8-16.5 36.8-36.8c0-7.3-2.2-14.4-6.2-20.4L558.2 21.4C549.3 8 534.4 0 518.3 0H121.7c-16 0-31 8-39.9 21.4L6.2 134.7c-4 6.1-6.2 13.2-6.2 20.4C0 175.5 16.5 192 36.8 192zM64 224V384v80c0 26.5 21.5 48 48 48H336c26.5 0 48-21.5 48-48V384 224H320V384H128V224H64zm448 0V480c0 17.7 14.3 32 32 32s32-14.3 32-32V224H512z"
    } else if (m.type == 'House') {
        path = "M543.8 287.6c17 0 32-14 32-32.1c1-9-3-17-11-24L512 185V64c0-17.7-14.3-32-32-32H448c-17.7 0-32 14.3-32 32v36.7L309.5 7c-6-5-14-7-21-7s-15 1-22 8L10 231.5c-7 7-10 15-10 24c0 18 14 32.1 32 32.1h32v69.7c-.1 .9-.1 1.8-.1 2.8V472c0 22.1 17.9 40 40 40h16c1.2 0 2.4-.1 3.6-.2c1.5 .1 3 .2 4.5 .2H160h24c22.1 0 40-17.9 40-40V448 384c0-17.7 14.3-32 32-32h64c17.7 0 32 14.3 32 32v64 24c0 22.1 17.9 40 40 40h24 32.5c1.4 0 2.8 0 4.2-.1c1.1 .1 2.2 .1 3.3 .1h16c22.1 0 40-17.9 40-40V455.8c.3-2.6 .5-5.3 .5-8.1l-.7-160.2h32z"
    } else if (m.type == 'Job') {
        path = "M304 64c0 26.5-21.5 48-48 48s-48-21.5-48-48s21.5-48 48-48s48 21.5 48 48zM9.8 214.8c5.1-12.2 19.1-18 31.4-12.9L60.7 210l22.9-38.1C99.9 144.6 129.3 128 161 128c51.4 0 97 32.9 113.3 81.7l34.6 103.7 79.3 33.1 34.2-45.6c6.4-8.5 16.6-13.3 27.2-12.8s20.3 6.4 25.8 15.5l96 160c5.9 9.9 6.1 22.2 .4 32.2s-16.3 16.2-27.8 16.2H288c-11.1 0-21.4-5.7-27.2-15.2s-6.4-21.2-1.4-31.1l16-32c5.4-10.8 16.5-17.7 28.6-17.7h32l22.5-30L22.8 246.2c-12.2-5.1-18-19.1-12.9-31.4zm82.8 91.8l112 48c11.8 5 19.4 16.6 19.4 29.4v96c0 17.7-14.3 32-32 32s-32-14.3-32-32V405.1l-60.6-26-37 111c-5.6 16.8-23.7 25.8-40.5 20.2S-3.9 486.6 1.6 469.9l48-144 11-33 32 13.7z"
    } else {
        path = "M96 64c0-35.3 28.7-64 64-64H266.3c26.2 0 49.7 15.9 59.4 40.2L373.7 160H480V126.2c0-24.8 5.8-49.3 16.9-71.6l2.5-5c7.9-15.8 27.1-22.2 42.9-14.3s22.2 27.1 14.3 42.9l-2.5 5c-6.7 13.3-10.1 28-10.1 42.9V160h56c22.1 0 40 17.9 40 40v45.4c0 16.5-8.5 31.9-22.6 40.7l-43.3 27.1c-14.2-5.9-29.8-9.2-46.1-9.2c-39.3 0-74.1 18.9-96 48H352c0 17.7-14.3 32-32 32h-8.2c-1.7 4.8-3.7 9.5-5.8 14.1l5.8 5.8c12.5 12.5 12.5 32.8 0 45.3l-22.6 22.6c-12.5 12.5-32.8 12.5-45.3 0l-5.8-5.8c-4.6 2.2-9.3 4.1-14.1 5.8V480c0 17.7-14.3 32-32 32H160c-17.7 0-32-14.3-32-32v-8.2c-4.8-1.7-9.5-3.7-14.1-5.8l-5.8 5.8c-12.5 12.5-32.8 12.5-45.3 0L40.2 449.1c-12.5-12.5-12.5-32.8 0-45.3l5.8-5.8c-2.2-4.6-4.1-9.3-5.8-14.1H32c-17.7 0-32-14.3-32-32V320c0-17.7 14.3-32 32-32h8.2c1.7-4.8 3.7-9.5 5.8-14.1l-5.8-5.8c-12.5-12.5-12.5-32.8 0-45.3l22.6-22.6c9-9 21.9-11.5 33.1-7.6V192 160 64zm170.3 0H160v96h32H304.7L266.3 64zM176 256c-44.2 0-80 35.8-80 80s35.8 80 80 80s80-35.8 80-80s-35.8-80-80-80zM528 448c13.3 0 24-10.7 24-24s-10.7-24-24-24s-24 10.7-24 24s10.7 24 24 24zm0 64c-48.6 0-88-39.4-88-88c0-29.8 14.8-56.1 37.4-72c14.3-10.1 31.8-16 50.6-16c2.7 0 5.3 .1 7.9 .3c44.9 4 80.1 41.7 80.1 87.7c0 48.6-39.4 88-88 88z"
    }

    var icono = {
        path: path,
        fillColor: '#E32831',
        fillOpacity: 1,
        strokeWeight: 0,
        scale: 0.04    
    }

    var marker = new google.maps.Marker({
        position: {lat: Number(m.lat), lng: Number(m.lng)},
        map:map,
        //icon:props.iconImage
        icon: icono,
        type: m.type,
        id: m.id,
        index: index
    });

    marcadores.push(marker);

    marker.addListener('click', function(){

        // - Hide the (clustered) resource list, if openend.
        dataListExist = false;
        $('.data-item').removeClass('toLeft');
        $('.data-list').addClass('d-none');

        processResource(marker.type, marker.id);
    });

    return marker;
}

function addClusters() {
    markerCluster = new MarkerClusterer( map, ms , {
        imagePath: '../assets/images/m',
        maxZoom: 12,
    });

    // - Sets the mimimum number of markers on each cluster
    markerCluster.setMinClusterSize(2);

    // - clusters is global var!
    clusters = markerCluster;   

    google.maps.event.addListener(markerCluster, 'clusterclick', function(cluster) {
        if (map.getZoom() == 12) {
        
            var markerList = {
                Business: [],
                House: [],
                Land: [],
                Job: []
            };

            for(i=0; i < cluster.getSize(); i++) {

                var type = cluster.markers_[i].type;
                var id = cluster.markers_[i].id;

                if(type == 'Business') {
                    markerList.Business.push([id, cluster.markers_[i].index]); 
                } else if(type == 'House') {
                     markerList.House.push([id, cluster.markers_[i].index]); 
                } else if(type == 'Land') {
                     markerList.Land.push([id, cluster.markers_[i].index]);
                } else {
                     markerList.Job.push([id, cluster.markers_[i].index]);       
                }
            }

            dataListExist = true;
            $('#data-item').addClass('d-none');    
            $('#data-list').removeClass('d-none');
            $('.contenedor').removeClass('d-none');

            showResourceList(markerList);
        }
    });

    markerCluster.redraw();
}


function updateItemActiveStatus(menu, item) {

    $(menu).removeClass('active');
    $(menu + '.dropdown-item[data-id=' + item +']').addClass('active');

}


function processResource(type, id) {

    var route = '/api/resource-info/' + type + '/' + id;

    $.ajax({

        url : route,
        type : 'GET',
        dataType:'json',
        success : function(data) {
	    //console.log(data);
            showResource(type, data);
        },
        error : function(xhr, status, error)
        {
            var errorMessage = xhr.status + ': ' + xhr.statusText
            alert('Error: No se encontró información de este recurso');
            //alert("Request: "+JSON.stringify(request));
        }
    });
}

function showResource(type, resource) {

    // - Common to all resource types

    /* -- Title info --*/
    $('#resource .resource-title .reference').html(resource.reference);

    /* -- Resource description  --*/
    $('#resource .detail .description').removeClass('text-area');
    if (resource.description != null) {
        $('#resource .detail .description').html(resource.description);
        if(resource.description.length > 50) {
            $('#resource .detail .description').addClass('text-area');
        }
    } else {
        $('#resource .detail .description').html('No hay descripción disponible');  
    }

    /* -- CDR info --*/
    $('#resource #ver-cdr')
        .html(resource.c_d_r.name)
        .attr('data-nombre', resource.c_d_r.name)
        .attr('data-direccion', resource.c_d_r.address)
        .attr('data-ciudad', resource.c_d_r.city)
        .attr('data-horario', resource.c_d_r.schedule)
        .attr('data-telefono', resource.c_d_r.phone)
        .attr('data-email', resource.c_d_r.email)
        .attr('data-web', resource.c_d_r.web)
        .attr('data-logo', resource.c_d_r.logo);

    if (resource.c_d_r.link) {
        $('#resource #ver-cdr')
            .attr('data-link', resource.c_d_r.link)
            .attr('data-linkTitle', resource.c_d_r.link_title)
    } else {
        $('#resource #ver-cdr')
            .attr('data-link', '')
            .attr('data-linkTitle', '')        
    }

    /* -- Location info --*/
    $('#resource .location .community').html(resource.community.name);
    $('#resource .location .province').html(resource.province.name);
    $('#resource .location .municipality').html(resource.municipality.name);
    $('#resource .location .town').html(resource.town);
    var town = resource.town;
    if (resource.population != null) {
         town += ' (' + resource.population + ' Hab.)';       
    }
    $('#resource .location .town').html(town);

    $('#resource .regime').html( (resource.form != null) ? resource.form.name : 'No hay información disponible');
    $('#resource .price').html( (resource.pricerange != null) ? resource.pricerange.range.substring(4) : 'No hay información disponible')    

    $('#resource .specific-details').addClass('d-none');   // - Hide the Specific details for all resource types           

    // - Per type

    setSpecificDetails(type, resource);                     // - Set the details firlds specific for each resource type


    // - Set the photo section
    $('.inner-resource .gallery').empty();      // - Clear the gallery container content

    if (resource.fotos.length > 0 ) {
        $.each(resource.fotos, function(index, url) {

            var thumb = `<img class="thumbnail" src="${url.thumb}" data-img-resized="${url.resized}">`;

            $('.inner-resource .gallery').append(thumb);
        });
        $('.photos').removeClass('d-none');    // - show the gallery
    } else {
        $('.photos').addClass('d-none');    // - hide the gallery sectin is there are no photos
    }

    // - Add the listener
    $('.inner-resource .thumbnail').on('mouseenter', function() {

        console.log($(this));

        $('.photo-big').attr('src', $(this).data('img-resized'));
        $('#photo').css('left', $('.data-item').offset().left -410);
        $('#photo').removeClass('d-none');
    }) 

    $('.inner-resource .thumbnail').on('mouseleave', function() {
        $('.photo-big').attr('src', '');
        $('#photo').addClass('d-none');
    }) 


    // - Show the dialog
    $('.inner-resource').addClass('d-none');        // - hide any inner containers
    $('.inner-resource').removeClass('d-none');    // - show this container

    $('.contenedor').removeClass('d-none');    // - show this container
    $('.data-item').removeClass('d-none');    // - show this container
}

function setSpecificDetails(type, resource) {

    $('#resource .title i')
        .removeClass('fa-house-chimney')
        .removeClass('fa-shop');

    if (type == 'House') {
        $('.inner-resource .specific-details.house').removeClass('d-none');
        $('.inner-resource .resource-title i.resource').addClass('fa-house-chimney');
        $('.inner-resource .resource-title .resource-type').html('Vivienda');
        $('.inner-resource .status').html( (resource.status != null) ? resource.status.status : 'No hay información disponible');
        $('.inner-resource .stories').html( (resource.stories != null && resource.stories != "0") ? resource.stories : 'N/D');
        $('.inner-resource .bedrooms').html( (resource.bedrooms != null && resource.bedrooms != "0") ? resource.bedrooms : 'N/D');
        $('.inner-resource .bathrooms').html( (resource.bathrooms != null && resource.bathrooms != "0") ? resource.bathrooms : 'N/D');
        $('.inner-resource .rooms').html( (resource.total_rooms != null && resource.total_rooms != "0") ? resource.total_rooms : 'N/D');
        $('.inner-resource .ownership').html( (resource.ownership != null) ? resource.ownership.name : 'No hay información disponible');

        // - Extras handling
        $('.inner-resource i.nested-ban').addClass('d-none');   // - Add ban icon to all extras
        if (resource.courtyard == "0") {
            $('.inner-resource .extra-courtyard').children('.nested-ban').removeClass('d-none');    // - Show the ban icon
        }
        if (resource.stables == "0") {
            $('.inner-resource .extra-stables').children('.nested-ban').removeClass('d-none');    // - Show the ban icon
        }
        if (resource.lands == "0") {
            $('.inner-resource .extra-lands').children('.nested-ban').removeClass('d-none');    // - Show the ban icon
        }
        if (resource.tobusiness == "0") {
            $('.inner-resource .extra-tobusiness').children('.nested-ban').removeClass('d-none');    // - Show the ban icon
        }
    } else if (type == 'Business') {
        $('.inner-resource .specific-details.business').removeClass('d-none');
        $('.inner-resource .resource-title i.resource').addClass('fa-shop');
        $('.inner-resource .resource-title .resource-type').html('Negocio');
        $('.inner-resource .sector').html( (resource.sector != null) ? resource.sector.name : 'No hay información disponible');
        $('.inner-resource .property-type').html( (resource.property_type != null) ? resource.property_type : 'No hay información disponible');
        $('.inner-resource .ownership').html( (resource.ownership != null) ? resource.ownership.name : 'No hay información disponible');
        $('.inner-resource .deadlines').html( (resource.deadlines != null) ? resource.deadlines : 'No hay información disponible');

        $('.terms').removeClass('text-area');
        if (resource.terms != null) {
            $('.inner-resource .terms').html(resource.terms);
            if(resource.terms.length > 50) {
                $('.terms').addClass('text-area');
            }
        } else {
            $('.inner-resource .terms').html('No hay descripción disponible');  
        }
    } else if (type == 'Job') {
        $('.inner-resource .specific-details.job').removeClass('d-none');
        $('.inner-resource .resource-title i.resource').addClass('fa-person-digging');
        $('.inner-resource .resource-title .resource-type').html('Trabajo');
        $('.inner-resource .sector').html( (resource.sector != null) ? resource.sector.name : 'No hay información disponible');
        $('.inner-resource .position').html( (resource.position != null) ? resource.position : 'No hay información disponible');
        $('.inner-resource .ownership').html( (resource.jobownership != null) ? resource.jobownership.name : 'No hay información disponible');
        $('.inner-resource .regime').html( (resource.jobform != null) ? resource.jobform.name : 'No hay información disponible');
        $('.inner-resource .requirements').removeClass('text-area');
        if (resource.requirements != null) {
            $('.inner-resource .requirements').html(resource.requirements);
            if(resource.requirements.length > 50) {
                $('.inner-resource .requirements').addClass('text-area');
            }
        } else {
            $('.inner-resource .requirements').html('No hay descripción disponible');  
        }
    } else { // - type = Tierra
        $('.inner-resource .specific-details.land').removeClass('d-none');
        $('.inner-resource .resource-title i.resource').addClass('fa-tractor');
        $('.inner-resource .resource-title .resource-type').html('Tierra');
        $('.inner-resource .ownership').html( (resource.ownership != null) ? resource.ownership.name : 'No hay información disponible');
        $('.inner-resource .arearange').html( (resource.arearange != null) ? resource.arearange.range : 'No hay información disponible');
        $('.inner-resource .landtype').html( (resource.landtype != null) ? resource.landtype.type : 'No hay información disponible');
        $('.inner-resource .landuse').html( (resource.landuse != null) ? resource.landuse.use : 'No hay información disponible');
    }
}

function showResourceList(markersList) {

    $('#resource-list ul').empty();
    var j = 0;

    $.each(markersList, function(index, ids) {
        $.each(ids, function(i, id) {

            if (index == 'Business') {
               iconClass = 'fa-shop'; 
               resourceName = 'Negocio';
            } else if (index == 'House') {
                iconClass = 'fa-house-chimney'; 
                resourceName = 'Vivienda';
            } else if (index == 'Job') {
                iconClass = 'fa-person-digging';
                resourceName = 'Trabajo'; 
            } else {
                iconClass = 'fa-tractor';
                resourceName = 'Tierra'; 
            }

            var item = `
                <li>
                    <a href="#" class="resource-list-item" data-type="${index}" data-id=${id[0]} data-index=${id[1]}>
                        <i class="fa-solid ${iconClass}"  data-bs-toggle="tooltip" title="${resourceName}"></i>
                        <span class="resource">Referencia: ${markers[id[1]].reference}</span>
                    </a>
                </li>
            `;

            var location = markers[id[1]].ccaa + '<br /> ' + markers[id[1]].province + '<br />' + markers[id[1]].town + ', ' + markers[id[1]].postcode

            $('#resource-list span.list-location').html(location);
            $('#resource-list ul').append(item);

            j++;
        });
    });

    $('.grouped-resources').html(j);

    // - Add the listener to the newly created "resource-list-item" links     
    $('.resource-list-item').on('click', function(e) {
        e.preventDefault();

        // $('.data-item').addClass('toLeft');
        processResource($(this).data('type'), $(this).data('id'));

    });  
}
