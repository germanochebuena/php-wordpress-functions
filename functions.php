<?php

add_action( 'wp_enqueue_scripts', 'enqueue_custom_script', 100 );

function enqueue_custom_script() {
wp_enqueue_script( 'custom', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery' ), false, true );
}

 // Register the additional options page
function register_additional_options_page() {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title' => 'Options Kairi Agency',
            'menu_title' => 'Options Kairi Agency',
            'menu_slug'  => 'options-kairi',
            'capability' => 'manage_options',
            'redirect'   => false,
            'menu_icon'  => 'dashicons-editor-paste-word' // Custom Dashicon class name
        ));
    }
}
add_action('init', 'register_additional_options_page');
/* user content restriction shortcode */
function custom_pdf() {
	$allowed_users = get_field('users_allowed');
	$current_user = wp_get_current_user()->ID;
	$display_single_content = false;
	
if($allowed_users){
    // start foreach loop
    foreach ($allowed_users as $user){
            if ($current_user == $user){
                $display_single_content = true;
            }
        } 
	// end foreach loop
}
	
	if ($display_single_content !== true){
		// add the styles to hide the content you want of the current page
			echo '<style>
                .single__hide{
                }
                .warning__content{
                    display:none;
                }
                </style>';
		} else {
		echo '<style>
                .single__hide{
                }
                </style>';
	}
	$pdf_document = get_field('main_document');
    $url = $_SERVER['REQUEST_URI'];
	if ($pdf_document) {
        
		if (strpos($url, '/es/') !== false) {
            echo'<button class="back__button" onclick="goBack()">‹</button>';
        echo '<div class="main__content-document single__hide global__hide">';

			echo '<div class="card-content">';
            // Check and echo "doc-row" elements conditionally based on get_field values
            if (get_field('ciudad')) {
                echo '<div class="doc-row"><i class="fa fa-map-marker" aria-hidden="true"></i><p class="text"> ' . get_field('ciudad') . ', ' . get_field('provincia') . '</p></div>';
            }
            if (get_field('tipo_de_proveedor')) {
                echo '<div class="doc-row"><i class="fa fa-bookmark" aria-hidden="true"></i><p class="text"> ' . get_field('tipo_de_proveedor') . '</p></div>';
            }
            if (get_field('hotel_estrellas') > 0 && get_field('tipo_de_proveedor') == 'Hotel') {
                echo '<div class="doc-row"><i class="fa fa-star" aria-hidden="true"></i><p class="text"> ' . get_field('hotel_estrellas') . '</p></div>';
            }
            if (get_field('numeros_de_habitaciones') > 1 && get_field('tipo_de_proveedor') == 'Hotel') {
                echo '<div class="doc-row"><i class="fa fa-bed" aria-hidden="true"></i><p class="text"> ' . get_field('numeros_de_habitaciones') . '</p></div>';
            }
            if (get_field('capacidad') > 0 && get_field('tipo_de_proveedor') == 'Hotel') {
                echo '<div class="doc-row capacidad"><i class="fa fa-user" aria-hidden="true"></i><p class="text">' . get_field('capacidad') . '</p></div>';
            }

            echo '</div>';
		  echo '<div class="doc-wrap"><div class="top__bar"><a href="'.$pdf_document.'" target="_blank" download><i class="fa fa-download" aria-hidden="true"></i> Descargar</a></div><iframe src="' . $pdf_document . '" width="500" height="500" style="width:100%; min-height:70vh;"></iframe></div>';

		echo '</div>';
    } else {
        echo'<button class="back__button" onclick="goBack()">‹</button>';
        echo '<div class="main__content-document single__hide global__hide">';

			echo '<div class="card-content">';
            // Check and echo "doc-row" elements conditionally based on get_field values
            if (get_field('ciudad')) {
                echo '<div class="doc-row"><i class="fa fa-map-marker" aria-hidden="true"></i><p class="text"> ' . get_field('ciudad') . ', ' . get_field('provincia') . '</p></div>';
            }
            if (get_field('tipo_de_proveedor')) {
                echo '<div class="doc-row"><i class="fa fa-bookmark" aria-hidden="true"></i><p class="text"> ' . get_field('tipo_de_proveedor') . '</p></div>';
            }
            if (get_field('hotel_estrellas') > 0 && get_field('tipo_de_proveedor') == 'Hotel') {
                echo '<div class="doc-row"><i class="fa fa-star" aria-hidden="true"></i><p class="text"> ' . get_field('hotel_estrellas') . '</p></div>';
            }
            if (get_field('numeros_de_habitaciones') > 1 && get_field('tipo_de_proveedor') == 'Hotel') {
                echo '<div class="doc-row"><i class="fa fa-bed" aria-hidden="true"></i><p class="text"> ' . get_field('numeros_de_habitaciones') . '</p></div>';
            }
            if (get_field('capacidad') > 0 && get_field('tipo_de_proveedor') == 'Hotel') {
                echo '<div class="doc-row capacidad"><i class="fa fa-user" aria-hidden="true"></i><p class="text">' . get_field('capacidad') . '</p></div>';
            }

            echo '</div>';
		  echo '<div class="doc-wrap"><div class="top__bar"><a href="'.$pdf_document.'" target="_blank" download><i class="fa fa-download" aria-hidden="true"></i> Download</a></div><iframe src="' . $pdf_document . '" width="500" height="500" style="width:100%; min-height:70vh;"></iframe></div>';

		echo '</div>';

		}
		}
    //echo '<div class="warning__content">You can not see this content</div>';
}
add_shortcode('document_pdf', 'custom_pdf');

