@props(
['type' => 'error_icon',
 'size' => 1,
 'class' => 'icons-no_hover',
 'id' => '',
  ])
@php
if ($type == 'edit')
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" id="'.$id.'" viewBox="0 -960 960 960" fill="currentColor" width="'.$size.'rem" height="'.$size.'rem" class="'.$class.'"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/></svg>';
else if ($type == 'error_icon') {
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" id="'.$id.'" height="'.$size.'rem" viewBox="0 -960 960 960" width="'.$size.'rem" fill="#EA3323" class="'.$class.'"><path d="M480-280q17 0 28.5-11.5T520-320q0-17-11.5-28.5T480-360q-17 0-28.5 11.5T440-320q0 17 11.5 28.5T480-280Zm-40-160h80v-240h-80v240Zm40 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>';
} else if ($type == 'restore') {
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" id="'.$id.'" height="'.$size.'rem" viewBox="0 -960 960 960" width="'.$size.'rem" class="'.$class.'"><path d="M480-560 320-400l56 56 64-64v168h80v-168l64 64 56-56-160-160Zm-280-80v440h560v-440H200Zm0 520q-33 0-56.5-23.5T120-200v-499q0-14 4.5-27t13.5-24l50-61q11-14 27.5-21.5T250-840h460q18 0 34.5 7.5T772-811l50 61q9 11 13.5 24t4.5 27v499q0 33-23.5 56.5T760-120H200Zm16-600h528l-34-40H250l-34 40Zm264 300Z"/></svg>';
} else if ($type =='arrow_back') {
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" id="'.$id.'" height="'.$size.'rem" viewBox="0 -960 960 960" width="'.$size.'rem" fill="#e8eaed" class="'.$class.'"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/></svg>';
} else if ($type == 'send') {
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" id="'.$id.'" height="'.$size.'rem" viewBox="0 -960 960 960" width="'.$size.'rem" fill="#e8eaed" class="'.$class.'"><path d="M120-160v-240l320-80-320-80v-240l760 320-760 320Z"/></svg>';
} else if ($type == 'bell') {
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" id="'.$id.'" height="'.$size.'rem" viewBox="0 -960 960 960" width="'.$size.'rem" fill="#e8eaed" class="'.$class.'"><path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80ZM320-280h320v-280q0-66-47-113t-113-47q-66 0-113 47t-47 113v280Z"/></svg>';
} else if ($type == 'read') {
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" id="'.$id.'" height="'.$size.'rem" viewBox="0 -960 960 960" width="'.$size.'rem" fill="#e8eaed" class="'.$class.'"><path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/></svg>';
} else if ($type == 'open_in_new') {
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" id="'.$id.'" height="'.$size.'rem" viewBox="0 -960 960 960" width="'.$size.'rem" fill="#e8eaed" class="'.$class.'"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h560v-280h80v280q0 33-23.5 56.5T760-120H200Zm188-212-56-56 372-372H560v-80h280v280h-80v-144L388-332Z"/></svg>';
} else if ($type == 'unread') {
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" id="'.$id.'" height="'.$size.'rem" viewBox="0 -960 960 960" width="'.$size.'rem" fill="#e8eaed" class="'.$class.'"><path d="M480-80q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80Zm0-420ZM160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v13q-11 22-16 45t-4 47q-10-2-19.5-3.5T480-720q-66 0-113 47t-47 113v280h320v-257q18 8 38.5 12.5T720-520v240h80v80H160Zm560-400q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35Z"/></svg>';
} else if ($type == 'arrow_forward') {
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" id="'.$id.'" height="'.$size.'rem" viewBox="0 -960 960 960" width="'.$size.'rem" fill="#e8eaed" class="'.$class.'"><path d="M647-440H160v-80h487L423-744l57-56 320 320-320 320-57-56 224-224Z"/></svg>';
} else if ($type == 'contact') {
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" id="'.$id.'" height="'.$size.'rem" viewBox="0 -960 960 960" width="'.$size.'rem" fill="#e8eaed" class="'.$class.'"><path d="M80-120q-33 0-56.5-23.5T0-200v-560q0-33 23.5-56.5T80-840h800q33 0 56.5 23.5T960-760v560q0 33-23.5 56.5T880-120H80Zm556-80h244v-560H80v560h4q42-75 116-117.5T360-360q86 0 160 42.5T636-200ZM360-400q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm400 160 80-80-60-80h-66q-6-18-10-38.5t-4-41.5q0-21 4-40.5t10-39.5h66l60-80-80-80q-54 42-87 106.5T640-480q0 69 33 133.5T760-240Zm-578 40h356q-34-38-80.5-59T360-280q-51 0-97 21t-81 59Zm178-280q-17 0-28.5-11.5T320-520q0-17 11.5-28.5T360-560q17 0 28.5 11.5T400-520q0 17-11.5 28.5T360-480Zm120 0Z"/></svg>';
} else if ($type == 'copy') {
    $svgIcon = '<svg xmlns="http://www.w3.org/2000/svg" id="'.$id.'" height="'.$size.'rem" viewBox="0 -960 960 960" width="'.$size.'rem" fill="#e8eaed" class="'.$class.'"><path d="M360-240q-33 0-56.5-23.5T280-320v-480q0-33 23.5-56.5T360-880h360q33 0 56.5 23.5T800-800v480q0 33-23.5 56.5T720-240H360Zm0-80h360v-480H360v480ZM200-80q-33 0-56.5-23.5T120-160v-560h80v560h440v80H200Zm160-240v-480 480Z"/></svg>';
} else {
        $svgIcon = (string) \Illuminate\Support\Facades\Blade::render(
        '<x-dynamic-component :component="\'icons.\'.$type" :id="$id" :size="$size" :class="$class" />',
        compact('type', 'id', 'size', 'class')
    );

}
@endphp

{!! $svgIcon !!}
