// src/Main.js
import React from 'react';
import AireUtilisateur from "./AireUtilisateur";
import AirePrincipale from "./AirePrincipale";



function Main() {
    return (
        <div className="flex h-screen">
            <AireUtilisateur/>
            <AirePrincipale/>
        </div>

    );

}

export default Main;