/* allow custom users */
function allow_custom_users() {
    if ( is_user_logged_in() ){
        $allowed_users = get_field('usuarios_permitidos', 'option');
        $current_user = wp_get_current_user()->roles[0];
        $display_content = false;
        // start foreach loop
        $current_user = wp_get_current_user();
        $user_roles = $current_user->roles;
    
        if (is_array($user_roles) && !empty($user_roles)) {
            $current_role = $user_roles[0];
        }
        if(!empty($allowed_users)){
            foreach ($allowed_users as $user_slug){
                $user = strtolower($user_slug);
                if ($current_role == $user){
                    $display_content = true;
                }
            } 
        }
        // end foreach loop
        
        if ($display_content == false){
            // Add styles to hide the content you want of the current page
            $custom_styles = '
                .global__hide{
                    display:none;
                }
                .warning__content{
                    display:none;
                }
            ';
        } else {
            $custom_styles = '
                .global__hide{
                    display:flex!important;
                }
            ';
        }

        // Add inline styles to the body tag
        wp_add_inline_style('custom-styles', $custom_styles);
    }
}
add_action('wp_enqueue_scripts', 'allow_custom_users');



/** Subscriber Account Shortcode and other Shortcodes * */
function custom_account() {
    // Get the current user
    $user = wp_get_current_user();
    $url = $_SERVER['REQUEST_URI'];
    
    // Check if the URL contains '/es/'
    if (strpos($url, '/es/') !== false) {
      $welcome = 'Bienvenido(a)';
        $logout_link = '<a class="logout__link" href="' . wp_logout_url() . '">Cerrar sesión</a>';
        $login_link = '<a class="logout__link" href="' . wp_login_url() . '">Iniciar sesión</a>';
        $loggedout_msg = '<h1 class="login">Inicie sesión para ver los detalles de su cuenta</h1>';
    } else {
      $welcome = 'Welcome';
        $logout_link = '<a class="logout__link" href="' . wp_logout_url() . '">Logout</a>';
        $login_link = '<a class="logout__link" href="' . wp_login_url() . '">Login</a>';
        $loggedout_msg = '<h1 class="login">Login to see your account details</h1>';
    }
    // Get the PDF document URL
    $pdf_document_url = get_field('pdf_contract', 'user_' . $user->ID);
    $image_contract_url = get_field('image_contract', 'user_' . $user->ID);
    
    // If the PDF document URL is not empty, show the PDF document
        echo '<div class="custom-account-page">';
        if (is_user_logged_in()) {
         echo $logout_link;
            echo '<h1>' . $welcome . ' ' . $user->display_name . '!</h1>';
            $url = $_SERVER['REQUEST_URI'];
            if (strpos($url, 'sign-in') !== false) {
                echo'
                <style>
                .custom-account-page .logout__link {
                    position: static!important;
                    margin-bottom: 22px!important;
                }
                #custom-button-link{
                    display:block!important;
                }
                .custom-account-page {
                    display: flex!important;
                    flex-direction: column-reverse!important;
                }
                </style>
                ';
            }
			/*echo '<a href="/document-archive/?ciudad=&provincia=&tipo_de_proveedor=&hotel_estrellas=0&numeros_de_habitaciones=0&capacidad=0" class="clear-filter-btn">Ver documentos</a>';*/
            if ($image_contract_url) {
              //echo '<img src="' . $image_contract_url . '" width="300" height="auto">';
            }
            if ($pdf_document_url) {
              //echo '<iframe src="' . $pdf_document_url . '" width="500" height="500" style="width:100%; min-height:70vh;"></iframe>';
            }

        } else {
            //echo $login_link;
            // echo $loggedout_msg;
        }
    
        echo '</div>';
    }
    
    
    add_shortcode('my_custom_account', 'custom_account');

