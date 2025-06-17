<img class="foto"
     src="data:{{ mime_content_type($fotoPath) }};base64,{{ base64_encode(file_get_contents($fotoPath)) }}"
     width="150" height="150">
