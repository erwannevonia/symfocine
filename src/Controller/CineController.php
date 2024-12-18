<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\JsonDecode;

class CineController extends AbstractController
{
    const themoviedbApiKey = "cc14ac256dfbb6167";

    #[Route('/', name: 'app_movie')]
    public function index(): Response
    {
        // Création du endpoint de l'API (film recherché + clé API)
        $endPoint = 'https://api.themoviedb.org/3/discover/movie?include_adult=false&include_video=false&page=1&sort_by=popularity.desc&api_key=' . self::themoviedbApiKey . '&language=fr-FR';

        $tmdbImagePrefix = "https://media.themoviedb.org/t/p/w300_and_h450_bestv2";

        // Lancement d'une requête HTTP
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endPoint);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);

        // Récupération de la réponse
        $resultatCurl = curl_exec($ch);

        // On transforme le résultat de la réponse cURL en un objet JSON utilisable
        $json = json_decode ( $resultatCurl );

        // Rendu du template
        return $this->render('cine/index.html.twig', [
            // On envoie au TWIG uniquement le contenu de Search
            'movies' => $json,
            'ImagePrefix' => $tmdbImagePrefix
        ]);
    }

    #[Route('/{search}', name: 'app_search')]
    public function search(int $search): Response
    {
        // Création du endpoint de l'API (film recherché + clé API)
        $endPoint = 'https://api.themoviedb.org/3/movie/' . $search . '?api_key=' . self::themoviedbApiKey . '&language=fr-FR';

        $tmdbImagePrefix = "https://media.themoviedb.org/t/p/w300_and_h450_bestv2";

        // Lancement d'une requête HTTP
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endPoint);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);

        // Récupération de la réponse
        $resultatCurl = curl_exec($ch);

        // On transforme le résultat de la réponse cURL en un objet JSON utilisable
        $json = json_decode ( $resultatCurl );

        // Rendu du template
        return $this->render('cine/search.html.twig', [
            // On envoie au TWIG uniquement le contenu de Search
            'movies' => $json,
            'ImagePrefix' => $tmdbImagePrefix,
            'movieSearch' => $search
        ]);
    }
}