/* Search bar landing page shortcode */

function search_bar_landing() {
          // Get the ACF field objects
          $ciudad_field = acf_get_field('ciudad');
          $provincia_field = acf_get_field('provincia');
          $proveedor_field = acf_get_field('tipo_de_proveedor');
         // Retrieve the options from the ACF field objects
         $ciudad_options = $ciudad_field['choices'];
         $provincia_options = $provincia_field['choices'];
         $proveedor_options = $proveedor_field['choices'];
     
         $ciudad_select = '';
         foreach ($ciudad_options as $value => $label) {
             $ciudad_select .= '<option value="' . $value . '">' . $label . '</option>';
         }
        
        $provincia_select = '';
        foreach ($provincia_options as $value => $label) {
            $provincia_select .= '<option value="' . $value . '">' . $label . '</option>';
        }
     
         $proveedor_select = '';
         foreach ($proveedor_options as $value => $label) {
             $proveedor_select .= '<option value="' . $value . '">' . $label . '</option>';
         }
    // Check if the URL contains '/es/'
    $url = $_SERVER['REQUEST_URI'];
    if (strpos($url, '/es/') !== false) { // spanish
        echo '
        <div id="searchbar-shortcode">
        <div class="searchbar-row"><i class="fa fa-bookmark" aria-hidden="true"></i><select class="search-bar-selector" name="proveedor" id="sel_ciudad" ><option value="">¿Qué necesitas?</option><option value="">Todo</option>'. $proveedor_select .'</select></div>

        <div class="searchbar-row"><i class="fa fa-map-marker" aria-hidden="true"></i><select class="search-bar-selector" name="ciudad" id="sel_tipo" ><option value="">¿Dónde?</option><option value="">Todas</option>'. $ciudad_select .'</select></div>
        
        <button style="font-size:1rem;" class="btn" id="buildHrefButton"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
        </div>';
    } else { // english
       echo '
        <div id="searchbar-shortcode">
        <div class="searchbar-row"><i class="fa fa-bookmark" aria-hidden="true"></i><select class="search-bar-selector" name="proveedor" id="sel_ciudad" ><option value="">What do you need?</option><option value="">All</option>'. $proveedor_select .'</select></div>

        <div class="searchbar-row"><i class="fa fa-map-marker" aria-hidden="true"></i><select class="search-bar-selector" name="ciudad" id="sel_tipo" ><option value="">Where?</option><option value="">All</option>'. $ciudad_select .'</select></div>
        
        <button style="font-size:1rem;" class="btn" id="buildHrefButton"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
        </div>';
    }
 
echo '
<script>

// Get references to the select elements and the button
const select1 = document.getElementById("sel_ciudad");
const select2 = document.getElementById("sel_tipo");
const buildHrefButton = document.getElementById("buildHrefButton");

// Add a change event listener to both select elements
select1.addEventListener("change", updateHref);
select2.addEventListener("change", updateHref);

// Function to update the href of the button
function updateHref() {
    const selectedValue1 = select1.value;
    const selectedValue2 = select2.value;
    
    const href = `/document-archive/?ciudad=${selectedValue2}&provincia=&tipo_de_proveedor=${selectedValue1}&hotel_estrellas=0&numeros_de_habitaciones=0&capacidad=0`;
    
    buildHrefButton.href = href;
}

// Add a click event listener to the button
buildHrefButton.addEventListener("click", function(event) {
    // Prevent the default behavior of the button (form submission)
    event.preventDefault();

    // Open the generated link in a new window
    const href = buildHrefButton.href;
    window.open(href, "_blank");
});

// Add a click event listener to the button
buildHrefButton.addEventListener("click", function(event) {
    // Prevent the default behavior of the button (form submission)
    event.preventDefault();

    // Open the generated link in a new window
    const href = buildHrefButton.href;
    window.open(href, "_blank");
});

// Call the updateHref function initially to set the initial href
updateHref();


</script>
    ';
}

