import React from 'react';

const UtilisateurNonConnecte = ({ onSeConnecter, onSInscrire }) => {
    return (
        <div>
            <h2>Vous n'êtes pas connecté</h2>
            <button onClick={onSeConnecter}>Se connecter</button>
            <button onClick={onSInscrire}>S'inscrire</button>
        </div>
    );
};

export default UtilisateurNonConnecte;
