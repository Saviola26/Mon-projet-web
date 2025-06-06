<x-mail::message>
    # Nouveau Séminaire Disponible : {{ $seminaire->theme }}

    Bonjour !

    Un nouveau séminaire vient d'être publié et pourrait vous intéresser :

    Thème : {{ $seminaire->theme }}
    Présenté par : {{ $seminaire->presentateur->name }}
    Date proposée : {{ \Carbon\Carbon::parse($seminaire->date_proposee)->format('d/m/Y') }}

    @if($seminaire->resume)
    Résumé :
    {{ $seminaire->resume }}
    @endif

    Pour plus de détails ou pour vous inscrire (si applicable), veuillez consulter notre plateforme.

    Merci,
    L'équipe de {{ config('app.name') }}
</x-mail::message>