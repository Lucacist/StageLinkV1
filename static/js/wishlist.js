function toggleLikeSimple(button, offreId, event) {
    // Empêcher la propagation de l'événement
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    // Référence au SVG
    const svg = button.querySelector('svg');
    
    // Vérifier si le bouton a déjà la classe "liked"
    const isLiked = button.classList.contains('liked');
    
    // Changer visuellement l'état immédiatement (pour une réponse instantanée)
    if (isLiked) {
        button.classList.remove('liked');
        svg.setAttribute('fill', 'none');
        svg.setAttribute('stroke', '#000000');
    } else {
        button.classList.add('liked');
        svg.setAttribute('fill', 'red');
        svg.setAttribute('stroke', 'red');
    }

    // Utiliser une requête GET simple avec une image invisible pour éviter les problèmes AJAX
    const timestamp = new Date().getTime(); // Évite le cache
    const img = new Image();
    img.onload = function() {
        console.log('Like/unlike action sent to server successfully');
    };
    img.onerror = function() {
        console.log('Like/unlike action sent to server');
    };
    img.style.display = 'none';
    img.src = `index.php?route=toggle_like&offre_id=${offreId}&ts=${timestamp}`;
    document.body.appendChild(img);
    
    // Nettoyer l'image après utilisation
    setTimeout(() => {
        if (img && img.parentNode) {
            img.parentNode.removeChild(img);
        }
    }, 2000);
    
    return false; // Important pour bloquer la propagation
}

// Fonction alternative si la première échoue
function toggleLike(event, button, offreId) {
    event.preventDefault();
    event.stopPropagation();
    
    return toggleLikeSimple(button, offreId, event);
}