add_shortcode('custom_search_bar', 'search_bar_landing');


/* Documents Filter Archive Shortcode */
function page_documents_archive() {
      // Get the ACF field objects
     $ciudad_field = acf_get_field('ciudad');
     $provincia_field = acf_get_field('provincia');
     $proveedor_field = acf_get_field('tipo_de_proveedor');

    // user restrictions
    $global_restriction = 'filter_hide';
    if ( is_user_logged_in() ){
            $global_restriction = '';
    }
 
     // Retrieve the options from the ACF field objects
     $ciudad_options = $ciudad_field['choices'];
     $provincia_options = $provincia_field['choices'];
     $proveedor_options = $proveedor_field['choices'];
 
     $ciudad_select = '';
     foreach ($ciudad_options as $value => $label) {
         $ciudad_select .= '<option value="' . $value . '">' . $label . '</option>';
     }
    
    $provincia_select = '';
    foreach ($provincia_options as $value => $label) {
        $provincia_select .= '<option value="' . $value . '">' . $label . '</option>';
    }
 
     $proveedor_select = '';
     foreach ($proveedor_options as $value => $label) {
         $proveedor_select .= '<option value="' . $value . '">' . $label . '</option>';
     }

     echo '
     <form id="filter-form" class="'.$global_restriction.'">
         <div class="f-wrap ciudad">
             <label for="ciudad">Ciudad</label>
             <select class="filter-field" name="ciudad" id="ciudad" placeholder="Ciudad"><option value="">¿Qué Ciudad?</option>'. $ciudad_select .'</select>
         </div>
        <div class="f-wrap provincia">
             <label for="provincia">Provincia</label>
             <select class="filter-field" name="provincia" id="provincia" placeholder="Provincia"><option value="">¿Qué Provincia?</option>'. $provincia_select .'</select>
         </div>
         <div class="f-wrap proveedor">
             <label for="tipo_de_proveedor">Proveedor</label>
             <select class="filter-field" name="tipo_de_proveedor" id="tipo_de_proveedor" placeholder="Proveedor"><option value="">¿Qué proveedor?</option>'. $proveedor_select .'</select>
         </div>
         <div class="f-wrap sort-by">
             <label for="sort_by">Sort By</label>
             <select name="sort_by" id="sort_by">
                 <option value="title">Title</option>
                 <option value="date">Date</option>
                 <option value="hotel_estrellas">Estrellas</option>
                 <option value="numeros_de_habitaciones">Habitaciones</option>
                 <option value="capacidad">Capacidad</option>
             </select>
         </div>
         <div class="f-wrap sort-order">
             <label for="sort_order">Sort Order</label>
             <select name="sort_order" id="sort_order">
                 <option value="ASC">Ascending</option>
                 <option value="DESC">Descending</option>
             </select>
         </div>
         <div class="f-wrap estrellas only-hotel">
             <label for="hotel_estrellas">Estrellas</label>
             <div class="text icon-text"><i class="fa fa-star" aria-hidden="true"></i> <input class="filter-field" type="number" name="hotel_estrellas" id="hotel_estrellas" min="0" max="6"></div>
             
         </div>
         <div class="f-wrap habitaciones only-hotel">
             <label for="numeros_de_habitaciones">Habitaciones</label>
             <div class="text icon-text"><i class="fa fa-bed" aria-hidden="true"></i> + <input class="filter-field" type="number" name="numeros_de_habitaciones" id="numeros_de_habitaciones" step="10"></div>
             
         </div>
         <div class="f-wrap capacidad only-hotel">
             <label for="capacidad">Capacidad</label>
             <div class="text icon-text"><i class="fa fa-user" aria-hidden="true"></i> <input class="filter-field" type="number" name="capacidad" id="capacidad" step="50"></div>
             
         </div>
         
         <!-- Add more filter fields here if needed -->

         <div class="sub-wrap">
            <button id="clear-filter-btn" type="button">Refresh Filter</button>
            <input id="apply-filter-btn" type="submit" value="Apply Filter">
         </div>
     </form>
     <div id="filtered-results"></div>';
     
}

