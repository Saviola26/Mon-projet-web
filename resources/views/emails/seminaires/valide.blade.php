<x-mail::message>
    # Séminaire Validé : {{ $seminaire->theme }}

    Bonjour {{ $seminaire->presentateur->name }},

    Nous avons le plaisir de vous informer que votre demande de séminaire pour le thème {{ $seminaire->theme }} a été validée !

    Votre séminaire est programmé pour le {{ \Carbon\Carbon::parse($seminaire->date_validee)->format('d/m/Y') }}.

    N'oubliez pas d'envoyer le résumé de votre présentation 10 jours avant la date du séminaire.

    Merci,
    L'équipe de {{ config('app.name') }}
</x-mail::message>
    