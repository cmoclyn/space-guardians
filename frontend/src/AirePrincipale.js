import React, { useState } from 'react';
import JeuPrincipal from "./JeuPrincipal";
import Nouveautes from "./Nouveautes";

const AirePrincipale = () => {
    const [estConnecte, setEstConnecte] = useState(false);


    const renderContent = () => {
        if (estConnecte) {
            return <JeuPrincipal/>
        }

        return <Nouveautes/>
    }

    return (
        <div className="flex-1 bg-blue-100 h-screen">
            {renderContent()}
            <div className="bg-red-500 text-white text-center font-bold p-10">
                Tailwind fonctionne !
            </div>
        </div>

    )
}

export default AirePrincipale;