add_shortcode('documents_archive', 'page_documents_archive');

// AJAX SCRIPT
function call_ajax(){ ?>
    <script>
        (function($){


// Function to update the URL query parameters
function updateURLParams() {
    var filterField1 = $('#ciudad').val();
    var filterField2 = $('#tipo_de_proveedor').val();
    var filterField3 = $('#hotel_estrellas').val();
    var filterField4 = $('#numeros_de_habitaciones').val();
    var filterField5 = $('#capacidad').val();
    var filterField6 = $('#provincia').val();

    var url = new URL(window.location);
    var params = new URLSearchParams(url.search);

    params.set('ciudad', filterField1);
    params.set('provincia', filterField6);
    params.set('tipo_de_proveedor', filterField2);
    params.set('hotel_estrellas', filterField3);
    params.set('numeros_de_habitaciones', filterField4);
    params.set('capacidad', filterField5);

    window.history.replaceState(null, '', '?' + params.toString());
}

// Function to filter the posts
function filterPosts() {
    var filterField1 = $('#ciudad').val();
    var filterField6 = $('#provincia').val();
    var filterField2 = $('#tipo_de_proveedor').val();
    var filterField3 = $('#hotel_estrellas').val();
    var filterField4 = $('#numeros_de_habitaciones').val();
    var filterField5 = $('#capacidad').val();
    var sortBy = $('#sort_by').val(); // Get the selected sort by value
    var sortOrder = $('#sort_order').val(); // Get the selected sort order value

    // Send AJAX request
    $.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>', // ajaxurl is a global WordPress variable
        type: 'POST',
        data: {
            action: 'filter_posts',
            ciudad: filterField1,
            provincia: filterField6,
            tipo_de_proveedor: filterField2,
            hotel_estrellas: filterField3,
            numeros_de_habitaciones: filterField4,
            capacidad: filterField5,
            sort_by: sortBy,
            sort_order: sortOrder,
            // Add more filter fields if needed
        },
        beforeSend: function() {
            // Display loading spinner or any other visual indication
            $('#filtered-results').html('Loading...');
        },
        success: function(response) {
            // Update the filtered results div with the AJAX response
            $('#filtered-results').html(response);
        },
        error: function() {
            $('#filtered-results').html('Error occurred.');
        }
    });
}

function fillValueFields() {
    // Check for search parameters on page load and populate filter fields
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('ciudad')) {
        $('#ciudad').val(urlParams.get('ciudad'));
    }
    if (urlParams.has('provincia')) {
        $('#provincia').val(urlParams.get('provincia'));
    }
    if (urlParams.has('tipo_de_proveedor')) {
        $('#tipo_de_proveedor').val(urlParams.get('tipo_de_proveedor'));
    }
    if (urlParams.has('hotel_estrellas')) {
        $('#hotel_estrellas').val(urlParams.get('hotel_estrellas'));
    }
    if (urlParams.has('numeros_de_habitaciones')) {
        $('#numeros_de_habitaciones').val(urlParams.get('numeros_de_habitaciones'));
    }
    if (urlParams.has('capacidad')) {
        $('#capacidad').val(urlParams.get('capacidad'));
    }
}

