import React, { useState } from 'react';
import UtilisateurNonConnecte from './UtilisateurNonConnecte';
import FormulaireConnexion from './FormulaireConnexion';
import FormulaireInscription from './FormulaireInscription';
import InfosUtilisateur from './InfosUtilisateur';

const AireUtilisateur = () => {
    const [estConnecte, setEstConnecte] = useState(false);
    const [formulaire, setFormulaire] = useState(null); // null, 'connexion' ou 'inscription'

    const afficherFormulaireConnexion = () => {
        setFormulaire('connexion');
    };

    const afficherFormulaireInscription = () => {
        setFormulaire('inscription');
    };

    const afficherInfosUtilisateur = () => {
        setEstConnecte(true);
    };

    const renderContent = () => {
        if (estConnecte) {
            return <InfosUtilisateur />;
        }

        if (formulaire === 'connexion') {
            return <FormulaireConnexion />;
        }

        if (formulaire === 'inscription') {
            return <FormulaireInscription />;
        }

        return (
            <UtilisateurNonConnecte
                onSeConnecter={afficherFormulaireConnexion}
                onSInscrire={afficherFormulaireInscription}
            />
        );
    };

    return (
        <div className="w-[15%] h-16 bg-gray-200">
            {renderContent()}
        </div>
    );
};

export default AireUtilisateur;
