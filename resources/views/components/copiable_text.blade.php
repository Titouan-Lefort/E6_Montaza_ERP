@props(['text', 'titre', 'title'])
<p class="block sm:flex text-wrap text-gray-800 dark:text-gray-200" {!! isset($title) ? 'title="' . $title . '"' : '' !!}>
    {!! isset($titre) ? '<strong>' . $titre . '</strong>&nbsp;' : '' !!}
    <span class="copiable_parent w-fit flex relative" onclick="copyToClipboard('{{ $text }}')">
        {!! '<span class="copiable">' . $text . '</span>' !!}
        <svg xmlns="http://www.w3.org/2000/svg" height="1.25rem" viewBox="0 -960 960 960" width="1rem" fill="#e8eaed" class="hidden_copiable icons-no_hover"><path d="M360-240q-33 0-56.5-23.5T280-320v-480q0-33 23.5-56.5T360-880h360q33 0 56.5 23.5T800-800v480q0 33-23.5 56.5T720-240H360Zm0-80h360v-480H360v480ZM200-80q-33 0-56.5-23.5T120-160v-560h80v560h440v80H200Zm160-240v-480 480Z"/></svg>
        <small class="hidden_copiable_small">Copier</small>
    </span>
</p>