// Trigger the function when the filter form is submitted
$('#filter-form').on('submit', function(e) {
    e.preventDefault();
    filterPosts(1);
    updateURLParams(1);
});

// Handle sort by change
$('#sort_by').on('change', function() {
    filterPosts(1);
    updateURLParams(1);
});

// Handle sort order change
$('#sort_order').on('change', function() {
    filterPosts(1);
    updateURLParams(1);
});
// Handle tipo de proveedor change
$('.only-hotel').hide();
$('#tipo_de_proveedor').on('change', function() {
    // Check if the selected value is 'Hotel'
    if ($(this).val() == 'Hotel') {
        // If it is, show elements with class 'only-hotel'
        $('.only-hotel').show();
    } else {
        // If it's not, hide elements with class 'only-hotel'
        $('.only-hotel').hide();
    }
});


// Check for search parameters on page load
function updateFieldsAndOutput() {
    var urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('ciudad') || urlParams.has('provincia') || urlParams.has('tipo_de_proveedor') || urlParams.has('hotel_estrellas') || urlParams.has('numeros_de_habitaciones') || urlParams.has('capacidad')) {
    fillValueFields();
    $("#hotel_estrellas_output").text($("#hotel_estrellas").val());
    $("#numeros_de_habitaciones_output").text($("#numeros_de_habitaciones").val());
    $("#capacidad_output").text($("#capacidad").val());
}
}

$("#hotel_estrellas").on("input", function() {
    $("#hotel_estrellas_output").text($(this).val());
});

$("#numeros_de_habitaciones").on("input", function() {
    $("#numeros_de_habitaciones_output").text($(this).val());
});

$("#capacidad").on("input", function() {
    $("#capacidad_output").text($(this).val());
});

$('#clear-filter-btn').on('click', function() {
    // Reset filter fields to empty values
    $('#ciudad').val('');
	$('#provincia').val('');
    $('#tipo_de_proveedor').val('');
    $('#hotel_estrellas').val('0');
    $('#numeros_de_habitaciones').val('0');
    $('#capacidad').val('0');

    // Trigger form submission to reset and reload the posts
    filterPosts(1);
    updateURLParams(1);
    updateFieldsAndOutput();
});

// on page load
updateFieldsAndOutput();
filterPosts();
	
	
	// REMOVE HREF FROM SINGLE HISTORY CARDS
	// 
	const singleHistory = document.querySelectorAll('.single-history a');
	if(singleHistory){
		[].map.call(singleHistory, function(card){
			card.removeAttribute('href');
		});
	}

        })(jQuery);
    </script>
<?php }
add_action( 'wp_footer', 'call_ajax', 10, 1 );

// AJAX handler for filtering posts
function filter_posts() {
    // Retrieve the filter values from the AJAX request
    $filter_field1 = $_POST['ciudad'];
    $filter_field2 = $_POST['tipo_de_proveedor'];
    $filter_field3 = $_POST['hotel_estrellas'];
    $filter_field4 = $_POST['numeros_de_habitaciones'];
    $filter_field5 = $_POST['capacidad'];
    $filter_field6 = $_POST['provincia'];

    //Permission variables
    $single_permission = acf_get_field('users_allowed');
    $global_permission = acf_get_field('usuarios_permitidos');
    $global_restriction = 'filter_hide';
    if ( is_user_logged_in() ){
        $allowed_users = get_field('usuarios_permitidos', 'option');
        $current_user = wp_get_current_user()->roles[0];
		$current_user_id = strtolower(wp_get_current_user()->display_name);
        $display_content_filter = false;
        $global_restriction = '';
        // start foreach loop
        $current_user = wp_get_current_user();
        $user_roles = $current_user->roles;
    
        if (is_array($user_roles) && !empty($user_roles)) {
            $current_role = $user_roles[0];
        }
        if(!empty($allowed_users)){
            foreach ($allowed_users as $user_slug){
                $user = strtolower($user_slug);
                if ($current_role == $user){
                    $display_content_filter = true;
                }
            } 
        }
        // end foreach loop
        
        if ($display_content_filter !== true){
            $global_restriction = 'filter_hide';
        } 

    }


    // Retrieve the sorting parameters from the AJAX request
    $sort_by = $_POST['sort_by'];
    $sort_order = $_POST['sort_order'];

    global $wpdb;

    $meta_query = array(
        'relation' => 'AND',
    );

    // Add the meta_query conditions for non-empty filter fields
    if (!empty($filter_field1)) {
        $meta_query[] = array(
            'key'     => 'ciudad',
            'value'   => $filter_field1,
            'type'    => 'select',
            'compare' => '=',
        );
    }
    if (!empty($filter_field2)) {
        $meta_query[] = array(
            'key'     => 'tipo_de_proveedor',
            'value'   => $filter_field2,
            'type'    => 'select',
            'compare' => '=',
        );
    }
    if (!empty($filter_field3)) {
        $meta_query[] = array(
            'key'     => 'hotel_estrellas',
            'value'   => $filter_field3,
            'type'    => 'range',
            'compare' => '=',
        );
    }
    if (!empty($filter_field4)) {
        $meta_query[] = array(
            'key'     => 'numeros_de_habitaciones',
            'value'   => $filter_field4,
            'type'    => 'range',
            'compare' => '>=',
        );
    }
    if (!empty($filter_field5)) {
        $meta_query[] = array(
            'key'     => 'capacidad',
            'value'   => $filter_field5,
            'type'    => 'range',
            'compare' => '<=',
        );
    }
    if (!empty($filter_field6)) {
        $meta_query[] = array(
            'key'     => 'provincia',
            'value'   => $filter_field6,
            'type'    => 'select',
            'compare' => '=',
        );
    }


    $args = array(
        'posts_per_page' => 400,
        'post_type'   => 'document',
        'post_status' => 'publish',
        'meta_query'  => $meta_query, // Set the meta_query
        'orderby'     => $sort_by, // Set the sorting field
        'order'       => $sort_order, // Set the sorting order
    );

    $query = new WP_Query($args);
    // Loop through the filtered posts and display them
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
			
			$user_ids = get_field('users_allowed', false, false);
			$single__hide = 'single_filter_hide';
			// Check if any users are selected
			if (!empty($user_ids)) {
				foreach ($user_ids as $user_id) {
					$user = get_userdata($user_id);
					if ($user) {
						// Retrieve the user's display name
						$display_name = strtolower($user->display_name);
						if($display_name === $current_user_id){
							$single__hide = '';
						}
					}
				}
			} else {
                $single__hide = '';
            }
			
            
            echo '<a href="'.get_the_permalink().'" class="card-document '.$global_restriction.' user__'.$single__hide.'">';
            $post_thumbnail_id = get_post_thumbnail_id(); // Get the attachment ID of the post thumbnail

            if (!empty($post_thumbnail_id)) {
                $post_thumb_url = wp_get_attachment_image_src($post_thumbnail_id, 'full');
                if (!empty($post_thumb_url)) {
                    echo '<img class="card-img" src="' . $post_thumb_url[0] . '" width="300" height="auto" style="object-fit:scale-down;">';
                }
            } else {
				$uploads = wp_upload_dir();
				$upload_path = $uploads['baseurl'];
                echo '<img class="card-img" src="'.site_url().'/wp-content/uploads/2023/08/cropped-Trofeo-DMC.png" width="300" height="auto" style="object-fit:contain;">';
            }
            echo '<h2>' . get_the_title() . '</h2>';
            echo '<div class="card-content">';
			
            if(!empty(get_field('ciudad'))) {
            echo '<p class="text icon-text"><i class="fa fa-map-marker" aria-hidden="true"></i> <span>'.get_field('ciudad').', '.get_field('provincia').'</span></p>';
            }
            if(!empty(get_field('tipo_de_proveedor'))) {
            echo '<p class="text icon-text"><i class="fa fa-bookmark" aria-hidden="true"></i> <span>'.get_field('tipo_de_proveedor').'</span></p>';
            }
            if(get_field('hotel_estrellas') > 0 && get_field('tipo_de_proveedor') == 'Hotel') {
            echo '<p class="text icon-text"><i class="fa fa-star" aria-hidden="true"></i> <span>'.get_field('hotel_estrellas').'</span></p>';
            }
            if(get_field('numeros_de_habitaciones') > 1 && get_field('tipo_de_proveedor') == 'Hotel') {
            echo '<p class="text icon-text"><i class="fa fa-bed" aria-hidden="true"></i> <span>'.get_field('numeros_de_habitaciones').'</span></p>';
            }
            if(get_field('capacidad') > 0 && get_field('tipo_de_proveedor') == 'Hotel') {
            echo '<p class="text icon-text"><i class="fa fa-user" aria-hidden="true"></i> <span>'.get_field('capacidad').'</span></p>';
            }
            echo '</div>';
            echo '</a>';
        }
        // Add pagination links after the post loop
         /*   echo '<div class="document-pagination">';
            echo paginate_links(array(
                'total' => $query->max_num_pages,
                'current' => max(1, $query->get('paged')),
            ));
            echo '</div>';
*/
        wp_reset_postdata();
    } else {
        echo 'No results found.';
    }

    wp_die(); // Always include this to end AJAX requests
}

add_action('wp_ajax_filter_posts', 'filter_posts');
add_action('wp_ajax_nopriv_filter_posts', 'filter_posts');


// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'jixic-animate','jixic-bootstrap','jixic-bootstrap-select','jixic-custom-animate','jixic-font-awesome','jixic-icomoon','jixic-imp','jixic-jquery-bootstrap-touchspin','jixic-jquery-fancybox','jixic-owl','jixic-owl-theme-default','jixic-jquery-mcustomscrollbar','jixic-slick','jixic-aos','jixic-swiper','jixic-flaticon','jixic-nouislider','jixic-nouislider-pips','jixic-timepicker','jixic-jquery-ui','jixic-polyglot-language-switcher','jixic-energy-icon','jixic-woocommerce','jixic-custom','jixic-main-style','jixic-tut','jixic-gutenberg','jixic-responsive','jixic-minified-style' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// END ENQUEUE PARENT ACTION

// Function to hide admin bar for specific users
function hide_admin_bar_for_specific_users() {
    // Get the current user object
    $current_user = wp_get_current_user();
    
    // Check if the current user is not "German"
    if ($current_user->user_login !== 'Samantha') {
        // Hide the admin bar for all users except "German"
        add_filter('show_admin_bar', '__return_false');
    }
}

// Hook the function to WordPress
add_action('after_setup_theme', 'hide_admin_bar_for_specific_users');




